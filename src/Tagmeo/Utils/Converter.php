<?php

namespace Tagmeo\Utils;

use Tagmeo\Utils\Utils;

class Converter
{
    public static function convertObjectToArray($d)
    {
        if (is_object($d)) {
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            return array_map(__FUNCTION__, $d);
        } else {
            return $d;
        }
    }

    public static function convertArrayToObject($d)
    {
        if (is_array($d)) {
            return (object) array_map(__FUNCTION__, $d);
        } else {
            return $d;
        }
    }

    public static function convertArrayToQueryString($params = [])
    {
        $qs = '';
        $params = Utils::ensureArray($params);

        $counter = 0;

        foreach ($params as $key => $value) {
            $qs .= ($counter === 0 ? '?' : '&');
            $qs .= $key.'='.$value;
            ++$counter;
        }

        return $qs;
    }
}
