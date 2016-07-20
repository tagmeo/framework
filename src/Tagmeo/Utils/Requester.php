<?php

namespace Tagmeo\Utils;

use Tagmeo\Utils\Utils;
use Tagmeo\Utils\Converter;

class Requester
{
    public static function get($url, $params = [], $decode = true)
    {
        $ch = curl_init();

        $url .= Converter::convertArrayToQueryString(Utils::ensureArray($params));

        return self::request($ch, $url, $decode);
    }

    public static function post($url, $postFields = [], $decode = true)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, Utils::ensureArray($postFields));

        return self::request($ch, $url, $decode);
    }

    protected static function request($ch, $url, $decode = true)
    {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($ch);

        curl_close($ch);

        if ($decode) {
            return json_decode($json);
        }

        return $json;
    }
}
