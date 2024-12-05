<?php
namespace App\Utils;

class Utils
{
    public static function map_operator($value): string
    {
        return match (gettype($value)) {
            'string' => 'LIKE',
            'array' => 'BETWEEN',
            default => '=',
        };
    }

    public static function map_value($value): mixed
    {
        return match (gettype($value)) {
            'string' => "%$value%",
            'array' => implode(' AND ', $value),
            default => $value,
        };
    }
}
