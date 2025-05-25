<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pendiente  = 'Pendiente';
    case Completado = 'Completado';

    /**
     * Obtener todas las opciones como array para selects
     */
    public static function toArray(): array
    {
        return [
            self::Pendiente->value => 'Pendiente',
            self::Completado->value => 'Completado',
        ];
    }
}
