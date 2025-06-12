<?php

return [
    'supportedLocales' => [
        'ar' => ['name' => 'العربية', 'script' => 'Arab', 'native' => 'العربية', 'regional' => 'ar_SA'],
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
    ],
    'useAcceptLanguageHeader' => true,
    'hideDefaultLocaleInURL' => false,
    'localesOrder' => ['ar', 'en'],
    'localesMapping' => [],
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),
    'urlsIgnored' => ['/skipped'],
];
