<?php

namespace Tagmeo\Utils;

class Validator
{
    protected static $patterns = [
        'protocol' => '(?:(?:https?:)?)?',
        'domain' => '(?:\/\/[\w\d-\.]*)',
        'url_slug' => '(?:(?:\/[\w\d-]*)*(?:\.[\w\d]*)?\/?)?',
        'query_string' => '(?:\?[\w\d-]+=[\w\d-]+)?(?:&[\w\d-]+=[\w\d-]+)*',
        'email' => '(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))',
        'phone' => '(\d{7}||\d{10}||\d{11,15})',
        'dot_syntax' => '(?:\w*\.\w*)*',
    ];

    public static function getPatterns()
    {
        return self::$patterns;
    }

    public static function getPattern($pattern)
    {
        if (array_key_exists($pattern, self::$patterns)) {
            return self::$patterns[$pattern];
        }

        return '';
    }

    public static function addPattern($name, $regex)
    {
        if (!array_key_exists($name, self::$patterns)) {
            self::$patterns[$name] = $regex;
        }

        return self::$patterns[$name];
    }

    public static function validate($subject, $type)
    {
        $empty = (empty($subject) || !isset($subject));

        $response = false;

        switch ($type) {
            case 'string':
                $response = is_string($subject);
                break;
            case 'integer':
                $response = is_integer(intval($subject));
                break;
            case 'array':
                $response = is_array($subject);
                break;
            case 'object':
                $response = is_object($subject);
                break;
            case 'url':
            case 'base_url':
            case 'url_slug':
            case 'query_string':
            case 'protocol':
            case 'domain':
            case 'email':
                $response = (is_string($subject) && (self::match($type, $subject) ? true : false));
                break;
            case 'phone':
                preg_match_all('/\d/', $subject, $matches);
                $subject = implode('', $matches[0]);
                $response = (is_integer(intval($subject)) && (self::match($type, $subject) ? true : false));
                break;
            case 'class':
                $response = (class_exists($subject));
                break;
        }

        return !$empty && $response;
    }

    public static function match($pattern, $subject)
    {
        if (is_string($pattern) && !empty($pattern)) {
            $split = explode('.', $pattern);
            $regex = (self::$patterns[$pattern] ?: '');

            if (count($split) == 2 && is_array(self::$patterns[$split[0]]) && array_key_exists($split[1], self::$patterns[$split[0]])) {
                $regex = self::$patterns[$split[0]][$split[1]];
            }

            preg_match('/^'.$regex.'$/', $subject, $matches);

            return $matches;
        }

        return [];
    }
}

$base_url = Validator::getPattern('protocol').Validator::getPattern('domain').Validator::getPattern('url_slug');
$url = $base_url.Validator::getPattern('query_string');

Validator::addPattern('base_url', $base_url);
Validator::addPattern('url', $url);
