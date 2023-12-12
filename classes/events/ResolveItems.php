<?php namespace ANIKIN\RememberTheSitemap\Classes\Events;

use ANIKIN\RememberTheSitemap\Classes\OfflineMallItems;
use ANIKIN\RememberTheSitemap\Classes\RainLabBlogItems;
use Event;
use System\Classes\PluginManager;
use ANIKIN\RememberTheSitemap\Classes\RainLabStaticPagesItems;

class ResolveItems
{
    public function subscribe(): void
    {
        $pluginManager = PluginManager::instance();

        // RainLab Static Pages plugin:
        if ($pluginManager->hasPlugin('RainLab.Pages')) {
            Event::listen('anikin.rememberthesitemap.resolveItem', function ($type, $item, $url, $theme) {
                if (in_array($type, RainLabStaticPagesItems::TYPES)) {
                    return RainLabStaticPagesItems::resolveItems($item, $theme);
                }
            });
        }

        // RainLab Blog plugin:
        if ($pluginManager->hasPlugin('RainLab.Blog')) {
            Event::listen('anikin.rememberthesitemap.resolveItem', function ($type, $item, $url, $theme) {
                if (in_array($type, RainLabBlogItems::TYPES)) {
                    return RainLabBlogItems::resolveItems($item, $theme);
                }
            });
        }

        // OFFLINE.Mall plugin:
        if ($pluginManager->hasPlugin('OFFLINE.Mall')) {
            Event::listen('anikin.rememberthesitemap.resolveItem', function ($type, $item, $url, $theme) {
                if (in_array($type, OfflineMallItems::TYPES)) {
                    return OfflineMallItems::resolveItems($item, $theme);
                }
            });
        }
    }
}
