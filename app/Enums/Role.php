<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case USER = 'user';

     public function canAssignTo(): array
    {
        return match ($this) {
            self::ADMIN => [self::MANAGER],
            self::MANAGER => [self::USER],
            self::USER => [],
        };
    }


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}