<?php

use Cms\Classes\Theme;
use ANIKIN\RememberTheSitemap\Models\Definition;

Route::get('sitemap.xml', function()
{
    if ( ! $theme = Theme::getActiveTheme()) {
        Log::info(trans('anikin.rememberthesitemap::lang.definition.theme_not_found'));
        abort(404);
    }

    if ( ! $definition = Definition::where('theme', $theme->getDirName())->first()) {
        Log::info(trans('anikin.rememberthesitemap::lang.definition.not_found'));
        abort(404);
    }

    return Response::make($definition->generateSitemap())
        ->header('Content-Type', 'application/xml');
});
