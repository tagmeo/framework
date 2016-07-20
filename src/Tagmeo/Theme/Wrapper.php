<?php

namespace Tagmeo\Theme;

class Wrapper
{
    public $slug;
    public $templates;

    public static $base;
    public static $mainTemplate;

    public function __construct($template = 'base.php')
    {
        $this->slug = basename($template, '.php');
        $this->templates = [$template];

        if (self::$base) {
            $str = substr($template, 0, -4);
            array_unshift($this->templates, sprintf($str.'-%s.php', self::$base));
        }

        add_filter('template_include', function ($main) {
            if (!is_string($main)) {
                return $main;
            }

            self::$mainTemplate = $main;
            self::$base = basename(self::$mainTemplate, '.php');

            if (self::$base === 'index') {
                self::$base = false;
            }

            return new self();
        }, 109, 1);
    }

    public function __toString()
    {
        $this->templates = apply_filters('tagmeo/wrap_'.$this->slug, $this->templates);

        return locate_template($this->templates);
    }

    public static function templatePath()
    {
        return self::$mainTemplate;
    }

    public static function sidebarPath()
    {
        return new self('views/sidebar.php');
    }
}
