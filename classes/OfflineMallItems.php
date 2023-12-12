<?php namespace ANIKIN\RememberTheSitemap\Classes;

use Cms\Classes\Theme;
use OFFLINE\Mall\Models\Category;
use OFFLINE\Mall\Models\GeneralSettings;
use OFFLINE\Mall\Models\Product;

class OfflineMallItems
{
    use \ANIKIN\RememberTheSitemap\Classes\Traits\SitemapHelper;

    const TYPES = [
        'offline-mall-products',
        'offline-mall-categories',
    ];

    public static function getMenuTypeInfo(string $type): array
    {
        if ($type == 'offline-mall-products') {
            return [
                'dynamicItems' => true,
            ];
        }

        if ($type == 'offline-mall-categories') {
            return [
                'dynamicItems' => true,
            ];
        }

        return [];
    }

    public static function resolveItems(DefinitionItem $item, Theme $theme): ?array
    {
        $result = [];

        if ($item->type == 'offline-mall-products') {
            $page = GeneralSettings::get('product_page', 'product');
            $products = Product::published()
                ->where('inventory_management_method', 'single')
                ->get();

            foreach ($products as $product) {
                $result = array_merge($result, self::getLocalisedItem($page, $theme, $product));
            }
        }

        if ($item->type = 'offline-mall-categories') {
            $page = GeneralSettings::get('category_page', 'category');
            $categories = Category::orderBy('name')->get();

            foreach ($categories as $category) {
                $result = array_merge($result, self::getLocalisedItem($page, $theme, $category));
            }
        }

        return ['items' => $result];
    }
}
