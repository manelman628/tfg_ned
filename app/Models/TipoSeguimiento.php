<?php

namespace App\Models;

use CodeIgniter\Model;

class TipoSeguimiento extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'tipos_seguimiento';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'App\Entities\TipoSeguimiento';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'descripcion',
        'rol',
        'estados_origen',
        'estado_destino_id',
        'roles_destino_avisos',
        'mensaje_aviso',
        'titulo_aviso',
        'editable'
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
     * OBTENER TIPOS DE SEGUIMIENTO POR ROL Y ESTADO
     * 
     * Retorna los tipos de seguimiento permitidos segun el rol que hace la petición y el estado de la propia solicitud que se pasa por parámetro.
     * 
     * @return array $seguiments
     */
    public function getTipoSeguimientobyRolEstado($rol,$estat)
    {
        $tipus = $this->select([
                '*'
            ])
            ->where("INSTR(rol,'". $rol ."') AND ( INSTR(estados_origen,'" . $estat . "') OR estados_origen IS NULL )")
            ->orderBy('descripcion','ASC')
            ->findAll();

        return $tipus;
    }

    

}
