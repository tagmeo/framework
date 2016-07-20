<?php

namespace Tagmeo\Theme;

use Tagmeo\Foundation\Application;

class Assets
{
    private $elixir;

    public function __construct()
    {
        $this->elixir = $this->getJson(Application::basePath('elixir.json'));

        add_action('wp_enqueue_scripts', function () {
            foreach ($this->elixir as $key => $value) {
                if ($key === 'assets') {
                    foreach ($this->elixir->$key as $handle => $asset) {
                        $asset->extension = $this->getExtension($asset->file);

                        if (preg_match('/^(\.\/|\.\.\/)(.*)$/', $asset->file, $part)) {
                            $asset->file = home_url($part[2]);
                        } elseif (preg_match('/^(\/\/|https?:\/\/)(.*)$/', $asset->file, $part)) {
                            $asset->file = '//'.$part[2];
                        } else {
                            $asset->file = home_url($this->getVersionedFile($asset->extension.DIRECTORY_SEPARATOR.$asset->file));
                        }

                        if (!isset($asset->dependsOn)) {
                            $asset->dependsOn = [];
                        }

                        if (!isset($asset->version)) {
                            $asset->version = null;
                        }

                        if (!isset($asset->inFooter)) {
                            $asset->inFooter = true;
                        }

                        if (!isset($asset->media)) {
                            $asset->media = 'all';
                        }

                        switch ($asset->extension) {
                            case 'css':
                                wp_register_style($handle, $asset->file, $asset->dependsOn, $asset->version, $asset->media);
                                wp_enqueue_style($handle);
                                break;
                            case 'js':
                                wp_register_script($handle, $asset->file, $asset->dependsOn, $asset->version, $asset->inFooter);
                                wp_enqueue_script($handle);
                                break;
                        }
                    }
                }
            }
        }, 100);
    }

    private function getVersionedFile($file, $buildDirectory = 'assets')
    {
        static $manifest = null;

        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(Application::assetPath('rev-manifest.json')), true);
        }

        if (isset($manifest[$file])) {
            return DIRECTORY_SEPARATOR.$buildDirectory.DIRECTORY_SEPARATOR.$manifest[$file];
        }

        throw new \InvalidArgumentException('File '.$file.' not defined in asset manifest.');
    }

    private function getExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    private function getJson($file = null)
    {
        if (is_null($file)) {
            return;
        }

        $data = file_get_contents($file);
        $json = json_decode($data);

        return $json;
    }
}
