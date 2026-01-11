<?php

namespace App\Controllers;

use App\Models\Aviso as AvisoModel;
use App\Models\CambioTratamiento as CambioTratamientoModel;
use App\Models\Tratamiento as TratamientoModel;
use App\Models\Solicitud as SolicitudModel;
use App\Models\CambioPrescripcion as CambioPrescripcionModel;
use App\Models\Prescripcion as PrescripcionModel;
use App\Models\Seguimiento as SeguimientoModel;
use App\Entities\Aviso as AvisoEntity;
use App\Entities\Solicitud as SolicitudEntity;
use App\Entities\Seguimiento as SeguimientoEntity;
use App\Enums\Estado as EstadoEnum;
use App\Enums\Seguimiento as SeguimientoEnum;
use Carbon\Carbon;
use Exception;

class Aviso extends BaseController
{     
  // campos clave que se comprueban para decidir si se envía aviso al sincronizar
  private $controlCambiosTratamiento = ['fecha_alta','suspendido','confirmado_hasta','cip','fecha_exitus','obs_tratamiento']; //'nif'
  private $controlCambiosPrescripcion = ['codigo_indicacion','fecha_fin','medi_desc','via_descripcion', 'codigo_frec', 'dosis']; //'valida_hasta'
  
  // roles destinatarios segun tipo de aviso
  private $rolesControlCambios = 'farmacia';
  private $rolesPendientes = 'farmacia';
  private $rolesCaducados = 'prescriptor';



  public function list(){
   
    helper('form');
    $session = session();
    $data['pagina'] = 'listar avisos';
    $data['breadcrumb'] = [["Principal", base_url()],["Avisos", ""]];
    $data['alert'] = $session->getFlashdata('alert') ? $session->getFlashdata('alert') : null;
    $data['errors'] = $session->getFlashdata('errors') ? $session->getFlashdata('errors') : null;
    
    $gestorAcces = service('auth');
    $data['id_usuario'] = $gestorAcces->getIDUser();
    if(!$gestorAcces->can('ver avisos', env('app.appName'))){
        $session->setFlashdata('pagina','listar tratamientos');
        return redirect('error_permisos');
    }
    
    if($gestorAcces->isLogged(false)){
        $data['usuario'] = $gestorAcces->getNomiCognoms();
        $data['permissions'] = $this->permissions;
        $data['rols'] = $gestorAcces->getPerfils(env('app.appName'));
    }

    $avisoModel = new AvisoModel;
    $data['avisos'] = $avisoModel->findAllWithFilters(['procesado' => 0], $data['rols'] ? ($data['rols'][0] == 'admin' ? null : $data['rols'][0] ) : null );

    return view('avisos/listado', $data);
}

  //Llamada ajax que marca aviso com procesado y realiza las acciones pertinentes
  public function procesa(){
      
    $resultat = ['error' => 0, 'txterror' => ''];
    try {
      
      $input = $this->request->getPost();

      if(isset($input['id']) && $input['id'] != null){
        $avisoModel = new AvisoModel;
        $aviso = $avisoModel->find($input['id']);
        
        if($aviso != null){
          $aviso->setProcesado();
          $avisoModel->save($aviso);
        }

      }
      return $this->response->setJSON($resultat);
    }catch(Exception $ex){
      return $this->response->setJSON(['error' => 1, 'txterror' => $ex->getMessage()]);
    }

  }

  /********** PROCESOS EJECUTADOS DESDE TAREAS CRON ************/

    /**
     * Esta función lista tratamientos pendientes de validar
     * se envía a los roles farmacia y admin
     * también inserta registro en la tabla avisos 
     *
   * @return void
   */
  public function pendientes(){

    helper('correo');
    $correosF = getCorreosAviso('farmacia');
    $correosA = getCorreosAviso('admin');
    $correos = array_merge($correosA, $correosF);
      
    echo date('Y-m-d H:i:s') . " Inicio proceso pendientes: Solicitudes pendientes de validar en la aplicación NED" . PHP_EOL;
    
    $avisoModel = new AvisoModel();
    $prescripcionModel = new PrescripcionModel();
    $cambioTipo = 'pendientes';
    $fechaCambios = Carbon::now();

    $tratamientoModel = new TratamientoModel();
    $tratamientos = $tratamientoModel->findPendientes();

    if ($tratamientos){
      $tratamFiltrados = 0;
      echo date('Y-m-d H:i:s') . " Encontradas " . count($tratamientos) . " solicitudes. " .  PHP_EOL;
      //enviar correo con los tratamientos pendientes

      $titulo = "NED - Solicitudes pendientes de validar";
      $htmlHead = '
              <h3>Los siguientes tratamientos prescritos en Silicon están pendientes de validar por Farmacia en la app NED:</h3>
              ';
      $htmlFooter  = '<p>Por favor, accede a la aplicación <a href="'.env('app.baseURL').'" target="_blank">NED</a> para revisar los datos</p>';
      $htmlBody = '<table style="border-collapse: separate; border-spacing: 10px;">
                    <thead>
                    <th style="text-align: left;" scope="col">ID Tratamiento</th>
                    <th style="text-align: left;" scope="col">CIP</th>
                    <th style="text-align: left;" scope="col">Prescriptor</th>
                    <th style="text-align: left;" scope="col">Confirmado hasta</th>
                  </thead>
                  <tbody>';
    
      foreach($tratamientos as $tratamiento){
        
        // TODO: de momento solo enviamos los recordatorios de pendientes que sean:
        // servicio endocrino
        // con confirmado_hasta posterior a la fecha actual 
        // o sin fecha confirmado_hasta TODO: este filtro se deberá quitar una vez revisados los tratamientos
        // ni tenga fecha_exitus ni tenga fecha_alta
        // y que tenga prescripciones válidas
        if($tratamiento->servicio_codigo == 'ENDSVVC' && 
          ($tratamiento->confirmado_hasta == null || $tratamiento->confirmado_hasta > $fechaCambios) &&
          $tratamiento->fecha_exitus == null && 
          $tratamiento->fecha_alta == null &&
          $prescripcionModel->getPrescripcionesTratamiento($tratamiento->id) != null){
        
          $tratamFiltrados ++;

          // insert en la tabla avisos
          $mensaje = "NED - Tratamiento Pendiente de validar";
          $avisoEncontrado = $avisoModel
                        ->where('tratamiento_id', $tratamiento->id)
                        ->where('fecha_aviso',$fechaCambios)
                        ->where('tipo_aviso',$cambioTipo)
                        ->first();
          if ($avisoEncontrado){
            $avisoEncontrado->setMensaje($avisoEncontrado->mensaje . $mensaje);
            $avisoEncontrado->procesado = 0;
            $avisoModel->save($avisoEncontrado);
          }else{
            $nuevoAviso = new AvisoEntity();
            $nuevoAviso->tratamiento_id = $tratamiento->id;
            $nuevoAviso->setFechaAviso();
            $nuevoAviso->mensaje = $mensaje;
            $nuevoAviso->tipo_aviso = $cambioTipo;
            $nuevoAviso->roles_proceso = $this->rolesPendientes;
            $avisoModel->save($nuevoAviso);
          }

          //envío correo
          $htmlBody .= '<tr style="text-align: left;margin: 3px;">
                          <td style="text-align: left;"><a href="'.env('app.baseURL').'/tratamientos/'. $tratamiento->id . '" target="_blank">'. $tratamiento->id . '</a></td>
                          <td style="text-align: left;">'. substr($tratamiento->cip, 0, 4) . str_repeat('*', strlen($tratamiento->cip) - 4) . '</td>
                          <td style="text-align: left;">'.    
                            implode('', array_map(function($namePart) {
                              return strtoupper(isset($namePart[0]) ? $namePart[0].($namePart[0] != ',' ? '.' : ' ') : '');
                            }, explode(' ', str_replace(',',' ,',$tratamiento->usuario)))) . 
                          '</td>
                          <td style="text-align: left;">'. ($tratamiento->confirmado_hasta ? Carbon::parse($tratamiento->confirmado_hasta)->format('d/m/Y') : "-") . '</td>
                        </tr>';
        }
      }
      $htmlBody .= '</tbody></table>';

      if($tratamFiltrados > 0){
        echo date('Y-m-d H:i:s') . " Enviando correo ->" . $tratamFiltrados . " solicitudes. " .  PHP_EOL;
        enviaCorreo(['titulo' => $titulo, 'cuerpo' => $htmlHead.$htmlBody.$htmlFooter], $correos);
      }else{
        echo date('Y-m-d H:i:s') . " No se han encontrado solicitudes pendientes de validar." . PHP_EOL;
      }
    }
          
    echo date('Y-m-d H:i:s') . " Fin del proceso pendientes." . PHP_EOL;

  }

  /**
  * Esta función lista tratamientos que finalizan nDías (configuración) después de la fechaRef
  * se envía a los roles catsalut + prescriptor
  * también inserta registro en la tabla avisos 
   *
   * @return void
   */
  public function finalizan($fechaRef = null){
   
    helper('correo');
    $correosC = getCorreosAviso('catsalut');
    $correosP = getCorreosAviso('prescriptor');
    $correosA = getCorreosAviso('admin');
    $correos = array_merge($correosC, $correosP, $correosA);

    if($fechaRef == null) {
      $data = Carbon::now();
    }else{
      $data = new Carbon($fechaRef);
    } 
    $fechaFinaliza = $data->addDays(DIAS_TRATAMIENTO_FINALIZA)->toDateString();

    echo date('Y-m-d H:i:s') . " Inicio proceso finalizan: Tratamientos que finalizan antes del día " . Carbon::parse($fechaFinaliza)->format('d/m/Y') . PHP_EOL;
    
    $tratamientoModel = new TratamientoModel();
    $tratamientos = $tratamientoModel->findConfirmadosHasta($fechaFinaliza);

    if ($tratamientos){
      echo date('Y-m-d H:i:s') . " Procesar " . count($tratamientos) . " tratamientos. " .  PHP_EOL;
      //enviar correo con los tratamientos próximos a finalizar
      $titulo = "NED - Tratamientos próximos a finalizar";
      $htmlHead = '
              <h3>Los siguientes tratamientos llegarán a la fecha límite de confirmación en los próximos '. DIAS_TRATAMIENTO_FINALIZA. ' días:</h3>
              ';
      $htmlFooter  = '<p>Por favor, accede a la aplicación <a href="'.env('app.baseURL').'" target="_blank">NED</a> para revisar los datos</p>';
      $htmlBody = '<table style="border-collapse: separate; border-spacing: 10px;">
                    <thead>
                    <th style="text-align: left;" scope="col">ID Tratamiento</th>
                    <th style="text-align: left;" scope="col">CIP</th>
                    <th style="text-align: left;" scope="col">Prescriptor</th>
                    <th style="text-align: left;" scope="col">Confirmado hasta</th>
                  </thead>
                  <tbody>';
    
      $tratamientosEnCurso = 0;
      foreach($tratamientos as $tratamiento){
        //se comprueba que la solicitud no esté finalizada, dada de alta o con exitus
        if(!in_array($tratamiento->estado_id, EstadoEnum::finDeCircuito())  && $tratamiento->fecha_exitus == null && $tratamiento->fecha_alta == null) {
          
          $tratamientosEnCurso += 1;
          $htmlBody .= '<tr style="text-align: left;margin: 3px;">
                          <td style="text-align: left;"><a href="'.env('app.baseURL').'/tratamientos/'. $tratamiento->id . '" target="_blank">'. $tratamiento->id . '</a></td>
                          <td style="text-align: left;">'. substr($tratamiento->cip, 0, 4) . str_repeat('*', strlen($tratamiento->cip) - 4) . '</td>
                          <td style="text-align: left;">'.    
                            implode('', array_map(function($namePart) {
                              return strtoupper(isset($namePart[0]) ? $namePart[0].($namePart[0] != ',' ? '.' : ' ') : '');
                            }, explode(' ', str_replace(',',' ,',$tratamiento->usuario)))) . 
                          '</td>
                          <td style="text-align: left;">'. ($tratamiento->confirmado_hasta ? Carbon::parse($tratamiento->confirmado_hasta)->format('d/m/Y') : "-") . '</td>
                        </tr>';
        }
      }
      $htmlBody .= '</tbody></table>';
      if($tratamientosEnCurso > 0){
        echo date('Y-m-d H:i:s') . " Se han encontrado " . $tratamientosEnCurso . " tratamientos no finalizados. Enviando correo" . PHP_EOL;
        enviaCorreo(['titulo' => $titulo, 'cuerpo' => $htmlHead.$htmlBody.$htmlFooter], $correos);
      }else{
        echo date('Y-m-d H:i:s') . " No se han encontrado tratamientos para notificar." . PHP_EOL;
      }
      
    }
          
    echo date('Y-m-d H:i:s') . " Fin del proceso próximos a finalizar." . PHP_EOL;

  }

  /**
   * Esta función lista tratamientos aprobados que llegan a la fecha de confirmado sin haber sido revisados
   * se envía aviso a prescriptores
   * se podría cambiar a estado CADUCADA pero no se hace porque este estado no se utiliza
   *
   * @return void
   */
  public function caducados(){

    helper('correo');
    $correosP = getCorreosAviso('prescriptor');
    $correosA = getCorreosAviso('admin');
    $correos = array_merge($correosP, $correosA);
    
    $fechaFinaliza = Carbon::now();
    $avisoModel = new AvisoModel();
    $cambioTipo = 'caducados';

    echo date('Y-m-d H:i:s') . " Inicio proceso caducados: Revisando tratamientos confirmados hasta el día " . Carbon::parse($fechaFinaliza)->format('d/m/Y') . PHP_EOL ;
    
    $tratamientoModel = new TratamientoModel();
    $tratamientos = $tratamientoModel->findConfirmadosHasta($fechaFinaliza, true);

    if ($tratamientos){
      echo date('Y-m-d H:i:s') . " Encontrados " . count($tratamientos) . " tratamientos." .  PHP_EOL;
      $caducats = 0;
      //enviar correo con tratamientos caducados
      $titulo = "NED - Tratamientos CADUCADOS el " . Carbon::parse($fechaFinaliza)->format('d/m/Y');
      $htmlHead = '
              <h3>El día ' . Carbon::parse($fechaFinaliza)->format('d/m/Y') . ' han caducado los siguientes tratamientos, ya que
              han llegado a la fecha de confirmación sin ser renovados:</h3>
              ';
      $htmlFooter  = '<p>Los tratamientos estan pendientes de ser revisats en Silicon por un prescriptor/a (cambio fecha "Confirmado Hasta" o dar de Alta). También puedes acceder També pots accedir a la aplición <a href="'.env('app.baseURL').'" target="_blank">NED</a> para revisar los datos</p>';
      $htmlBody = '<table style="border-collapse: separate; border-spacing: 10px;">
                    <thead>
                    <th style="text-align: left;" scope="col">ID Tratamiento</th>
                    <th style="text-align: left;" scope="col">CIP</th>
                    <th style="text-align: left;" scope="col">Prescriptor</th>
                    <th style="text-align: left;" scope="col">Confirmado Hasta</th>
                  </thead>
                  <tbody>';
    
      foreach($tratamientos as $tratamiento){
        //Tratamientos encontrados que llegan a la fecha de confirmado y no estan finalizados todavía
        $caducat = $this->cambioPendRenovarTratamiento($tratamiento);
        if($caducat){
          $caducats += 1;
          // inserta en tabla avisos
          $mensaje = "NED - Tratamiento Caducado";
          $avisoEncontrado = $avisoModel
                        ->where('tratamiento_id', $tratamiento->id)
                        ->where('fecha_aviso',$fechaFinaliza)
                        ->where('tipo_aviso',$cambioTipo)
                        ->first();
          if ($avisoEncontrado){
            if(!str_contains($avisoEncontrado->mensaje, $mensaje)){
              $avisoEncontrado->setMensaje($avisoEncontrado->mensaje . $mensaje);
            }
            $avisoEncontrado->procesado = 0;
            $avisoModel->save($avisoEncontrado);
          }else{
            $nuevoAviso = new AvisoEntity();
            $nuevoAviso->tratamiento_id = $tratamiento->id;
            $nuevoAviso->setFechaAviso();
            $nuevoAviso->mensaje = $mensaje;
            $nuevoAviso->tipo_aviso = $cambioTipo;
            $nuevoAviso->roles_proceso = $this->rolesCaducados;
            $avisoModel->save($nuevoAviso);
          }

          $htmlBody .= '<tr style="text-align: left;margin: 3px;">
                        <td style="text-align: left;"><a href="'.env('app.baseURL').'/tratamientos/'. $tratamiento->id . '" target="_blank">'. $tratamiento->id . '</a></td>
                        <td style="text-align: left;">'. substr($tratamiento->cip, 0, 4) . str_repeat('*', strlen($tratamiento->cip) - 4) . '</td>
                        <td style="text-align: left;">'.    
                          implode('', array_map(function($namePart) {
                            return strtoupper(isset($namePart[0]) ? $namePart[0].($namePart[0] != ',' ? '.' : ' ') : '');
                          }, explode(' ', str_replace(',',' ,',$tratamiento->usuario)))) . 
                        '</td>
                        <td style="text-align: left;">'. ($tratamiento->confirmado_hasta ? Carbon::parse($tratamiento->confirmado_hasta)->format('d/m/Y') : "-") . '</td>
                      </tr>';
        }
      }
      $htmlBody .= '</tbody></table>';
      if($caducats > 0){
        echo date('Y-m-d H:i:s') . " Se han encontrado " . $caducats . " tratamientos pendientes de renovar. Enviando correo." . PHP_EOL;
        enviaCorreo(['titulo' => $titulo, 'cuerpo' => $htmlHead.$htmlBody.$htmlFooter], $correos);
      }else{
        echo date('Y-m-d H:i:s') . " No se han encontrado tratamientos pendientes de renovar." . PHP_EOL;
      }
    }else{
      echo date('Y-m-d H:i:s') . " No se han encontrado tratamientos que finalizan la fecha indicada." . PHP_EOL;
    }
          
    echo date('Y-m-d H:i:s') . " Fin del proceso caducados." . PHP_EOL;

  }


  /**
  * Esta función revisa las altas e introduce automáticamente un seguimiento de finalización.
  * Avisa a catsalut y farmacia que el tratamiento ha finalizado.
   *
   * @return void
   */
  public function altas(){
    helper('correo');
    $correosF = getCorreosAviso('farmacia');
    $correosC = getCorreosAviso('catsalut');
    $correosA = getCorreosAviso('admin');

    $correos = array_merge($correosF, $correosA, $correosC);

    $fechaFinaliza = Carbon::now();

    echo date('Y-m-d H:i:s') . " Inicio proceso altas: Revisando tratamientos con fecha de alta (límite" . Carbon::parse($fechaFinaliza)->format('d/m/Y') . ")" . PHP_EOL ;
    
    $tratamientoModel = new TratamientoModel();
    //añadimos seguimiento:finalizado para los tratamientos encontrados que tienen fecha de alta y que no están finalizados aún
    $tratamientos = $tratamientoModel->findAltesFins($fechaFinaliza);

    if ($tratamientos){
     
      echo date('Y-m-d H:i:s') . " Encontrados " . count($tratamientos) . " tratamientos." .  PHP_EOL;
      $altas = 0;
      $htmlBody = '<table style="border-collapse: separate; border-spacing: 10px;">
                      <thead>
                      <th style="text-align: left;" scope="col">ID Tratamiento</th>
                      <th style="text-align: left;" scope="col">CIP</th>
                      <th style="text-align: left;" scope="col">Prescriptor</th>
                      <th style="text-align: left;" scope="col">Confirmado hasta</th>
                      <th style="text-align: left;" scope="col">Fecha Alta</th>
                    </thead>
                    <tbody>';

      foreach($tratamientos as $tratamiento){
      
         // De momento solamente se procesan altas del servicio endocrino
        if($tratamiento->servicio_codigo == 'ENDSVVC'){
          $finalitzat = $this->cambioFinalizadoTratamiento($tratamiento, 'Finalización automática del tratamiento por ALTA a Silicon');
          if($finalitzat){
            $altas += 1;
            $htmlBody .= '<tr style="text-align: left;margin: 3px;">
                          <td style="text-align: left;"><a href="'.env('app.baseURL').'/tratamientos/'. $tratamiento->id . '" target="_blank">'. $tratamiento->id . '</a></td>
                          <td style="text-align: left;">'. substr($tratamiento->cip, 0, 4) . str_repeat('*', strlen($tratamiento->cip) - 4) . '</td>
                          <td style="text-align: left;">'.    
                            implode('', array_map(function($namePart) {
                              return strtoupper(isset($namePart[0]) ? $namePart[0].($namePart[0] != ',' ? '.' : ' ') : '');
                            }, explode(' ', str_replace(',',' ,',$tratamiento->usuario)))) . 
                          '</td>
                          <td style="text-align: left;">'. ($tratamiento->confirmado_hasta ? Carbon::parse($tratamiento->confirmado_hasta)->format('d/m/Y') : "-") . '</td>
                          <td style="text-align: left;">'. Carbon::parse($tratamiento->fecha_alta)->format('d/m/Y') . '</td>
                        </tr>';
          }
        }
      }

      $htmlBody .= '</tbody></table>';

      if($altas > 0){
        //enviar correo con los tratamientos de alta que no estaban finalizados
        $titulo = "NED - Pacientes de ALTA en Silicon hasta " . Carbon::parse($fechaFinaliza)->format('d/m/Y');
        $htmlHead = '
                <h3>Se han encontrado ' . $altas . ' tratamientos dados de alta en Silicon y se ha procedido a finalizar las correspondientes solicitudes en la app de NED:</h3>
                ';
        $htmlFooter  = '<p>Puedes acceder a la aplicación <a href="'.env('app.baseURL').'" target="_blank">NED</a> para revisar los datos</p>';
      
        echo date('Y-m-d H:i:s') . " Se han encontrado y modificado " . $altas . " altas. Enviando correo." . PHP_EOL;
        enviaCorreo(['titulo' => $titulo, 'cuerpo' => $htmlHead.$htmlBody.$htmlFooter], $correos);
      }else{
        echo date('Y-m-d H:i:s') . " No se han encontrado nuevos tratamientos de alta." . PHP_EOL;
      }
      
    }else{
      echo date('Y-m-d H:i:s') . " No se han encontrado nuevos tratamientos de alta." . PHP_EOL;
    }    
    echo date('Y-m-d H:i:s') . " Fin del proceso altas." . PHP_EOL;
     
  }

  /**
   * Esta función revisa los exitus e introduce automáticamente un seguimiento de finalización.
   * Avisa a prescriptores, catsalut y farmacia que el tratamiento ha finalitzado.
   *
   * @return void
   */
  public function exitus(){
    helper('correo');
    $correosF = getCorreosAviso('farmacia');
    $correosC = getCorreosAviso('catsalut');
    $correosA = getCorreosAviso('admin');
    $correosP = getCorreosAviso('prescriptor');

    $correos = array_merge($correosF, $correosA, $correosC, $correosP);

    $fechaFinaliza = Carbon::now();

    echo date('Y-m-d H:i:s') . " Inicio proceso exitus: Revisant tratamientos amb data d'exitus." . PHP_EOL ;
    
    $tratamientoModel = new TratamientoModel();
    // añadimos el seguimiento:finalizado para los tratamientos encontrados con fecha de exitus y que no estan finalizados tadavía
    $tratamientos = $tratamientoModel->findExitusHasta($fechaFinaliza);

    if ($tratamientos){
      echo date('Y-m-d H:i:s') . " Encontrados " . count($tratamientos) . " tratamientos." .  PHP_EOL;
      $exitus = 0;
      $htmlBody = '<table style="border-collapse: separate; border-spacing: 10px;">
                      <thead>
                      <th style="text-align: left;" scope="col">ID Tratamiento</th>
                      <th style="text-align: left;" scope="col">CIP</th>
                      <th style="text-align: left;" scope="col">Prescriptor</th>
                      <th style="text-align: left;" scope="col">Confirmado Hasta</th>
                      <th style="text-align: left;" scope="col">Exitus</th>
                    </thead>
                    <tbody>';

      foreach($tratamientos as $tratamiento){
      
        $finalitzat = $this->cambioFinalizadoTratamiento($tratamiento, 'Finalización automática del tratamiento por EXITUS a Silicon');
        if($finalitzat){
          $exitus += 1;
          $htmlBody .= '<tr style="text-align: left;margin: 3px;">
                        <td style="text-align: left;"><a href="'.env('app.baseURL').'/tratamientos/'. $tratamiento->id . '" target="_blank">'. $tratamiento->id . '</a></td>
                        <td style="text-align: left;">'. substr($tratamiento->cip, 0, 4) . str_repeat('*', strlen($tratamiento->cip) - 4) . '</td>
                        <td style="text-align: left;">'.    
                          implode('', array_map(function($namePart) {
                            return strtoupper(isset($namePart[0]) ? $namePart[0].($namePart[0] != ',' ? '.' : ' ') : '');
                          }, explode(' ', str_replace(',',' ,',$tratamiento->usuario)))) . 
                        '</td>
                        <td style="text-align: left;">'. ($tratamiento->confirmado_hasta ? Carbon::parse($tratamiento->confirmado_hasta)->format('d/m/Y') : "-") . '</td>
                        <td style="text-align: left;">'. Carbon::parse($tratamiento->fecha_exitus)->format('d/m/Y') . '</td>
                      </tr>';
        }
      }
      $htmlBody .= '</tbody></table>';

      if($exitus > 0){
         //enviar correo con tratamientos exitus que no estaban finalizados
        $titulo = "NED - Pacientes EXITUS a Silicon hasta el " . Carbon::parse($fechaFinaliza)->format('d/m/Y');
        $htmlHead = '
                <h3>Se han encontrado ' . $exitus . ' pacientes exitus a Silicon y se ha procedido a finalitzar las solicitudes en la aplicación de NED:</h3>
                ';
        $htmlFooter  = '<p>Puedes acceder a la aplicación <a href="'.env('app.baseURL').'" target="_blank">NED</a> para revisar los datos</p>';
       
        echo date('Y-m-d H:i:s') . " Se han encontrado y modificado " . $exitus . " exitus. Enviando correo." . PHP_EOL;
        enviaCorreo(['titulo' => $titulo, 'cuerpo' => $htmlHead.$htmlBody.$htmlFooter], $correos);
      }else{
        echo date('Y-m-d H:i:s') . " No se han encontrado nuevos pacientes exitus." . PHP_EOL;
      }
    }else{
      echo date('Y-m-d H:i:s') . " No se han encontrado nuevos pacientes exitus." . PHP_EOL;
    }
          
    echo date('Y-m-d H:i:s') . " Fin del proceso exitus." . PHP_EOL;
     
  }

  
  /**
   * Proceso de sincronización llamado desde CRON 
   * Esta función sincroniza los cambios en cambios_tratamientos y cambios_prescripciones con las tablas de producción de tratamientos y prescripciones.
   * Se ejecuta diariamente para procesar los cambios en Silicon, entre la primera parte de la ETL (sincronización de cambios) y la segunda parte (actualización).
   * Envía correos a los roles configurados indicando los cambios significativos (campos clave) realizados.
   *
   * @param string|null $fechaSincro Fecha de referencia para la sincro. Si es null se utiliza la actual
   * @return void
   */
  public function sincroniza($fechaSincro = null){

    if($fechaSincro == null) $fechaSincro = Carbon::now()->toDateString();

      // Sincronizar segun campos clave
      echo  date('Y-m-d H:i:s') . " Inicio proceso sincronización tablas NED de cambios ... ";
      echo "\r\n";
      $cambios = $this->sincronizaCambios($fechaSincro);

      // Enviar correos dependiento del tipo de cambio
      helper('correo');
      $correosF = getCorreosAviso('farmacia');
      $correosC = getCorreosAviso('catsalut');
      $correosA = getCorreosAviso('admin');
      $correosDestino = array_merge($correosC, $correosF, $correosA);

      if ($cambios && $cambios > 0){
       
        $htmlHead = '
                <h3>Sincronización de cambios en la aplicación NED</h3>
                <p>Los siguientes tratamientos/prescripciones han sido modificados:</p>
                ';
        $titulo = "NED - Sincronización Silicon-App NED";
        $htmlFooter  = '<p>Por favor, accede a la aplicación <a href="'.env('app.baseURL').'" target="_blank">NED</a> para revisar los datos</p>';
        $htmlBody = '<table style="border-collapse: separate; border-spacing: 10px;">
                      <thead>
                      <th style="text-align: left;" scope="col">Registro</th>
                      <th style="text-align: left;" scope="col">Cambios</th>
                    </thead>
                    <tbody>';

        //recupera avisos insertados del día
        $avisoModel = new AvisoModel;
        $avisos = $avisoModel->findAllWithFilters(['fecha_aviso' => $fechaSincro], false);

        foreach($avisos as $aviso){
          $htmlBody .= '<tr>
                          <td style="text-align: left;vertical-align: top;"><a href="'.env('app.baseURL').'/tratamientos/'. $aviso->tratamiento_id . '" target="_blank">'. $aviso->tratamiento_id . '</a></td>
                          <td style="text-align: left;">';
          $mensajes = explode('###', $aviso->group_mensaje);
                        foreach ($mensajes as $miss){
                          $htmlBody .= '<div><span style="font-size: .87rem;">'.$miss.'</span></div>';
                        } 
          $htmlBody .= '</td></tr>';

        }
        $htmlBody .= '</tbody></table>';

        echo date('Y-m-d H:i:s') . " Enviando correos cambios a: " . implode(',',$correosDestino);
        echo "\r\n";
        $enviats = enviaCorreo(['titulo' => $titulo, 'cuerpo' => $htmlHead.$htmlBody.$htmlFooter], $correosDestino);

        echo date('Y-m-d H:i:s') . " Enviados " . count($enviats) . ' correos.';
        echo "\r\n";
      }
            
      echo date('Y-m-d H:i:s') . " Fin del proceso de sincronización.";
      echo "\r\n";
  }

  /********** FIN PROCESOS EJECUTADOS DESDE TAREAS CRON ************/


  /********** MÉTODOS PRIVADOS ************/

  /**
   * Procesa los cambios de la tabla de cambios y los registra en la tabla de avisos.
   * Devuelve el número de cambios procesados.
   * Para determinar si hay cambios a notificar, se tienen en cuenta los campos definidos en controlCambiosPrescripcion y controlCambiosTratamiento.
   *
   * @param string $fechaSincro Fecha de referencia para la sincronización.
   * @return int Número de cambios procesados.
   */
  private function sincronizaCambios($fechaSincro){
  
    try{      

      $arrCambiosTratamientos = [];
      $arrCambiosPrescripciones = [];
      $arrCambiosCampos = [];
      $nCambiosProcesados = 0;
      $nCambiosTratamientos = 0;
      $nCambiosPrescripciones = 0;
      $cambiosTModel = new CambioTratamientoModel();
      $tratamientoModel = new TratamientoModel();
      $solicitudModel = new SolicitudModel();
      $cambiosPModel = new CambioPrescripcionModel();
      $prescripcionModel = new PrescripcionModel();
      $avisoModel = new AvisoModel();
      
      // Procesamos cambios en tratamientos
      $cambiosT = $cambiosTModel->findByDataControl($fechaSincro);
      foreach($cambiosT as $cambio){
        //obtenemos datos del tratamiento actual en la BD
        $tratamientoActual = $tratamientoModel->find($cambio->id);
        if($tratamientoActual && $cambio->estado_registro == 'changed'){
          $arrCambiosCampos = [];
          foreach($this->controlCambiosTratamiento as $campoT){
            //comprobamos si han cambiado los campos clave
            if($cambio->{$campoT} != $tratamientoActual->{$campoT}){
              //hay cambio
              array_push($arrCambiosCampos, [['field' => $campoT, 'new' => $cambio->{$campoT} , 'old' => $tratamientoActual->{$campoT} ]]);

              //Tratar cambio en fecha confirmado_hasta
              if($campoT == 'confirmado_hasta'){
                $fechaCambio = Carbon::now()->toDateString();
                $this->cambioConfirmacionSolicitud($cambio->id, $fechaCambio);
              }

            }
          }
          if (count($arrCambiosCampos) > 0){
            array_push($arrCambiosTratamientos, ['tratamiento'=>$cambio->id, 'tipo' => 'changed', 'cambios' => $arrCambiosCampos]); 
          }

        }elseif($cambio->estado_registro == 'deleted'){
          array_push($arrCambiosTratamientos, ['tratamiento'=>$cambio->id, 'tipo' => 'deleted', 'cambios' => 'Registro borrado']); 

        }elseif($cambio->estado_registro == 'new'){
          array_push($arrCambiosTratamientos, ['tratamiento'=>$cambio->id, 'tipo' => 'new', 'cambios' => 'Registro nuevo']); 
        }

        $nCambiosProcesados += 1;
        
      }

      $nCambiosTratamientos = count($arrCambiosTratamientos);

      // insert en tabla avisos
      foreach($arrCambiosTratamientos as $cambio){
        if (count($cambio['cambios']) > 0){
          //si hay cambios insertamos avisos
          $mensaje = '';
          foreach ($cambio['cambios'] as $arrCampos){
            foreach ($arrCampos as $campo){
              $mensaje .= '###Campo ' . $campo['field'] . ' pasa de ' . ($campo['old'] ?? '[NULL]') . ' a ' . ($campo['new'] ?? '[NULL]') . PHP_EOL;
            }
          }
        
          $avisoEncontrado = $avisoModel->where('tratamiento_id', $cambio['tratamiento'])->where('fecha_aviso',$fechaSincro)->first();
          if ($avisoEncontrado){
            $avisoEncontrado->setMensaje($avisoEncontrado->mensaje . $mensaje);
            $avisoEncontrado->procesado = 0;
            $avisoModel->save($avisoEncontrado);
          }else{
            $nuevoAviso = new AvisoEntity();
            $nuevoAviso->tratamiento_id = $cambio['tratamiento'];
            $nuevoAviso->setFechaAviso();
            $nuevoAviso->mensaje = $mensaje;
            $nuevoAviso->tipo_aviso = $cambio['tipo'];
            $nuevoAviso->roles_proceso = $this->rolesControlCambios;
            $avisoModel->save($nuevoAviso);
          }

        }
      }

      // *** Procesamos cambios en prescripciones ***
      $cambiosP = $cambiosPModel->findByDataControl($fechaSincro);
      foreach($cambiosP as $cambio){
        //obtenemos datos del tratamiento y prescripcion actual a la BD
        $prescripcionActual = $prescripcionModel->find($cambio->id_prescripcion);
        $solicitud = $solicitudModel->findByTratamiento($cambio->id);
        
        // solo se notifican cambios en prescripciones si la solicitud se encuentra en un estado notificable (no pendiente + validada | aprobada)
        if ($solicitud && $solicitud->estado_id > 0 && in_array($solicitud->estado_id, EstadoEnum::notificarCambios())){

          if($prescripcionActual && $cambio->estado_registro == 'changed'){
            $arrCambiosCampos = [];
            foreach($this->controlCambiosPrescripcion as $campP){
            //comprobamos si han cambiado los campos clave
              if($cambio->{$campP} != $prescripcionActual->{$campP}){
                //hay cambio
                array_push($arrCambiosCampos, [['field' => $campP, 'new' => $cambio->{$campP} , 'old' => $tratamientoActual->{$campP} ]]);
              }
            }
            if (count($arrCambiosCampos) > 0){
              array_push($arrCambiosPrescripciones, ['prescripcion' => $cambio->id_prescripcion, 'tratamiento'=>$cambio->id_tratamiento, 'tipo' => 'changed', 'cambios' => $arrCambiosCampos]); 
            }

          }elseif($cambio->estado_registro == 'deleted'){
            array_push($arrCambiosPrescripciones, ['prescripcion' => $cambio->id_prescripcion, 'tratamiento'=>$cambio->id_tratamiento, 'tipo' => 'deleted', 'cambios' => 'Prescripción borrada']); 

          }elseif($cambio->estado_registro == 'new' && $solicitud){
            array_push($arrCambiosPrescripciones, ['prescripcion' => $cambio->id_prescripcion, 'tratamiento'=>$cambio->id_tratamiento, 'tipo' => 'new', 'cambios' => 'Nueva prescripción']); 
          }
        }

        $nCambiosProcesados += 1;
        
      }

      $nCambiosPrescripciones = count($arrCambiosPrescripciones);

      //insert prescripciones en tabla avisos
      foreach($arrCambiosPrescripciones as $cambio){
        $mensaje = '';

        if (is_array($cambio['cambios'])){
          
          foreach ($cambio['cambios'] as $arrCampos){
            foreach ($arrCampos as $campo){
              $mensaje .= '###Campo ' . $campo['field'] . ' pasa de ' . ($campo['old'] ?? '[NULL]') . ' a ' . ($campo['new'] ?? '[NULL]') . PHP_EOL;
            }
          }
        }else{
          $mensaje = $cambio['cambios'] . PHP_EOL;
        }

        $avisoEncontrado = $avisoModel->where('tratamiento_id', $cambio['tratamiento'])
                                ->where('prescripcio_id', $cambio['prescripcion'])
                                ->where('fecha_aviso',$fechaSincro)->first();
        if ($avisoEncontrado){
          $avisoEncontrado->setMensaje($avisoEncontrado->mensaje . $mensaje);
          $avisoEncontrado->procesado = 0;
          $avisoModel->save($avisoEncontrado);
        }else{
          $nuevoAviso = new AvisoEntity();
          $nuevoAviso->tratamiento_id = $cambio['tratamiento'];
          $nuevoAviso->prescripcio_id = $cambio['prescripcion'];
          $nuevoAviso->setFechaAviso();
          $nuevoAviso->mensaje = $mensaje;
          $nuevoAviso->tipo_aviso = $cambio['tipo'];
          $nuevoAviso->roles_proceso = $this->rolesControlCambios;
          $avisoModel->save($nuevoAviso);
        }
      }

      echo date('Y-m-d H:i:s') . " Sincronización correcta (". $nCambiosProcesados ." registros procesados, " . $nCambiosTratamientos . " tratamientos y " . $nCambiosPrescripciones . " prescripciones insertadas en avisos)". PHP_EOL;
      return  $nCambiosTratamientos + $nCambiosPrescripciones;

    } catch (Exception $e) {
      echo date('Y-m-d H:i:s') . " Sincronización ERROR: ". $e->getMessage() . PHP_EOL;
      return false;
    }    

  }

  /**
   * Cuando se detecta un cambio en la fecha de confirmado_hasta del tratamiento,
   * asigna la fecha actual como fecha de última confirmación a la solicitud
    * 
   * @param mixed $idTratamiento El tratamiento/solicitud que se monitoriza.
   * @return boolean Devuelve true si se ha podido aplicar el cambio
   * 
   * 
   */
  private function cambioConfirmacionSolicitud($idTratamiento, $fechaConfirmadoHasta){
      
      $solicitudModel = new SolicitudModel;
      $datos = $solicitudModel->findByTratamiento($idTratamiento);
      if($datos == null){
        $datos = new SolicitudEntity();
        $datos->tratamiento_id = $idTratamiento;
        $datos->setUsuarioCreacion();
        $datos->setEstado();
      }

      $datos->setUsuarioModificacion();
      $datos->setFechaCambioConfirmado($fechaConfirmadoHasta);

      try{
        return $solicitudModel->save($datos);
      }catch(Exception $e){
        error_log($e->getMessage());
        return false;
      }
    
  }

  /**
   * Asigna un seguimiento de finalización cuando un tratamiento es dado de alta.
   * Esta función actualiza el estado del tratamiento a "Finalizado" y crea un seguimiento automático de finalización.
   * Como requisitos, se comprueba que el estado no sea ya Rechazado o Finalizado.
   * 
   * @param mixed $tratamiento El tratamiento al cual se aplica el seguimiento.
   * @return boolean devuelve true si se ha podido aplicar el cambio, o false en caso contrario.
   * 
   */
  private function cambioFinalizadoTratamiento($tratamiento, $observaciones = ''){
    
    try{
     
      $solicitudModel = new SolicitudModel;
      $datos = $solicitudModel->findByTratamiento($tratamiento->id);
      if($datos == null){
        // Si no tenia estado, creamos nuevo registro a solicitudes
        $datos = new SolicitudEntity();
        $datos->tratamiento_id = $tratamiento->id;
        $datos->setUsuarioCreacion();
      }else{
        if(!in_array($datos->estado_id, EstadoEnum::sePuedenFinalizar())){
          return false;
        }
      }
             
      $datos->setUsuarioModificacion();
      $datos->setEstado(EstadoEnum::FINALIZADA->value);
      $datosOk = $solicitudModel->save($datos);

      if($datosOk){

        $seguimientoModel = new SeguimientoModel();
        $nuevo_seg = new SeguimientoEntity();
        $nuevo_seg->tratamiento_id = $tratamiento->id;
        $nuevo_seg->observaciones = $observaciones;
        $nuevo_seg->tipo_seguimiento_id = SeguimientoEnum::FINALIZAR->value;
        $nuevo_seg->setFechaSeguimiento();
        $nuevo_seg->setUsuarioCreacion();
        $nuevo_seg->setUsuarioModificacion();
      
        $segOk = $seguimientoModel->save($nuevo_seg);
        return $datosOk && $segOk;
      }

      return false;

    }catch(Exception $e){
      error_log($e->getMessage());
      return false;
    }
  
  }

  /**
  * 
  * Asigna un seguimiento de pendiente de renovar y actualiza el estado del tratamiento a "Caducado" 
  * cuando un tratamiento que está aprobado llega a la fecha de confirmado_hasta.
  * 
  * @param mixed $tratamiento El tratamiento al cual se aplica el seguimiento.
  * @return boolean Retorna true si se ha podido aplicar el cambio, o false en caso contrario.
  * 
   */
  private function cambioPendRenovarTratamiento($tratamiento){
    
    try{
     
      $solicitudModel = new SolicitudModel;
      $datos = $solicitudModel->findByTratamiento($tratamiento->id);
      if($datos == null){
        // Si no tenia estado no hacer nada
        return false;
      }else{
        if($datos->estado_id != EstadoEnum::APROBADA->value){
          //si no está como aprobada, tampoco hacer nada
          return false; 
        }
      }
      
      // El resto de caoss, en que está aprobada y se ha pasado de fecha, queda pendiente de revisar
      return true;

    }catch(Exception $e){
      error_log($e->getMessage());
      return false;
    }
  
  }

}
