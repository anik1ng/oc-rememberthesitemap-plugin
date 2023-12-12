<?php namespace ANIKIN\RememberTheSitemap\Classes;

use October\Rain\Database\Collection;
use Site;
use Cms\Classes\Theme;
use RainLab\Pages\Classes\Page as StaticPage;
use RainLab\Pages\Classes\PageList;
use RainLab\Translate\Classes\Translator;

class RainLabStaticPagesItems
{
    const TYPES = [
        'rainlab-static-page',
        'rainlab-static-pages',
    ];

    public static function getMenuTypeInfo(string $type): ?array
    {
        if ($type == 'rainlab-static-pages') {
            return [
                'dynamicItems' => true
            ];
        }

        if ($type == 'rainlab-static-page') {
            return [
                'references'   => self::listStaticPageMenuOptions(),
                //'nesting'      => true,
                'dynamicItems' => true
            ];
        }

        return null;
    }

    /**
     * Returns a list of options for the Reference drop-down menu in the
     * menu item configuration form, when the Static Page item type is selected.
     * @return array Returns an array
     */
    protected static function listStaticPageMenuOptions(): array
    {
        $theme = Theme::getEditTheme();

        $pageList = new PageList($theme);
        $pageTree = $pageList->getPageTree(true);

        $iterator = function($pages) use (&$iterator) {
            $result = [];

            foreach ($pages as $pageInfo) {
                $pageName = $pageInfo->page->getViewBag()->property('title');
                $fileName = $pageInfo->page->getBaseFileName();

                if (!$pageInfo->subpages) {
                    $result[$fileName] = $pageName;
                }
                else {
                    $result[$fileName] = [
                        'title' => $pageName,
                        'items' => $iterator($pageInfo->subpages)
                    ];
                }
            }

            return $result;
        };

        return $iterator($pageTree);
    }

    public static function resolveItems(DefinitionItem $item, Theme $theme): ?array
    {
        $tree = StaticPage::buildMenuTree($theme);
        $sites = Site::listEnabled();

        if ($item->type == 'rainlab-static-page' && !isset($tree[$item->reference])) {
            return null;
        }

        $result = [];

        if ($item->type == 'rainlab-static-page') {
            $pageInfo = $tree[$item->reference];

            foreach($sites as $site) {
                $pageUrl = self::getPageUrl($theme, $item->reference, $pageInfo, $site->locale);

                $resultItem = [
                    'url' => $pageUrl,
                    'mtime' => $pageInfo['mtime'],
                ];

                $resultItem['alternate_locale_urls'] = self::getAlternateUrls($theme, $item->reference, $pageInfo, $sites);

                $result[] = $resultItem;
            }
        }

        if ($item->type == 'rainlab-static-pages') {
            $iterator = function ($items) use (&$iterator, &$tree, $sites, $theme) {
                $branch = [];

                foreach ($items as $itemName) {
                    if (!array_key_exists($itemName, $tree)) {
                        continue;
                    }

                    $itemInfo = $tree[$itemName];

                    foreach ($sites as $site) {
                        $pageUrl = self::getPageUrl($theme, $itemName, $itemInfo, $site->locale);
                        $branchItem = [
                            'url' => $pageUrl,
                            'mtime' => $itemInfo['mtime'],
                        ];

                        $branchItem['alternate_locale_urls'] = self::getAlternateUrls($theme, $itemName, $itemInfo, $sites);

                        if ($itemInfo['items']) {
                            $branchItem['items'] = $iterator($itemInfo['items']);
                        }

                        $branch[] = $branchItem;
                    }
                }

                return $branch;
            };

            $result = $iterator($tree['--root-pages--']);
        }

        return ['items' => $result];
    }

    protected static function getPageUrl(Theme $theme, string $itemName, array $itemInfo, string $locale): string
    {
        $staticPage = StaticPage::loadCached($theme, $itemName);

        return \Cms::url(Translator::instance()->getPathInLocale(array_get($staticPage->viewBag, "localeUrl.{$locale}", $itemInfo['url']), $locale));
    }

    protected static function getAlternateUrls(Theme $theme, string $itemName, array $itemInfo, Collection $sites): array
    {
        $alternateUrls = [];

        foreach ($sites as $site) {
            $pageUrl = self::getPageUrl($theme, $itemName, $itemInfo, $site->locale);
            $alternateUrls[$site->locale] = $pageUrl;
        }

        return $alternateUrls;
    }
}
