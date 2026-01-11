<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class Seguimiento extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'seguimientos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'App\Entities\Seguimiento';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tratamiento_id',
        'tipo_seguimiento_id',
        'observaciones',
        'equipo_administracion_id',
        'fecha_seguimiento',
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

   
    /* OBTENER SEGUIMIENTOS DE TRATAMIENTO 
     * 
     * Retorna todos los seguimientos de un tratamiento con detalles adicionales.
     * 
     * @return array $seguimientos
     */

    public function getSeguimientosTratamiento($idTratamiento)
    {
        $seguimiento = $this->select(['seguimientos.id', 'seguimientos.tratamiento_id', 'seguimientos.tipo_seguimiento_id',
                                    'seguimientos.observaciones', 'tipos_seguimiento.descripcion as tipo_seguimiento', 
                                    'estados_destino.descripcion as estado_destino', 'tipos_seguimiento.editable',
                                    'equipos_administracion.bomba', 'equipos_administracion.lab', 'equipos_administracion.equipo',
                                    'seguimientos.fecha_seguimiento','seguimientos.usuario_creacion', 'seguimientos.usuario_modificacion', 'seguimientos.fecha_modificacion'])
            ->join('tipos_seguimiento','tipos_seguimiento.id = seguimientos.tipo_seguimiento_id')
            ->join('estados as estados_destino','estados_destino.id = tipos_seguimiento.estado_destino_id','left')
            ->join('equipos_administracion','equipos_administracion.id = seguimientos.equipo_administracion_id','left')
            ->where('tratamiento_id', $idTratamiento)
            ->orderBy('seguimientos.fecha_seguimiento DESC, seguimientos.fecha_modificacion DESC')
            ->findAll();

        return $seguimiento;
    }

    public function getUltimoSeguimiento($idTratamiento, $idTipusSeguimiento = null)
    {
        $seguimiento = $this->select(['seguimientos.id', 'seguimientos.tratamiento_id', 'seguimientos.tipo_seguimiento_id',
                                    'seguimientos.observaciones', 'tipos_seguimiento.descripcion as tipo_seguimiento', 
                                    'estados_destino.descripcion as estado_destino', 'tipos_seguimiento.editable',
                                    'equipos_administracion.bomba', 'equipos_administracion.lab', 'equipos_administracion.equipo',
                                    'seguimientos.usuario_creacion', 'seguimientos.usuario_modificacion',
                                    'seguimientos.fecha_seguimiento','seguimientos.usuario_creacion', 'seguimientos.usuario_modificacion', 'seguimientos.fecha_modificacion'])
            ->join('tipos_seguimiento','tipos_seguimiento.id = seguimientos.tipo_seguimiento_id')
            ->join('estados as estados_destino','estados_destino.id = tipos_seguimiento.estado_destino_id','left')
            ->join('equipos_administracion','equipos_administracion.id = seguimientos.equipo_administracion_id','left')
            ->where('tratamiento_id', $idTratamiento);

        if ($idTipusSeguimiento > 0)  $seguimiento->where('seguimientos.tipo_seguimiento_id', $idTipusSeguimiento);
        
        return $seguimiento->orderBy('seguimientos.fecha_seguimiento DESC')->first();
    }
    
    public function getNumSeguimientosTratamiento($idTratamiento)
    {
        $seguimientos = $this->select(['COUNT(*) as seguimientos_totales'])->where('tratamiento_id', $idTratamiento)->first();
        if ($seguimientos) {
            return $seguimientos->seguimientos_totales;
        } else {
            return 0;
        }
    }
   
}
