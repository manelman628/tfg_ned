<?php namespace App\Enums;

enum Seguimiento : int 
{
    case VALIDACION = 1;
    case VUELTA_PENDENTE = 2;
    case APROBAR = 3;
    case DATOS_FARM = 4;
    case DATOS_PRESC = 5;
    case PENDIENTE_RENOVAR = 6;
    case FINALIZAR = 7;
    case DENEGAR = 8;
    case ELIMINAR = 9;
    case REQU_PRESC = 10;
    case REQU_FARM = 11;
    case DETALLES_ADMIN = 12;
    case RECHAZAR = 13;
    

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}