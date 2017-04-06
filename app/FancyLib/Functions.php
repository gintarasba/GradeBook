<?php
namespace App\FancyLib;

class Functions
{
    public static function pr($a)
    {
        echo '<pre>';
        print_r($a);
        echo '</pre>';
    }

    /**
     * Cleanup the value.
     *
     * @param $value
     * @param null $type
     *
     * @return mixed|string
     */
    public static function cleaner($value, $type = null)
    {
        switch ($type) {
            case 'num':
                $value = htmlspecialchars(addslashes($value), ENT_QUOTES);
                $value = preg_replace('/[^0-9]/i', '', $value);
                break;
            case 'numex':
                $value = htmlspecialchars(addslashes($value), ENT_QUOTES);
                $value = preg_replace("/[^0-9\,]/i", '', $value);
                break;
            case 'dec':
                $value = htmlspecialchars(addslashes($value), ENT_QUOTES);
                $value = preg_replace("/[^0-9\.]/", '', $value);
                break;
            case 'text':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array('<br/>', '<br/>'), $value);
                $value = preg_replace('/[^a-za-z]/iu', '', $value);
                break;
            case 'api':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array('<br/>', '<br/>'), $value);
                $value = preg_replace("/[^a-za-z0-9\-\_\.]/iu", '', $value);
                break;
            case 'filename':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array('<br/>', '<br/>'), $value);
                $value = preg_replace("/[^a-za-z\.\,\_\-\+\=\?\(\)\!0-9]/iu", '', $value);
                break;
            case 'json':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array('<br/>', '<br/>'), $value);
                $value = preg_replace("/[^a-za-z0-9\—\~\`\.\,\@\%\{\}\[\]\/\:\<\>\\\;\?\&\(\)\_\#\!\$\*\^\-\+\=\ \n\r]/iu", '', $value);
                break;
            default:
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array('<br/>', '<br/>'), $value);
                $value = preg_replace("/[^a-za-z0-9\—\~\`\.\,\@\%\[\]\/\:\<\>\\\;\?\&\(\)\_\#\!\$\*\^\-\+\=\ \n\rąčęėįšųūžĄČĘĖĮŠŲŪŽ]/iu", '', $value);
                break;
        }

        return $value;
    }

    // Truncates a string by given length
    public static function truncate($text, $length)
    {
        $length = abs((int)$length);
        if (strlen($text) > $length) {
            $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
        }
        return($text);
    }
}
