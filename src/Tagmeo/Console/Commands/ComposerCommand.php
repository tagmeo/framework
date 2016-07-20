<?php

namespace Tagmeo\Console\Commands;

use Composer\Script\Event;

class ComposerCommand
{
    public static function postCreate(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';
    }

    public static function postInstall(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';
    }

    public static function postUpdate(Event $event)
    {
        require_once $event->getComposer()->getConfig()->get('vendor-dir').'/autoload.php';
    }
}
