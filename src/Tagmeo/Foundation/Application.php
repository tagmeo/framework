<?php

namespace Tagmeo\Foundation;

class Application
{
    const VERSION = '0.0.1';

    public static $appDir = 'app';
    public static $assetDir = 'assets';
    public static $configDir = 'config';
    public static $mustUsePluginDir = 'mu-plugins';
    public static $pluginDir = 'plugins';
    public static $publicDir = 'public';
    public static $resourceDir = 'resources';
    public static $storageDir = 'storage';
    public static $themeDir = 'themes';
    public static $uploadDir = 'uploads';

    protected static $basePath;
    protected static $environmentPath;
    protected static $environmentFile = '.env';

    public function __construct($basePath)
    {
        self::setBasePath($basePath);
    }

    public static function version()
    {
        return static::VERSION;
    }

    public static function setBasePath($path)
    {
        self::$basePath = $path;
    }

    public static function basePath($path = '')
    {
        return self::$basePath.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public static function appPath($path = '')
    {
        return self::$basePath.DIRECTORY_SEPARATOR.self::$appDir.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public static function assetPath($path = '')
    {
        return self::publicPath(self::$assetDir.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    public static function configPath($path = '')
    {
        return self::$basePath.DIRECTORY_SEPARATOR.self::$configDir.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public static function environmentPath()
    {
        return self::$environmentPath ?: self::$basePath;
    }

    public static function environmentFile()
    {
        return self::environmentPath().DIRECTORY_SEPARATOR.self::$environmentFile;
    }

    public static function mustUsePluginPath($path = '')
    {
        return self::publicPath(self::$mustUsePluginDir.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    public static function pluginPath($path = '')
    {
        return self::publicPath(self::$pluginDir.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    public static function publicPath($path = '')
    {
        return self::$basePath.DIRECTORY_SEPARATOR.self::$publicDir.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public static function resourcePath($path = '')
    {
        return self::$basePath.DIRECTORY_SEPARATOR.self::$resourceDir.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public static function storagePath($path = '')
    {
        return self::$basePath.DIRECTORY_SEPARATOR.self::$storageDir.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    public static function themePath()
    {
        return get_stylesheet_directory();
    }

    public static function uploadPath($path = '')
    {
        return self::publicPath(self::$uploadDir.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    public static function runningInConsole()
    {
        return php_sapi_name() == 'cli';
    }
}
