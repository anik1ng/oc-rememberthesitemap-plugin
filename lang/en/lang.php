<?php

return [
    'plugin' => [
        'name' => 'Remember the Sitemap',
        'description' => 'Generate a sitemap.xml file for your website.',
        'permissions' => [
            'access_settings' => 'Access sitemap configuration settings',
            'access_definitions' => 'Access sitemap definitions page',
        ],
    ],
    'item' => [
        'location' => 'Location:',
        'priority' => 'Priority',
        'changefreq' => 'Change frequency',
        'always' => 'always',
        'hourly' => 'hourly',
        'daily' => 'daily',
        'weekly' => 'weekly',
        'monthly' => 'monthly',
        'yearly' => 'yearly',
        'never' => 'never',
        'editor_title' => 'Edit Sitemap Item',
        'type' => 'Type',
        'allow_nested_items' => 'Allow nested items',
        'allow_nested_items_comment' => 'Nested items could be generated dynamically by static page and some other item types',
        'url' => 'URL',
        'reference' => 'Reference',
        'title_required' => 'The title is required',
        'unknown_type' => 'Unknown item type',
        'unnamed' => 'Unnamed item',
        'add_item' => 'Add <u>I</u>tem',
        'new_item' => 'New item',
        'cms_page' => 'CMS Page',
        'cms_page_comment' => 'Select the page to use for the URL address.',
        'reference_required' => 'The item reference is required.',
        'url_required' => 'The URL is required',
        'cms_page_required' => 'Please select a CMS page',
        'page' => 'Page',
        'check' => 'Check',
        'definition' => 'Definition',
        'save_definition' => 'Saving Definition...',
        'load_indicator' => 'Emptying Definition...',
        'empty_confirm' => 'Empty this definition?'
    ],
    'definition' => [
        'theme_not_found' => 'Active theme not found',
        'not_found' => 'No sitemap definition was found. Try creating one first.'
    ],
    'types' => [
        // RainLab.Pages
        'rainlab-static-page' => 'RainLab.StaticPages Single Page',
        'rainlab-static-pages' => 'RainLab.StaticPages All Pages',
        // RainLab.Blog
        'rainlab-blog-post' => 'RainLab.Blog Single Post',
        'rainlab-blog-posts' => 'RainLab.Blog All Posts',
        'rainlab-blog-category' => 'RainLab.Blog Single Category Posts',
        // OFFLINE.Mall
        'offline-mall-products' => 'OFFLINE.Mall Products',
        'offline-mall-categories' => 'OFFLINE.Mall Categories',
    ],
];
