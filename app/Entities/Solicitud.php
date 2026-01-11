<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Carbon\Carbon;
use App\Enums\Estado as EstadoEnum;

class Solicitud extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    

    public function setUsuarioCreacion() : Solicitud
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_creacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_creacion'] = 'ned';
      }
      return $this;
    }

    public function setUsuarioModificacion() : Solicitud
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_modificacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_modificacion'] = 'ned';
      }
      return $this;
    }

    public function setUsuarioEliminacion() : Solicitud
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_eliminacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_eliminacion'] = 'ned';
      }
      return $this;
    }

    public function setEstado($estado =  null) : Solicitud
    {
      if($estado == null){
        $estado = EstadoEnum::PENDIENTE->value;
      }
      
      $this->attributes['estado_id'] = $estado;
      return $this;
    }

    public function getUsuarioCreacion()
    {
      $gestorAcces = service('auth');
      if(isset($this->attributes['usuario_creacion'])){
          $this->attributes['raw_usuario_creacion'] = $this->attributes['usuario_creacion'];
          list($nomUsuari, $correuUsuari) = $gestorAcces->getDadesUsuari($this->attributes['usuario_creacion']);
          if(isset($nomUsuari)){
            $this->attributes['usuario_creacion'] = $nomUsuari;
          }
      }
      return $this->attributes['usuario_creacion'];
      
    }

    public function setFechaCambioConfirmado($dataC = null)
    {
      if($dataC == null){
        $this->attributes['fecha_cambio_confirmado'] = Carbon::now()->toDateTimeString();
      }else{
        $this->attributes['fecha_cambio_confirmado'] = Carbon::parse($dataC)->toDateTimeString();
      }

      return $this;
    }
  
   
}
