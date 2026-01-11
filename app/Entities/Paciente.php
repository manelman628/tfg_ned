<?php

namespace App\Entities;

use Carbon\Carbon;
use CodeIgniter\Entity\Entity;

class Paciente extends Entity
{
    public function setUsuarioCreacion() : Paciente
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_creacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_creacion'] = 'ned';
      }
      return $this;
    }

    public function setUsuarioModificacion() : Paciente
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_modificacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_modificacion'] = 'ned';
      }
      return $this;
    }

    public function setUsuarioEliminacion() : Paciente
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['usuario_eliminacion'] = $gestorAcces->getIDUser();
      }else{
        $this->attributes['usuario_eliminacion'] = 'ned';
      }
      return $this;
    }

    public function getDataCreacioPretty()
    {
      if(Carbon::hasFormat($this->attributes['fecha_creacion'], 'Y-m-d H:i:s')){
         $this->attributes['fecha_creacion'] = Carbon::parse($this->attributes['fecha_creacion'])->format('d/m/Y H:i:s');
       }
       return $this->attributes['fecha_creacion'];
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

    public function unsetDireccion() : Paciente
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['calle'] = null;
        $this->attributes['poblacion'] = null;
        $this->attributes['codigo_postal'] = null;
      }
      return $this;
    }

    public function unsetTelefono() : Paciente
    {
      $gestorAcces = service('auth');
      if($gestorAcces->isLogged(false)){
        $this->attributes['telefono'] = null;
      }
      return $this;
    }

}
