<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\Tratamiento as TratamientoModel;
use App\Models\Prescripcion as PrescripcionModel;
use App\Models\Seguimiento as SeguimientoModel;
use App\Models\Solicitud as SolicitudModel;
use App\Models\Estado as EstadoModel;
use App\Models\Paciente as PacienteModel;
use App\Models\Indicacion as IndicacionModel;
use App\Models\TipoSeguimiento as TipoSeguimientoModel;
use App\Models\EquipoAdministracion as EquipoAdministracionModel;
use App\Models\UsuarioSilicon as UsuarioSiliconModel;
use App\Entities\Tratamiento as TratamientoEntity;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Tratamiento extends BaseController
{

  const ESTADO_ELIMINADO = 7;
  const TIPO_SEGUIMIENTO_ELIMINADO  = 9;
  const TIPO_SEGUIMIENTO_EQUIPO_ADMINISTRACION  = 12;

    /**
     * Lista tratamientos importados en la aplicación, admite filtros pasados por GET
     * @return string
     */
    
    public function list($tratamientoId = null)
    {
        helper('form');
        $session = session();
        $data['pagina'] = 'listar tratamientos';
        $data['breadcrumb'] = [["Principal", base_url()],["Tratamientos", ""]];
        $data['alert'] = $session->getFlashdata('alert') ? $session->getFlashdata('alert') : null;
        $data['errors'] = $session->getFlashdata('errors') ? $session->getFlashdata('errors') : null;
        
        $gestorAcces = service('auth');
        $data['id_usuario'] = $gestorAcces->getIDUser();
        if(!$gestorAcces->can('listar tratamientos', env('app.appName'))){
            $session->setFlashdata('pagina','listar tratamientos');
            return redirect('error_permisos');
        }
        
        if($gestorAcces->isLogged(false)){
            $data['usuario'] = $gestorAcces->getNomiCognoms();
            $data['permissions'] = $this->permissions;
            $data['rols'] = $gestorAcces->getPerfils(env('app.appName'));
            $data['is_admin'] = in_array('admin', $data['rols']);
        }

        $filtros = $this->request->getGet();

        if($tratamientoId != null){
          //filtro id_tratamiento
          $filtros['tratamiento_id'] = $tratamientoId;
        
        }elseif(count($filtros) == 0 && count($data['rols']) == 1){
          //aplica filtros de estado por defecto según rol, definidos opcionalmente en app/Config/Constants
          $filtros['estado_id'] = json_decode(FILTROS_ESTADO)->{$data['rols'][0]};
        }
        
        $data['filtres'] = $filtros;
        $data['tratamientos'] = $this->getTratamientos($filtros);

        //carregar dades pels filtres
        $data['estados'] = (new EstadoModel())->findAll();
        $data['usuarios'] = (new UsuarioSiliconModel())->findAll();
        $data['servicios'] = (new TratamientoModel())->getServicios();

        //Dades pels seguiments
        $data['tipo_seguimientos'] = (new TipoSeguimientoModel())->findAll();
        $data['equipos_administracion'] = (new EquipoAdministracionModel())->findAll();
        
        //Hardcoded: id del tipus de seguiment que fa l'eliminació
        $data['tipo_seguimiento_eliminacion'] = self::TIPO_SEGUIMIENTO_ELIMINADO;
        
        return view('tratamientos/listado', $data);
    }

    /**
     * Muestra los datos relevantes de la solicitud relacionada con el id de tratamiento
     * @return string
     */
    
     public function solicitud($tratamientoId)
     {
        helper('form');
        $session = session();
        $data['pagina'] = 'veure solicitud';
        $data['breadcrumb'] = [["Principal", base_url()],["Tratamientos", base_url().route_to('list_tratamientos')], ["Solicitud ", ""]];
        $data['alert'] = $session->getFlashdata('alert') ? $session->getFlashdata('alert') : null;
        $data['errors'] = $session->getFlashdata('errors') ? $session->getFlashdata('errors') : null;
        
        $gestorAcces = service('auth');
        $data['id_usuarioo'] = $gestorAcces->getIDUser();
        if(!$gestorAcces->can('listar tratamientos', env('app.appName'))){
            $session->setFlashdata('pagina','listar tratamientos');
            return redirect('error_permisos');
        }
        
        if($gestorAcces->isLogged(false)){
            $data['usuario'] = $gestorAcces->getNomiCognoms();
            $data['permissions'] = $this->permissions;
            $data['rols'] = $gestorAcces->getPerfils(env('app.appName'));
        }
         
        $tratamientoModel = new TratamientoModel();
        $tratamiento = $tratamientoModel->findTratamientoCompleto($tratamientoId);
        if($tratamiento){

          $tratamiento->duracion = $this->duracionTratamiento($tratamiento);

          //Asignar los datos de seguimiento a cada tratamiento
          $seguimientoModel = new SeguimientoModel();
          $seguimientos = $seguimientoModel->getSeguimientosTratamiento($tratamiento->id);

          if(count($seguimientos) > 0){
              $tratamiento->seguimientos = $seguimientos;

              foreach($tratamiento->seguimientos as $seguiment) {
                //Assignar dades de l'usuario de la validacio si n'hi ha
                if (!isset($tratamiento->validacio) && $seguiment->tipo_seguimiento_id == 1){
                  $tratamiento->validacion = new \stdClass();
                  $tratamiento->validacion->validador = $seguiment->getUsuarioCreacion();
                  $tratamiento->validacion->fecha = $seguiment->getFechaSeguimientoPretty();
                } 
    
                if (!isset($tratamiento->aprobacion) && $seguiment->tipo_seguimiento_id == 3){
                  $tratamiento->aprobacion = new \stdClass();
                  $tratamiento->aprobacion->aprobador = $seguiment->getUsuarioCreacion();
                  $tratamiento->aprobacion->fecha = $seguiment->getFechaSeguimientoPretty();
                } 
    
                if (!isset($tratamiento->denegacion) && $seguiment->tipo_seguimiento_id == 8){
                  $tratamiento->denegacion = new \stdClass();
                  $tratamiento->denegacion->denegador = $seguiment->getUsuarioCreacion();
                  $tratamiento->denegacion->fecha = $seguiment->getFechaSeguimientoPretty();
                } 

                if (!isset($tratamiento->finalizacion) && $seguiment->tipo_seguimiento_id == 7){
                  $tratamiento->finalizacion = new \stdClass();
                  $tratamiento->finalizacion->finalizador = $seguiment->getUsuarioCreacion();
                  $tratamiento->finalizacion->fecha = $seguiment->getFechaSeguimientoPretty();
                } 
              }
          }         

          //Recuperar datos del método de administración de los segumientos
          $tratamiento->equipo = $seguimientoModel->getUltimoSeguimiento($tratamiento->id, self::TIPO_SEGUIMIENTO_EQUIPO_ADMINISTRACION);

          //Asignar datos de prescripciones
          $prescripcionModel = new PrescripcionModel();
          $prescripciones = $prescripcionModel->getPrescripcionesTratamiento($tratamiento->id, true);

          //Si totdas estan caducadas se muestran todas, sino solamente las vigentes 
          if(count($prescripciones) > 0){
            if($tratamiento->prescripciones_totales == $tratamiento->prescripciones_caducadas){
              $tratamiento->prescripciones = $prescripciones;
            }else{
              $tratamiento->prescripciones = array_filter($prescripciones, fn($prescripcio) => $prescripcio->caducada == 0);
            }
          }
                   
          //Recuperar datos de paciente
          $pacientModel = new PacienteModel();
          $paciente = $pacientModel->findPacienteByCip($tratamiento->cip);
          if ($paciente){
              $tratamiento->paciente = $paciente;
          }
          
          //Recuperar datos de indicación
          $indicacionModel = new IndicacionModel();
          $indicaciones = $indicacionModel->findIndicacionesTratamiento($tratamiento->id, $tratamiento->prescripciones_totales == $tratamiento->prescripciones_caducadas);
          if ($indicaciones){
              $tratamiento->indicaciones = $indicaciones;
          }

          $data['tratamiento'] = $tratamiento;
        }
        return view('tratamientos/solicitud', $data);
     }
 

    /* * Retorna un tratamiento con sus datos completos
     * @return TratamientoEntity
    */
    public function getTratamiento()
    {
        $input = $this->request->getPost();

        $tratamiento = new TratamientoEntity();
        
        $tratamientoModel = new TratamientoModel();
        if (isset($input['tratamiento_id'])){
            $tratamiento = $tratamientoModel->find($input['tratamiento_id']);
        }

        if($tratamiento){

          //Asignar datos extra del tratamiento
          $solicitudModel = new solicitudModel();
          $solicitud = $solicitudModel->getSolicitud($tratamiento->id);
          if($solicitud){
            $tratamiento->estado = $solicitud->estado;
            $tratamiento->fecha_cambio_confirmado = $solicitud->fecha_cambio_confirmado;
          }

          //Assignar datos de seguimientos
          $seguimientoModel = new SeguimientoModel();
          $seguimientos = $seguimientoModel->getSeguimientosTratamiento($tratamiento->id);

          if(count($seguimientos) > 0){
              $tratamiento->seguiments = $seguimientos;
          }

          //Assignar datos de prescripciones
          $prescripcionModel = new PrescripcionModel();
          $prescripciones = $prescripcionModel->getPrescripcionesTratamiento($tratamiento->id);

          if(count($prescripciones) > 0){
              $tratamiento->prescripciones = $prescripciones;
          }
        }
       
        return $this->response->setJSON($tratamiento);
    }

    /* Retorna un string con las indicaciones de las prescripciones
     * @param array $arrPrescripcions
     * @return string
    */
    public function getIndicacionesFromPrescripciones($arrPrescripcions)
    {

      $indicaciones = array_column($arrPrescripcions, 'indicacion','codigo_indicacion');
      $retorno = '';
      return ksort($indicaciones,0);
      foreach ($indicaciones as $iKey=>$iValue){
        $retorno .= ('/'.$iKey.': '.$iValue);
        
      }
      echo substr($retorno,1);
        
      return substr($retorno,1);
    }


    /* * Exporta a Excel el listado de tratamientos que cumplen los filtros indicados
     * @return void
    */
    public function export()
    {
      
      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
  
      $filename = 'Listado_NED.xlsx';
      $filtros = $this->request->getGet();
      $tratamientos = $this->getTratamientos($filtros );
  
      $array_tratamientos = [];
      $fila = [];
  
      foreach($tratamientos as $tratamiento){    

        //datos de prescripciones
        $prescripcionModel = new PrescripcionModel();
        $prescripciones = $prescripcionModel->getPrescripcionesTratamiento($tratamiento->id);

        if(count($prescripciones) > 0){
            $tratamiento->prescripciones = $prescripciones;
        }
                 
        //datos de paciente
        $pacientModel = new PacienteModel();
        $paciente = $pacientModel->findPacienteByCip($tratamiento->cip);
        if ($paciente){
            $tratamiento->paciente = $paciente;
        }
        
        //datos de indicación
        $indicacionModel = new IndicacionModel();
        $indicaciones = $indicacionModel->findIndicacionesTratamiento($tratamiento->id,$tratamiento->prescripciones_totales == $tratamiento->prescripciones_caducadas);
        if ($indicaciones){
            $tratamiento->indicaciones = $indicaciones;
        }

        //asignación a array para bolcado a excel
        $fila = [];
        //A
        array_push($fila, $tratamiento->id);
        //B
        array_push($fila, $tratamiento->usuario);
        //C
        array_push($fila, $tratamiento->usuario_servicio);
        //D nombre paciente
        array_push($fila, $tratamiento->apellidos_paciente . ', ' . $tratamiento->nombre_paciente);
        //E cip
        array_push($fila, $tratamiento->cip);
        //F direccion
        array_push($fila, (isset($tratamiento->paciente) && !empty($tratamiento->paciente->calle) ? 
                    $tratamiento->paciente->calle : $tratamiento->direccion));
        //G codigo postal
        array_push($fila, (isset($tratamiento->paciente) && !empty($tratamiento->paciente->codigo_postal) ? 
                    $tratamiento->paciente->codigo_postal : $tratamiento->codigo_postal));
        //H municipio
        array_push($fila, (isset($tratamiento->paciente) && !empty($tratamiento->paciente->poblacion) ? 
                    $tratamiento->paciente->poblacion : $tratamiento->poblacion));
        //I telefono
        array_push($fila, (isset($tratamiento->paciente) && !empty($tratamiento->paciente->telefono) ? 
        $tratamiento->paciente->telefono : $tratamiento->telefono));
        //J indicaciones    
        if(isset($tratamiento->indicaciones) && count($tratamiento->indicaciones) > 0){
          $indicaciones = (array_map(fn($indicacion) => (isset($indicacion->codigo) ? $indicacion->codigo .': '.$indicacion->descripcion : ''), $tratamiento->indicaciones));        
        }else{
          $indicaciones = [];
        }
        array_push($fila, implode(';',$indicaciones));

        //K, L, M: Duración del tratamiento
        $fechaInicio = $tratamiento->fecha_cambio_confirmado ? Carbon::parse($tratamiento->fecha_cambio_confirmado) : Carbon::parse($tratamiento->fecha_ingreso);
        $fechaFin = $tratamiento->confirmado_hasta ? Carbon::parse($tratamiento->confirmado_hasta) : '';
        $duracion = $this->duracionTratamiento($tratamiento);
        array_push($fila,  $fechaInicio != '' ? $fechaInicio->format('d/m/Y') : '');
        array_push($fila,  $fechaFin != '' ? $fechaFin->format('d/m/Y') : '');
        array_push($fila,  $duracion != null ? $duracion : '');

        //N observaciones
        array_push($fila, (isset($tratamiento->observaciones) ? $tratamiento->observaciones : ''));
        //O alta
        $fechaAlta = $tratamiento->fecha_alta ? Carbon::parse($tratamiento->fecha_alta) : '';
        array_push($fila,  $fechaAlta != '' ? $fechaAlta->format('d/m/Y') : '');
        //P exitus
        $dataExitus = $tratamiento->fecha_exitus ? Carbon::parse($tratamiento->fecha_exitus) : '';
        array_push($fila,  $dataExitus != '' ? $dataExitus->format('d/m/Y') : '');
        //Q estdo
        array_push($fila, $tratamiento->estado);
       
        array_push($array_tratamientos, $fila);

      } 

      $sheet->setTitle("NED");
      $sheet->setCellValue('A1', 'ID');
      $sheet->setCellValue('B1', 'PRESCRIPTOR');
      $sheet->setCellValue('C1', 'SERVICIO');
      $sheet->setCellValue('D1', 'NOMBRE PACIENTE');
      $sheet->setCellValue('E1', 'CIP');
      $sheet->setCellValue('F1', 'DIRECCION');
      $sheet->setCellValue('G1', 'CP');
      $sheet->setCellValue('H1', 'LOCALIDAD');
      $sheet->setCellValue('I1', 'TELEFONO');
      $sheet->setCellValue('J1', 'INDICACIONES');
      $sheet->setCellValue('K1', 'INICIO/RENOV. TRAT.');
      $sheet->setCellValue('L1', 'CONFIRMADO HASTA');
      $sheet->setCellValue('M1', 'DURACION (DIAS)');
      $sheet->setCellValue('N1', 'OBSERVACIONES');
      $sheet->setCellValue('O1', 'ALTA');
      $sheet->setCellValue('P1', 'EXITUS');
      $sheet->setCellValue('Q1', 'ESTADO SOLICITUD');


      $sheet->fromArray($array_tratamientos, null , "A2");
            
      $sheet->getStyle('A1:V1')->getFont()->setBold(true);
  
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment;filename="' . $filename . '"');
      header('Cache-Control: max-age=0');
      // If you're serving to IE 9, then the following may be needed
      //header('Cache-Control: max-age=1');
  
      // If you're serving to IE over SSL, then the following may be needed
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0
  
      $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
      $writer->save('php://output');
      exit;
    
    }


    /* Retorna un array de tratamientos que cumplen los filtros indicados
     * usada internamente en el controlador
     * @param array $filtros
     * @return array
    */
    private function getTratamientos(array $filtros)
    {

      $tratamientoModel = new TratamientoModel();
      $arrayWhere = [];
      $arrayLike = [];
      $arrayDates = [];
      $arrayBetweenDates = [];
      $filtroEstado = null;
      $muestraEliminados = null;
      $filtrePerId = false;
      $arrayServeis = null;
      
      if(isset($filtros['tratamiento_id']) && !empty($filtros['tratamiento_id']) ){
        $filtrePerId = true;
        $tratamientos[] = $tratamientoModel->findTratamientoCompleto($filtros['tratamiento_id']);
        if (empty($tratamientos[0])) return array();
        
      }else{

        if(isset($filtros['cip']) && !empty($filtros['cip']) ){
            $arrayWhere['cip'] = trim($filtros['cip']);
        }

        if(isset($filtros['nhc']) && !empty($filtros['nhc']) ){
          $arrayWhere['nhc'] = trim($filtros['nhc']);
        }

        if(isset($filtros['usuario']) && !empty($filtros['usuario']) ){
          $arrayWhere['usuario_codigo_medico'] = $filtros['usuario'];
        }

        if(isset($filtros['servicio_codigo']) && is_array($filtros['servicio_codigo']) && count($filtros['servicio_codigo']) > 0){
          $arrayServeis = $filtros['servicio_codigo'];
        }
        
        if(isset($filtros['nombre_completo']) && !empty($filtros['nombre_completo']) ){
          $arrayLike['nombre_paciente'] = str_replace(' ','%',$filtros['nombre_completo']);
          $arrayLike['apellidos_paciente'] = str_replace(' ','%',$filtros['nombre_completo']);
        }

        // comprobar si se filtra por intervalo  de fechas confirmado_hasta
        if(isset($filtros['confirmado_hasta_ini']) && $filtros['confirmado_hasta_ini']!=''){
          $arrayBetweenDates['confirmado_hasta >='] = $filtros['confirmado_hasta_ini'];
        }
        if(isset($filtros['confirmado_hasta_fi']) && $filtros['confirmado_hasta_fi']!=''){
          $arrayBetweenDates['confirmado_hasta <='] = $filtros['confirmado_hasta_fi'];
        }

        // comprobar si se filtra por intervalo de fechas ingreso
        if(isset($filtros['fecha_ingreso_ini']) && $filtros['fecha_ingreso_ini']!=''){
          $arrayBetweenDates['fecha_ingreso >='] = $filtros['fecha_ingreso_ini'];
        }
        if(isset($filtros['fecha_ingreso_fi']) && $filtros['fecha_ingreso_fi']!=''){
          $arrayBetweenDates['fecha_ingreso <='] = $filtros['fecha_ingreso_fi'];
        }

        // comprobar si se filtra por intervalo de fechas alta
        if(isset($filtros['fecha_alta_ini']) && $filtros['fecha_alta_ini']!=''){
          $arrayBetweenDates['fecha_alta >='] = $filtros['fecha_alta_ini'];
        }
        if(isset($filtros['fecha_alta_fi']) && $filtros['fecha_alta_fi']!=''){
          $arrayBetweenDates['fecha_alta <='] = $filtros['fecha_alta_fi'];
        }

        // comprobar si se filtra por intervalo de fechas de exitus
        if(isset($filtros['fecha_exitus_ini']) && $filtros['fecha_exitus_ini']!=''){
          $arrayBetweenDates['fecha_exitus >='] = $filtros['fecha_exitus_ini'];
        }
        if(isset($filtros['fecha_exitus_fi']) && $filtros['fecha_exitus_fi']!=''){
          $arrayBetweenDates['fecha_exitus <='] = $filtros['fecha_exitus_fi'];
        }

        /* Zona de filtros por defecto
        *
        * Si no se marcan tratamientos finalizados, se ocultan los tratamientos con fecha confirmado_hasta menor a la actual
        * Si no se marcan altas, se ocultan los tratamientos que tienen fecha de alta
        * Si no se marca exitus, se ocultan los tratamientos que tienen fecha de exitus
        * Si no se filtra por estado, se ocultan los tratamientos que tienen el estado eliminado
        * Si no se marcan tratamientos sin prescripciones, se ocultan los tratamientos que no tienen prescripciones
        * Si no se marcan prescripciones finalizadas, se ocultan las prescripciones finalizadas
        */

        // oculta tratamientos caducados (confirmado_hasta) si no se ha marcado el filtro
        if(!isset($filtros['tratamientos']) || $filtros['tratamientos']!=1){
            if (count($arrayBetweenDates) == 0 || !array_filter(array_keys($arrayBetweenDates), fn($key) => strpos($key, 'confirmado') !== false)) {
              array_push($arrayDates, 'confirmado_hasta');
            }
        }

        // oculta las altas si no se ha marcado el filtro
        if(!isset($filtros['altas']) || $filtros['altas']!=1){
          if (count($arrayBetweenDates) == 0 || !array_filter(array_keys($arrayBetweenDates), fn($key) => strpos($key, 'alta') !== false)) {
            array_push($arrayDates, 'fecha_alta');
          }
        }

        // oculta los exitus si no se ha marcado el filtro
        if(!isset($filtros['exitus']) || $filtros['exitus']!=1){
          if (count($arrayBetweenDates) == 0 || !array_filter(array_keys($arrayBetweenDates), fn($key) => strpos($key, 'exitus') !== false)) {
            array_push($arrayDates, 'fecha_exitus');
          }
        }

        //Filtros específicos que se obtienen de los datos de seguimientos o prescripciones
        if(isset($filtros['estado_id']) && is_array($filtros['estado_id'])){
            $filtroEstado = $filtros['estado_id'];
        }
        if(isset($filtros['eliminados']) && $filtros['eliminados']==1){
          $muestraEliminados = 1;
        } 

        $tratamientos = $tratamientoModel->findAllWithFilters($arrayWhere, $arrayLike, $arrayDates, $arrayBetweenDates, $arrayServeis);

      }     
 
      // asignar datos extra a cada tratamiento
      foreach($tratamientos as $tratamientoKey => $tratamiento) {
       
        $tratamiento->duracion = $this->duracionTratamiento($tratamiento);
        
        // Recuperar datos de seguimentos
        $seguimientoModel = new SeguimientoModel();
        
        $seguimientos = $seguimientoModel->getNumSeguimientosTratamiento($tratamiento->id);
        $tratamientos[$tratamientoKey]->seguimientos_totales = $seguimientos;
      
        if (!$filtrePerId){
          //aplicar filtros específicos si no se trata de un tratamiento concreto
    
          //estado de ultimo seguimiento
          if($filtroEstado != null){
            if(!isset($tratamiento->estado_id) || !in_array($tratamiento->estado_id, $filtroEstado)){
              unset($tratamientos[$tratamientoKey]);
            }
          }else{
            if($muestraEliminados != 1 && $tratamiento->estado_id == self::ESTADO_ELIMINADO){
              unset($tratamientos[$tratamientoKey]);
            }
          }

        }
        
      }
      
      return $tratamientos;

    }
    

  /* retorna la duración en días del tratamiento desde la última confirmación, si no desde la fecha de ingreso inicial
   * @param TratamientoEntity $tratamiento
   * @return int|null
  */
  private function duracionTratamiento($tratamiento)
  {

    if($tratamiento->confirmado_hasta){
      $dataFi = Carbon::parse($tratamiento->confirmado_hasta);
      if($tratamiento->fecha_cambio_confirmado && $tratamiento->fecha_ingreso <= $tratamiento->fecha_cambio_confirmado && $tratamiento->confirmado_hasta >= $tratamiento->fecha_cambio_confirmado ){
        $dataInici = Carbon::parse($tratamiento->fecha_cambio_confirmado); 
      }else{
        $dataInici = Carbon::parse($tratamiento->fecha_ingreso);
      }
      return $dataInici->diffInDays($dataFi);
    }
    return null;
  }
   
}

