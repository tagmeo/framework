<?php

namespace Tagmeo\Console;

class Console
{
    public static function commandExists($cmd)
    {
        $whichWhere = (PHP_OS === 'WINNT') ? 'where' : 'which';

        $process = proc_open(
            $whichWhere.' '.$cmd,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w']
            ],
            $pipes
        );

        if ($process !== false) {
            $stdOut = stream_get_contents($pipes[1]);

            fclose($pipes[1]);
            fclose($pipes[2]);

            proc_close($process);

            return $stdOut !== '';
        }

        return false;
    }
}
