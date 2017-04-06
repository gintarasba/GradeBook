<?php
namespace App\FancyLib;

class Month
{
    public static $monthsList = [
        'Sausis', 'Vasaris', 'Kovas',
        'Balandis', 'Gegužė', 'Birželis',
        'Liepa', 'Rugpjūtis', 'Rugsėjis',
        'Spalis', 'Lapkritis', 'Gruodis'
    ];

    public static function monthsList()
    {
        return self::$monthsList;
    }
}
