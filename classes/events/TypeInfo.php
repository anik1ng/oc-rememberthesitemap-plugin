<?php namespace ANIKIN\RememberTheSitemap\Classes\Events;

use ANIKIN\RememberTheSitemap\Classes\OfflineMallItems;
use Event;
use System\Classes\PluginManager;
use RainLab\Blog\Models\Post;
use ANIKIN\RememberTheSitemap\Classes\RainLabStaticPagesItems;

class TypeInfo
{
    public function subscribe(): void
    {
        $pluginManager = PluginManager::instance();

        // RainLab Static Pages plugin:
        if ($pluginManager->hasPlugin('RainLab.Pages')) {
            Event::listen('anikin.rememberthesitemap.getTypeInfo', function ($type) {
                if (in_array($type, RainLabStaticPagesItems::TYPES)) {
                    return RainLabStaticPagesItems::getMenuTypeInfo($type);
                }
            });
        }

        // RainLab Blog plugin:
        if ($pluginManager->hasPlugin('RainLab.Blog')) {
            Event::listen('anikin.rememberthesitemap.getTypeInfo', function ($type) {
                $typeMappings = [
                    'rainlab-blog-post' => 'blog-post',
                    'rainlab-blog-posts' => 'all-blog-posts',
                    'rainlab-blog-category' => 'category-blog-posts',
                ];

                if (isset($typeMappings[$type])) {
                    return Post::getMenuTypeInfo($typeMappings[$type]);
                }
            });
        }

        // OFFLINE.Mall plugin:
        if ($pluginManager->hasPlugin('OFFLINE.Mall')) {
            Event::listen('anikin.rememberthesitemap.getTypeInfo', function ($type) {
                if (in_array($type, OfflineMallItems::TYPES)) {
                    return OfflineMallItems::getMenuTypeInfo($type);
                }
            });
        }
    }
}
