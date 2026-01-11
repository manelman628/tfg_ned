<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Solicitud as SolicitudEntity;
use Dom\Entity;
use Exception;

class Solicitud extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'solicitudes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'App\Entities\Solicitud';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tratamiento_id',
        'estado_id',
        'fecha_cambio_confirmado',
        'usuario_creacion',
        'usuario_modificacion',
        'usuario_eliminacion',
    ];

    // Dates
    protected $useTimestamps = true;
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
     * OBTENER DATOS DE TRATAMIENTO 
     * 
     * Retorna todos los registros de un tratamiento.
     * 
     * @return array $solicitudes
     */
    public function findByTratamiento($idTratamiento): SolicitudEntity|null
    {
        $seguimiento = $this->select([
                '*'
            ])
            ->where('tratamiento_id', $idTratamiento)->first();

        return $seguimiento;
    }

    /**
     * OBTENER DATOS DE SOLICITUD 
     * 
     * Retorna los datos de la solicitud asociada al tratamiento indicado.
     * 
     * @return $solicitude
     */
    public function getSolicitud($idTratamiento)
    {
        $solicitud = $this->select(['solicitudes.*', 'estados.descripcion as estado', 'estados.clase_bootstrap'])
            ->join('estados','estados.id = solicitudes.estado_id','left')
            ->where('tratamiento_id', $idTratamiento)
            ->first();

        return $solicitud;
    }
    
   
}
