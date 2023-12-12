<?php namespace ANIKIN\RememberTheSitemap\Classes\Traits;

use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;
use Site;
use Url;
use RainLab\Translate\Classes\Translator;

trait SitemapHelper
{
    /**
     * Build array links(pages) for sitemap
     */
    protected static function getLocalisedItem(string $page, Theme $theme, object $model): ?array
    {
        $cmsPage = CmsPage::loadCached($theme, $page);
        $sites = Site::listEnabled();
        $result = [];

        foreach ($sites as $site) {
            $pageUrl = self::getLocalisedPageUrl($cmsPage, $model, $site->locale);
            if (!$pageUrl) {
                return null;
            }

            $resultItem = [
                'url' => $pageUrl,
                'mtime' => $model->updated_at,
            ];

            foreach ($sites as $site) {
                $resultItem['alternate_locale_urls'][$site->locale] = self::getLocalisedPageUrl($cmsPage, $model, $site->locale);
            }

            $result[] = $resultItem;
        }

        return $result;
    }

    /**
     * Return localised page url
     */
    protected static function getLocalisedPageUrl(CmsPage $cmsPage, object $model, string $locale): string
    {
        $pageUrl = array_get($cmsPage->viewBag, "localeUrl.{$locale}", $cmsPage->url);

        $url = Translator::instance()->getPathInLocale($pageUrl, $locale);
        $url = (new \October\Rain\Router\Router)->urlFromPattern($url, ['slug' => $model->lang($locale)->slug]);

        return Url::to($url);
    }
}
