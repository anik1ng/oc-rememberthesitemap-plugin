<?php namespace ANIKIN\RememberTheSitemap;

use Event;
use Backend;
use System\Classes\SettingsManager;
use ANIKIN\RememberTheSitemap\Classes\Events\ListTypes;
use ANIKIN\RememberTheSitemap\Classes\Events\ResolveItems;
use ANIKIN\RememberTheSitemap\Classes\Events\TypeInfo;

class Plugin extends \System\Classes\PluginBase
{
    public function pluginDetails(): array
    {
        return [
            'name' => 'anikin.rememberthesitemap::lang.plugin.name',
            'description' => 'anikin.rememberthesitemap::lang.plugin.description',
            'author' => 'Constantine Anikin',
            'website' => 'https://anikin.agency',
            'icon' => 'icon-sitemap'
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'anikin.rememberthesitemap.access_definitions' => [
                'tab' => 'anikin.rememberthesitemap::lang.plugin.name',
                'label' => 'anikin.rememberthesitemap::lang.plugin.permissions.access_definitions',
            ],
        ];
    }

    public function registerSettings(): array
    {
        return [
            'definitions' => [
                'label' => 'anikin.rememberthesitemap::lang.plugin.name',
                'description' => 'anikin.rememberthesitemap::lang.plugin.description',
                'icon' => 'icon-sitemap',
                'url' => Backend::url('anikin/rememberthesitemap/definitions'),
                'category' => SettingsManager::CATEGORY_CMS,
                'permissions' => ['anikin.rememberthesitemap.access_definitions'],
            ],
        ];
    }

    public function boot(): void
    {
        Event::subscribe(ListTypes::class);
        Event::subscribe(TypeInfo::class);
        Event::subscribe(ResolveItems::class);
    }
}
