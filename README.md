# Remember The Sitemap

> Sitemap solution for October CMS v3.

Based on original [RainLab.Sitemap plugin](https://github.com/rainlab/sitemap-plugin).

## Viewing the sitemap

Once this plugin is installed and the sitemap has been configured. The sitemap can be viewed by accessing the file relative to the website base path. For example, if the website is hosted at https://octobercms.com it can be viewed by opening this URL:

```
https://octobercms.com/sitemap.xml
```

## Out a box:

* RainLab Static Pages
* RainLab Blog
* OFFLINE Mall

_Other popular plugins (and Tailor) coming soon..._

## Managing a sitemap definition

The sitemap is managed by selecting "Remember the Sitemap" from the Settings area of the back-end. There is a single sitemap definition for each theme and it will be created automatically.

A sitemap definition can contain multiple items and each item has a number of properties. There are common properties for all item types, and some properties depend on the item type. The common item properties are **Priority** and **Change frequency**. The Priority defines the priority of this item relative to other items in the sitemap. The Change frequency defines how frequently the page is likely to change.

---

## Adding to sitemap your items: 

##### Registering new sitemap definition item types (required):

```php
// Plugin.php — boot() method:
Event::listen('anikin.rememberthesitemap.listTypes', function () {
    return [
        'my-plugin-product' => 'My Plugin Products',
        'my-plugin-categories' => 'My Plugin Categories',
    ];
});
```

##### Resolving items for sitemap (required):
```php
// Plugin.php — boot() method:
Event::listen('anikin.rememberthesitemap.resolveItem', function ($type, $item, $url, $theme) {
    if ($type === 'my-plugin-products') {
        return ProductsItems::resolveItems($item, $url, $theme);
    }
});

/**
 * file: ProductsItems.php
 * 
 * Method getLocalisedItem(string $page, Theme $theme, object $model) from trait SitemapHelper 
 * return localized links for all sites automatically.
 * 
 * $page — cms page name.
 * $theme — variable from Event listener.
 * $model — your single model.
 */
class RainLabBlogItems
{
    use \ANIKIN\RememberTheSitemap\Classes\Traits\SitemapHelper;
    
    public static function resolveItems(DefinitionItem $item, Theme $theme): ?array
    {
        $result = [];
    
        if ($item->type == 'my-plugin-products') {
            $products = Product::published()->get();
    
            foreach ($products as $product) {
                $result = array_merge($result, self::getLocalisedItem($item->cmsPage, $theme, $product));
            }
        }
    
        return ['items' => $result];
    }
}
```

##### Registering new sitemap type info's (not required):
```php
// Plugin.php — boot() method:
Event::listen('anikin.rememberthesitemap.getTypeInfo', function($type) {
    if ($type == 'blog-post' || $type = 'all-blog-posts' || $type = 'category-blog-posts') {
        return BlogPostsItems::getMenuTypeInfo($type);
    }
});

/**
 * file: BlogPostsItems.php
 * 
 * Cut sample from RainLab.Blog
 * https://github.com/rainlab/blog-plugin/blob/master/models/Post.php#L506
 */
public static function getMenuTypeInfo(string $type): array
{
    if ($type == 'blog-post') {
        // This shows how to set type
        // with a select single post from Sitemap admin page
        $references = [];

        $posts = Post::orderBy('title')->get();
        foreach ($posts as $post) {
            $references[$post->id] = $post->title;
        }

        $result = [
            'references'   => $references,
            'nesting'      => false,
            'dynamicItems' => false
        ];
    }
    
    if ($type == 'all-blog-posts') {
        // This shows how to get type with list posts
        $result = [
            'dynamicItems' => true
        ];
    }
    
    if ($type == 'category-blog-posts') {
        // This shows hot to set type with categories list
        // with a select single category from Sitemap admin page
        $references = [];

        $categories = Category::orderBy('name')->get();
        foreach ($categories as $category) {
            $references[$category->id] = $category->name;
        }

        $result = [
            'references'   => $references,
            'dynamicItems' => true
        ];
    }
    
    if ($result) {
        // This shows how to set option on Sitemap admin page
        // with a select page where have plugin component
        $theme = Theme::getActiveTheme();

        $pages = CmsPage::listInTheme($theme, true);
        $cmsPages = [];

        foreach ($pages as $page) {
            if (!$page->hasComponent('blogPost')) {
                continue;
            }

            /*
             * Component must use a categoryPage filter with a routing parameter and post slug
             * eg: categoryPage = "{{ :somevalue }}", slug = "{{ :somevalue }}"
             */
            $properties = $page->getComponentProperties('blogPost');
            if (!isset($properties['categoryPage']) || !preg_match('/{{\s*:/', $properties['slug'])) {
                continue;
            }

            $cmsPages[] = $page;
        }

        $result['cmsPages'] = $cmsPages;
    }

    return $result;
}
```

