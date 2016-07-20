<?php

namespace Tagmeo\Utils;

class Utils
{
    public function __construct()
    {
        add_filter('wp_trim_excerpt', function ($text = '', $count = 55, $more = true) {
            return self::trimExcerpt($text, $count, $more);
        }, 10, 3);
    }

    public static function trimExcerpt($text = '', $count = 55, $more = true)
    {
        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);

        $excerptLength = apply_filters('excerpt_length', $count);

        $excerptMore = $more ? apply_filters('excerpt_more', ' '.'[...]') : null;

        return wp_trim_words($text, $excerptLength, $excerptMore);
    }

    public static function getIpAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    public static function ensureArray($subject)
    {
        return is_array($subject) ? $subject : [];
    }

    public static function isAssoc($arr)
    {
        return array_keys(self::ensureArray($arr)) !== range(0, count($arr) - 1);
    }
}
