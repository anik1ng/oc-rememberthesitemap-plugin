<?php namespace ANIKIN\RememberTheSitemap\Classes;

use Cms\Classes\Theme;
use October\Rain\Database\Scopes\NestedTreeScope;
use RainLab\Blog\Models\Post;
use RainLab\Blog\Models\Category;

class RainLabBlogItems
{
    use \ANIKIN\RememberTheSitemap\Classes\Traits\SitemapHelper;

    const TYPES = [
        'rainlab-blog-post',
        'rainlab-blog-posts',
        'rainlab-blog-category',
    ];

    public static function resolveItems(DefinitionItem $item, Theme $theme): ?array
    {
        $result = [];

        if ($item->type == 'rainlab-blog-post') {
            if (!$item->reference || !$item->cmsPage) {
                return null;
            }

            if (!$post = Post::find($item->reference)) {
                return null;
            }

            $result = array_merge($result, self::getLocalisedItem($item->cmsPage, $theme, $post));
        }

        if ($item->type == 'rainlab-blog-posts') {
            if (!$item->cmsPage) {
                return null;
            }

            $posts = Post::isPublished()->orderBy('title')->get();

            foreach ($posts as $post) {
                $result = array_merge($result, self::getLocalisedItem($item->cmsPage, $theme, $post));
            }
        }

        if ($item->type == 'rainlab-blog-category') {
            if (!$item->cmsPage) {
                return null;
            }

            $category = Category::find($item->reference);
            if (!$category) {
                return null;
            }

            $query = Post::isPublished();
            $categories = $category->getAllChildrenAndSelf()->lists('id');
            $query->whereHas('categories', function($q) use ($categories) {
                $q->withoutGlobalScope(NestedTreeScope::class)->whereIn('id', $categories);
            });
            $posts = $query->get();


            foreach ($posts as $post) {
                $result = array_merge($result, self::getLocalisedItem($item->cmsPage, $theme, $post));
            }
        }

        return ['items' => $result];
    }
}
