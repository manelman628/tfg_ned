<?php

namespace App\Controllers;
use Exception;
use App\Controllers\BaseController;

use App\Models\Seguimiento as SeguimientoModel;
use App\Models\Tratamiento as TratamientoModel;
use App\Models\Aviso as AvisoModel;
use App\Models\Solicitud as SolicitudModel;
use App\Models\TipoSeguimiento as TipoSeguimientoModel;
use App\Entities\Seguimiento as SeguimientoEntity;
use App\Entities\Aviso as AvisoEntity;
use App\Entities\Solicitud as SolicitudEntity;
use Carbon\Carbon;

class Seguimiento extends BaseController
{
 
  //crides AJAX
  public function getSeguimiento()
  {
     
    $input = $this->request->getPost();

    $seguimientoModel = new SeguimientoModel();
    $seguimiento = $seguimientoModel->find($input['id']);

    return $this->response->setJSON($seguimiento);
  }

  public function getSeguimientosTratamiento()
  {
     
    $input = $this->request->getPost();

    $seguimientoModel = new SeguimientoModel();
    $seguimientos = $seguimientoModel->getSeguimientosTratamiento($input['tratamiento_id']);

    $solicitudModel = new SolicitudModel();
    $solicitud = $solicitudModel->findByTratamiento($input['tratamiento_id']);
    $tipos = [];
    $tipos = $this->getTiposSeguimiento($solicitud ? $solicitud->estado_id : 1);
    
    return $this->response->setJSON(['seguimientos' => $seguimientos, 'tipos' => $tipos]);
    
  }


  //Obté els tipus de seguimientos aplicables segons estat actual i rol
  private function getTiposSeguimiento($estadoSolicitud)
  {
      
    $gestorAcces = service('auth');
    $data['id_usuario'] = $gestorAcces->getIDUser();
    
    if(!$gestorAcces->can('crear seguimiento', env('app.appName'))){
        return false;
    }
    
    if($gestorAcces->isLogged(false)){
        $data['usuario'] = $gestorAcces->getNomiCognoms();
        $data['permissions'] = $this->permissions;
        $data['rols'] = $gestorAcces->getPerfils(env('app.appName'));
    }
    $tipoSeg = new TipoSeguimientoModel();
    return $tipoSeg->getTipoSeguimientobyRolEstado(isset($data['rols']) ?  $data['rols'][0] : '', $estadoSolicitud);

  }


  public function saveSeguimiento()
  {
    $resultat = ['error' => 0, 'txterror' => '', 'changed' => false, 'notified' => false];

    $input = $this->request->getPost();

    if(isset($input['tratamiento_id']) && $input['tratamiento_id'] > 0){
      
      $seguimientoModel = new seguimientoModel();
      $nuevo_seguimiento = new seguimientoEntity();
      $es_nuevo_seguimiento = true;

      if(isset($input['id']) && $input['id'] > 0){
        $nuevo_seguimiento = $seguimientoModel->find($input['id']);
        $es_nuevo_seguimiento = false;
      }else{
        $nuevo_seguimiento->tratamiento_id = $input['tratamiento_id'];
        $nuevo_seguimiento->setUsuarioCreacion();
      }

      $nuevo_seguimiento->observaciones = isset($input['observaciones']) ? $input['observaciones'] : 'Eliminació' ;
      $nuevo_seguimiento->tipo_seguimiento_id = $input['tipo_seguimiento_id'];
      if(isset($input['equipo_administracion_id']) && $input['equipo_administracion_id'] > 0){
        $nuevo_seguimiento->equipo_administracion_id = $input['equipo_administracion_id'];
      }else{
        $nuevo_seguimiento->equipo_administracion_id = null;
      }
      $nuevo_seguimiento->setFechaSeguimiento(isset($input['fecha_seguimiento']) ? $input['fecha_seguimiento'] : null);
      $nuevo_seguimiento->setUsuarioModificacion();
      
      if($seguimientoModel->save($nuevo_seguimiento)){
        if($es_nuevo_seguimiento){
          // si es un insert es dispara el canvi d'estat i l'avis
          $resultat['changed']  = $this->cambioEstadoTratamiento($input['tratamiento_id'], $input['tipo_seguimiento_id']);
          $resultat['notified'] = $this->avisoSeguimiento($input['tratamiento_id'], $input['tipo_seguimiento_id']);
        }
        $resultat['error'] = 0;
        $resultat['txterror'] = '';
      }else{
        $resultat['error'] = 1;
        $resultat['txterror'] = 'No se han podido guardar los datos de seguimiento';
      }

    }else{
      $resultat['error'] = 1;
      $resultat['txterror'] = 'No se puede guardar. No se encuentra Tratamiento associado';
    }

    return $this->response->setJSON($resultat);
  }


   /**
     * Funció cridada cada cop que es registra un seguimiento del tratamiento
     * segons el tipus de seguimiento es genera un canvi d'estat que s'actualitza a la taula de solicitudes
     * es pot obviar el tipus de seguimiento (passar un null) i passar com a 3r param. l'estat destí que volem assignar al tratamiento
     * retorna id de l'estat destí
     */
    public function cambioEstadoTratamiento($idTratamiento, $tipoSeguimientoId){

      $changed = false;
      $tipoSeguimientoModel = new TipoSeguimientoModel();
      $tipoSeg = $tipoSeguimientoModel->find($tipoSeguimientoId);
      if ($tipoSeg && $tipoSeg->estado_destino_id != null){
      
        $solicitudModel = new SolicitudModel;
        $soli = $solicitudModel->findByTratamiento($idTratamiento);
        if($soli == null){
          $soli = new SolicitudEntity();
          $soli->tratamiento_id = $idTratamiento;
          $soli->setUsuarioCreacion();
        }

        $soli->setEstado($tipoSeg->estado_destino_id);
        $soli->setUsuarioModificacion();
        try{
          $changed = $solicitudModel->save($soli);
          if ($changed){
            return $solicitudModel->getSolicitud($idTratamiento);
          }else{
            return false;
          }
        }catch(Exception $e){
          error_log($e->getMessage());
        }
      }

      return $changed;
    }

    /**
     * Funció cridada cada cop que es registra un seguimiento del tratamiento
     * segons el tipus de seguimiento es genera un canvi d'estat que s'actualitza a la taula de solicitudes
     * es pot obviar el tipus de seguimiento (passar un null) i passar com a 3r param. l'estat destí que volem assignar al tratamiento
     * retorna id de l'estat destí
     */
    public function avisoSeguimiento($idTratamiento, $tipoSeguId){
     
      try{
        $tipoSeguimientoModel = new TipoSeguimientoModel();
        $tipoSeguimiento = $tipoSeguimientoModel->find($tipoSeguId);
        if ($tipoSeguimiento && $tipoSeguimiento->roles_destino_avisos != null){
        
          //genera aviso segun tipo seguimiento
          $dataAvis = Carbon::now()->toDateString();
          $tipoAviso = 'seguimiento';
          $mensaje = str_replace('{id_tratamiento}', $idTratamiento, $tipoSeguimiento->mensaje_aviso);
          $mensaje = str_replace('{baseURL}', env('app.baseURL'), $mensaje);
          $titulo = $tipoSeguimiento->titulo_aviso;
          $rolsDesti = $tipoSeguimiento->roles_destino_avisos;
          $avisModel = new AvisoModel;
        
          //s'escriu obviant el rol admin, que pot veure tots els avisos
          $rolsDestiAvisos = str_replace('admin,', '', $rolsDesti);
          $rolsDestiAvisos = str_replace('admin', '', $rolsDestiAvisos);

          $avisTrobat = $avisModel->where('tratamiento_id', $idTratamiento)
                                  ->where('tipo_aviso', $tipoAviso)
                                  ->where('fecha_aviso',$dataAvis)
                                  ->where("roles_proceso like '%".$rolsDestiAvisos."%'")
                                  ->first();
          if ($avisTrobat){
            $avisTrobat->setMensaje($avisTrobat->mensaje . $titulo);
            $avisTrobat->procesado = 0;
            $avisModel->save($avisTrobat);
          }else{
            $nouAvis = new AvisoEntity();
            $nouAvis->tratamiento_id = $idTratamiento;
            $nouAvis->setFechaAviso();
            $nouAvis->mensaje = $titulo;
            $nouAvis->tipo_aviso = $tipoAviso;
            $nouAvis->roles_proceso = $rolsDestiAvisos;
            $avisModel->save($nouAvis);
          }

          //per cada rol destí es busquen els correus i s'envia l'avis
          helper('correo');
          $rols_desti = explode(',',$tipoSeguimiento->roles_destino_avisos) ;
          
          if(count($rols_desti) > 0){
            $correus = getCorreosAviso($rols_desti[0]);
            for ($i = 1; $i < count($rols_desti); $i++){
              $aCorreus = getCorreosAviso($rols_desti[$i]);
              $correus = array_merge($correus, $aCorreus);
            }    
          }

          if (count($correus) > 0){  
            enviaCorreo(['titulo' => $titulo, 'cuerpo' => $mensaje ], $correus);
          }
          
        }
        return true;

      }catch(Exception $e){
        error_log($e->getMessage());
      }

      return false;
      
    }

}