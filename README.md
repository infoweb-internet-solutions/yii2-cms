CMS module for Yii 2
========================

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

Add new folders in frontend/web/

```
uploads/img
uploads/files
```

and add .gitignore file in uploads folder

```
*
!.gitignore
```

Add this to your composer file if you want to add git folders

```
"config": {
    ...
	"preferred-install": "source"
},
```

Before using the module you also need to update the composer.json file of your project
with a reference to the custom repositories that are needed to override certain
vendor modules
```
...
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/infoweb-internet-solutions/yii2-i18n-module"
    },
    {
        "type": "vcs",
        "url": "https://github.com/infoweb-internet-solutions/yii2-ckeditor"
    },
]
...
```

To use the module, execute yii migration
```
yii migrate/up --migrationPath=@vendor/yiisoft/yii2/rbac/migrations
yii migrate/up --migrationPath=@vendor/infoweb-internet-solutions/yii2-cms/migrations
```

Docs
-----
- [Installation i18n module](https://github.com/zelenin/yii2-i18n-module)
- [Installation user module](https://github.com/infoweb-internet-solutions/yii2-cms-user)
- [Installation admin module](https://github.com/mdmsoft/yii2-admin)
- [Installation settings module](https://github.com/infoweb-internet-solutions/yii2-cms-settings)
- [Installation pages module](https://github.com/infoweb-internet-solutions/yii2-cms-pages)
- [Installation partials module](https://github.com/infoweb-internet-solutions/yii2-cms-partials)
- [Installation seo module](https://github.com/infoweb-internet-solutions/yii2-cms-seo)
- [Installation menu module](https://github.com/infoweb-internet-solutions/yii2-cms-menu)
- [Installation alias module](https://github.com/infoweb-internet-solutions/yii2-cms-alias)
- [Installation analytics widget](https://github.com/infoweb-internet-solutions/yii2-cms-analytics)
- [Installation sortable behaviour](https://github.com/infoweb-internet-solutions/yii2-sortable-behaviour)
- [Installation date control module](https://github.com/kartik-v/yii2-datecontrol)

Usage
-----

Once the extension is installed, simply modify your common application configuration as follows:

```php
return [
    ...
    'language' => 'nl',
    'timeZone' => 'Europe/Brussels',
    ...
    'components' => [
        ...        
        
        // Rewrite url's
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
        ],
        // Formatter
        'formatter' => [
            'dateFormat' => 'php:d-m-Y',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'EUR',
        ],
    ],
    ...
    'modules' => [
        ...
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                Module::FORMAT_DATE => 'php:d-m-Y',
                Module::FORMAT_TIME => 'php:H:m:s a',
                Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss a',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],

            // set your display timezone
            'displayTimezone' => 'Europe/Brussels',

            // set your timezone for date saved to db
            'saveTimezone' => 'Europe/Brussels',

            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    //'todayBtn' => true
                ]],
                Module::FORMAT_DATETIME => [], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],
            // Use custom convert action
            'convertAction' => '/cms/parse/convert-date-control'
        ]
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
    'bootstrap' => ['log','cms'],
    ...
    'modules' => [
        'cms' => [
            'class' => 'infoweb\cms\Module',
        ],
        'yii2images' => [
            'class' => 'rico\yii2images\Module',
            // @frontend/web/
            'imagesStorePath' => '@uploadsBasePath/img', //path to origin images
            'imagesCachePath' => '@uploadsBasePath/img/cache', //path to resized copies
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
            'placeHolderPath' => '@infoweb/cms/assets/img/avatar.png',
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

you backend parameters as follows:
```php
return [
    ...
    // Moximanager settings
    'moxiemanager'  => [
        'license-key'   => 'your-moxiemanager-key'
    ],
    ...
]
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

Add a couple of system aliases to your common/bootstrap.php file
```
...
// System aliases
Yii::setAlias('baseUrl', 'http://' . ((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '') . ((YII_ENV_DEV) ? '/directory-in-your-localhost' : ''));
Yii::setAlias('basePath', dirname(dirname(__DIR__)));
Yii::setAlias('uploadsBaseUrl', Yii::getAlias('@baseUrl') . '/frontend/web/uploads');
Yii::setAlias('uploadsBasePath', Yii::getAlias('@basePath') . '/frontend/web/uploads');
Yii::setAlias('frontendUrl', Yii::getAlias('@baseUrl') . '/frontend/web');
...
```

Import the translations and use category 'app':
```
yii i18n/import @infoweb/cms/messages
```

After that, import the translations of the custom i18n repository by using category 'zelenin/modules/i18n':
```
yii i18n/import @Zelenin/yii/modules/I18n/messages
```
