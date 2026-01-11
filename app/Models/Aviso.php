<?php

namespace App\Models;

use CodeIgniter\Model;

class Aviso extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'avisos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'App\Entities\Aviso';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'fecha_aviso',
        'tratamiento_id',
        'prescripcion_id',
        'mensaje',
        'procesado',
        'usuario_proceso',
        'fecha_proceso',
        'tipo_aviso',
        'roles_proceso',
    ];

    // Fechas
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_creacion';
    protected $updatedField  = 'fecha_modificacion';
    protected $deletedField  = 'fecha_eliminacion';

    // Validación
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

    /*  Método para obtener todos los avisos con filtros opcionales  */
    public function findAllWithFilters($arrayWhere = null, $rol = null)
    {
      
        $tratamientos = $this
                        ->select(["avisos.id, avisos.tratamiento_id, avisos.fecha_aviso, avisos.tipo_aviso,
                            GROUP_CONCAT(DISTINCT avisos.roles_proceso) as roles_proceso, tratamientos.usuario, CONCAT(CONCAT(tratamientos.apellidos_paciente,', '), tratamientos.nombre_paciente) AS paciente,
                            GROUP_CONCAT(DISTINCT IF(prescripcion_id IS NULL, mensaje, CONCAT(CONCAT('Prescripción ', CONCAT(avisos.prescripcion_id, ':')), mensaje)) SEPARATOR '###') AS group_mensaje"])
                        ->join('tratamientos', 'tratamientos.id = avisos.tratamiento_id','LEFT')
                        ->where($arrayWhere)
                        ->groupBy('fecha_aviso,tratamiento_id,tipo_aviso')
                        ->orderBy('fecha_aviso,tratamiento_id,prescripcion_id');
        if($rol){
            $tratamientos->where('roles_proceso LIKE "%'.$rol.'%"');
        }

          return $tratamientos->findAll();
        
    }


}
