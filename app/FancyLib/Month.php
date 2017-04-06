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

    public static function getShortDay($dayNr, $strLength = 3)
    {
        switch ($dayNr) {
            case 0:
            return (object) [
                'title' => mb_substr('Sekmadienis', 0, $strLength),
                'textColor' => 'red'
            ];
            break;
            case 1:
            return (object) [
                'title' => mb_substr('Pirmadienis', 0, $strLength),
                'textColor' => 'black'
            ];
            break;
            case 2:
            return (object) [
                'title' => mb_substr('Antradienis', 0, $strLength),
                'textColor' => 'black'
            ];
            break;
            case 3:
            return (object) [
                'title' => mb_substr('Trečiadienis', 0, $strLength),
                'textColor' => 'black'
            ];
            break;
            case 4:
            return (object) [
                'title' => mb_substr('Ketvirtadienis', 0, $strLength),
                'textColor' => 'black'
            ];
            break;
            case 5:
            return (object) [
                'title' => mb_substr('Penktadienis', 0, $strLength),
                'textColor' => 'black'
            ];
            break;
            case 6:
            return (object) [
                'title' => mb_substr('Šeštadienis', 0, $strLength),
                'textColor' => 'red'
            ];
            break;

        }
    }
}
