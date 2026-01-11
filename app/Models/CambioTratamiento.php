<?php

namespace App\Models;

use CodeIgniter\Model;
use Carbon\Carbon;
use Exception;

class CambioTratamiento extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'cambios_tratamientos';
    protected $primaryKey       = 'id_cambios';
    protected $useAutoIncrement = false;
    protected $returnType       = 'App\Entities\CambiosTratamiento';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
      'fecha_ingreso',
      'fecha_alta',
      'suspendido',
      'confirmado_hasta',
      'pendiente_confirmar',
      'valido_hasta',
      'etc_id',
      'fecha_proceso',
      'NHC', 
      'cip', 
      'nif', 
      'nombre_paciente', 
      'apellidos_paciente',  
      'fecha_nacimiento',   
      'fecha_exitus',
      'direccion', 
      'poblacion', 
      'codigo_postal', 
      'provincia', 
      'nacionalidad', 
      'telefono', 
      'sexo',
      'usuario', 
      'usuario_login', 
      'usuario_grupo', 
      'usuario_servicio',
      'usuario_rol', 
      'usuario_num_colegiado', 
      'usuario_email', 
      'usuario_codigo_medico', 
      'usuario_activo', 
      'estado_registro',
      'control'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_modificacion';
    protected $deletedField  = 'fecha_eliminacion';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * OBTENER CAMBIOS EN DATOS DE TRATAMIENTO POR FECHA INDICADA
     * 
     * Retorna todos los registros de cambios del tratamiento, si no pasamos fecha tomamos la actual
     * 
     * @return array $canvis_tractaments
     */
    public function findByDataControl($dataControl = null)
    {
      if ($dataControl == null){
        $dataControl  = Carbon::now()->toDateString();
      }
        $canvis_tractaments = $this->select(["*"])->where("control like '".$dataControl."%'")->findAll();

        return $canvis_tractaments;
    }

    /* Borra los registros de cambios de trataminento por fecha indicada */
    public function deleteByDataControl($dataControl = null)
    {
      if ($dataControl == null){
        $dataControl  = Carbon::now()->toDateString();
      }
        $canvis_tractaments = $this->delete("control like '".$dataControl."%'");

        return $canvis_tractaments;
    }

}
