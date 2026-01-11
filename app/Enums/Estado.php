<?php namespace App\Enums;

enum Estado : int 
{
    case PENDIENTE = 1;
    case VALIDADA = 2;
    case APROBADA = 3;
    case DENEGADA = 4;
    case CADUCADA = 5;
    case FINALIZADA = 6;
    case ELIMINADA = 7;
    case RECHAZADA = 8;

    public static function notificarCambios(): array
    {
        // Estados que generan notificaciones a los usuarios
        return [self::VALIDADA->value, self::APROBADA->value];
    }
    

    public static function sePuedenFinalizar(): array
    {
        // Estados que se pueden marcar como finalizados automáticamente cuando el paciente es alta o exitus
        return [self::PENDIENTE->value, self::VALIDADA->value, self::APROBADA->value, self::CADUCADA->value];
    }

    public static function finDeCircuito(): array
    {
        // Solicitudes que han llegado al final de su circuito, no se tienen en cuenta para avisos que requieren acciones
        return [self::FINALIZADA->value, self::ELIMINADA->value, self::RECHAZADA->value, self::DENEGADA->value];
    }

    public function label(): string
    {
        return match($this) {
            self::PENDIENTE => 'Pendiente',
            self::VALIDADA   => 'Validada',
            self::APROBADA   => 'Aprobada',
            self::DENEGADA   => 'Denegada',
            self::CADUCADA   => 'Caducada',
            self::FINALIZADA   => 'Finalizada',
            self::ELIMINADA   => 'Eliminada',
            self::RECHAZADA   => 'Rechazada',
        };
    }

    public static function values(): array
    {
        return array_map(fn($e) => $e->value, self::cases());
    }
}