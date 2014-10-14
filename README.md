CMS module for Yii 2
========================

Docs
-----
- [Installation admin module](https://github.com/mdmsoft/yii2-admin)
- [Installation user module](https://github.com/infoweb-internet-solutions/yii2-cms-user)
- [Installation pages module](https://github.com/infoweb-internet-solutions/yii2-cms-pages)
- [Installation partials module](https://github.com/infoweb-internet-solutions/yii2-cms-partials)
- [Installation seo module](https://github.com/infoweb-internet-solutions/yii2-cms-seo)
- [Installation menu module](https://github.com/infoweb-internet-solutions/yii2-cms-menu)
- [Installation alias module](https://github.com/infoweb-internet-solutions/yii2-cms-alias)
- [Installation analytics widget](https://github.com/infoweb-internet-solutions/yii2-cms-analytics)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require infoweb-internet-solutions/yii2-cms "*"
```

or add

```
"infoweb-internet-solutions/yii2-cms": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply modify your common application configuration as follows:

```php
return [
    ...
    'language' => 'nl'
    ...
    'components' => [
        ...        
        
        // Rewrite url's
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
        ],
    ],
    ...
    'params' => [
        // Font Awesome Icon framework
        'icon-framework' => 'fa',  
    ]
];
```

your backend configuration as follows:

```php
return [
    ...
    'modules' => [
        'cms' => [
            'class' => 'infoweb\cms\Module',
        ],
    ],
    ...
    'components' => [
        ...
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views/layouts' => '@infoweb/cms/views/layouts',
                ],
            ],
        ],
    ],
    ...
];
```

and your common parameters as follows:

```php
return [
    ...
    // Enabled languages
    'languages' => [
        'nl'    => 'Nederlands',
        'fr'    => 'Français',
        'en'    => 'English',
    ],
    'companyName'   => 'Infoweb'
    ...
];
```


To use the module, execute yii migration
```
yii migrate/up --migrationPath=@vendor/yiisoft/yii2/rbac/migrations
yii migrate/up --migrationPath=@vendor/infoweb-internet-solutions/yii2-cms/migrations
```