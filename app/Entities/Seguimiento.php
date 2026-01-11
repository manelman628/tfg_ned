<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Carbon\Carbon;


class Seguimiento extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];
    

    public function setUsuarioCreacion() : Seguimiento
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_creacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_creacion'] = 'ned';
      }
      return $this;
    }

    public function setUsuarioModificacion() : Seguimiento
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_modificacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_modificacion'] = 'ned';
      }
      return $this;
    }

    public function setUsuarioEliminacion() : Seguimiento
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_eliminacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_eliminacion'] = 'ned';
      }
      return $this;
    }

    public function setFechaSeguimiento($fechaS = null) : Seguimiento
    {
      if($fechaS == null){
        $this->attributes['fecha_seguimiento'] = Carbon::now()->toDateString();
      }else{
        $this->attributes['fecha_seguimiento'] = Carbon::parse($fechaS)->toDateString();
      }

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
          }else{
            $this->attributes['usuario_creacion'] = $this->attributes['raw_usuario_creacion'];
          }
      }
      return $this->attributes['usuario_creacion'];
      
    }

    public function getFechaSeguimientoPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_seguimiento'], 'Y-m-d')){
         $this->attributes['fecha_seguimiento'] = Carbon::parse($this->attributes['fecha_seguimiento'])->format('d/m/Y');
       }
       return $this->attributes['fecha_seguimiento'];
    }

    public function getFechaModificacionPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_modificacion'], 'Y-m-d H:i:s')){
         $this->attributes['fecha_modificacion'] = Carbon::parse($this->attributes['fecha_modificacion'])->format('d/m/Y');
       }
       return $this->attributes['fecha_modificacion'];
    }

  
   
}
