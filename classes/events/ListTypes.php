<?php namespace ANIKIN\RememberTheSitemap\Classes\Events;

use ANIKIN\RememberTheSitemap\Classes\OfflineMallItems;
use Event;
use System\Classes\PluginManager;
use ANIKIN\RememberTheSitemap\Classes\RainLabBlogItems;
use ANIKIN\RememberTheSitemap\Classes\RainLabStaticPagesItems;

class ListTypes
{
    public function subscribe(): void
    {
        $pluginManager = PluginManager::instance();

        // RainLab Static Pages plugin:
        if ($pluginManager->hasPlugin('RainLab.Pages')) {
            Event::listen('anikin.rememberthesitemap.listTypes', function () {
                return $this->getTypesWithTitle(RainLabStaticPagesItems::TYPES);
            });
        }

        // RainLab Blog plugin:
        if ($pluginManager->hasPlugin('RainLab.Blog')) {
            Event::listen('anikin.rememberthesitemap.listTypes', function () {
                return $this->getTypesWithTitle(RainLabBlogItems::TYPES);
            });
        }

        // OFFLINE.Mall plugin:
        if ($pluginManager->hasPlugin('OFFLINE.Mall')) {
            Event::listen('anikin.rememberthesitemap.listTypes', function () {
                return $this->getTypesWithTitle(OfflineMallItems::TYPES);
            });
        }
    }

    private function getTypesWithTitle(array $types): array
    {
        $result = [];
        foreach ($types as $type) {
            $result = array_merge($result, ["{$type}" => "anikin.rememberthesitemap::lang.types.{$type}"]);
        }

        return $result;
    }
}
