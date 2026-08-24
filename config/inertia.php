<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Side Rendering (SSR)
    |--------------------------------------------------------------------------
    */
    'ssr' => [
        'enabled' => env('INERTIA_SSR_ENABLED', false),
        'url' => 'http://127.0.0.1:13714',
    ],

    /*
    |--------------------------------------------------------------------------
    | Page Paths & Extensions
    |--------------------------------------------------------------------------
    */
    'pages' => [
        'ensure_pages_exist' => false,
        'paths' => [
            resource_path('js/Pages'),
            resource_path('js/pages'),
        ],
        'extensions' => [
            'js',
            'jsx',
            'svelte',
            'ts',
            'tsx',
            'vue',
        ],
    ],

    'testing' => [
        'ensure_pages_exist' => false,
        'page_paths' => [
            resource_path('js/Pages'),
            resource_path('js/pages'),
        ],
    ],

];
