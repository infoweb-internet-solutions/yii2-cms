CMS module for Yii 2
========================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

You can then install the application using the following command:

```
php composer.phar global require "fxp/composer-asset-plugin:1.0.0-beta4"
php composer.phar create-project --prefer-dist --stability=dev yiisoft/yii2-app-advanced advanced
```
  
Create a new development database and adjust the components['db'] configuration in environments/dev/common/config/main-local.php accordingly.

Create a new production database and adjust the components['db'] configuration in environments/prod/common/config/main-local.php accordingly.
  
Add the components['assetManager'] configuration in the same files:
  
```
'assetManager' => [
    'linkAssets' => true,
],
```
  
Also adjust the components['mailer'] configuration for both files.
  
Run command `init` to initialize the application with a specific environment.

add admin email in backend params,
add adminEmail and supportEmail in common params

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

@todo update:
Admin module
backend
```
'modules' => [
    'admin' => [
        'class' => 'mdm\admin\Module',
        ...
    ]
    ...
],
```

common:
```
'components' => [
	....
	'authManager' => [
	    'class' => 'yii\rbac\PhpManager', // or use 'yii\rbac\DbManager'
	]
],
```
    

Docs
-----
- [Installation i18n module](https://github.com/zelenin/yii2-i18n-module)
- [Installation user module](https://github.com/infoweb-internet-solutions/yii2-cms-user)
- [Installation settings module](https://github.com/infoweb-internet-solutions/yii2-cms-settings)
- [Installation pages module](https://github.com/infoweb-internet-solutions/yii2-cms-pages)
- [Installation partials module](https://github.com/infoweb-internet-solutions/yii2-cms-partials)
- [Installation seo module](https://github.com/infoweb-internet-solutions/yii2-cms-seo)
- [Installation menu module](https://github.com/infoweb-internet-solutions/yii2-cms-menu)
- [Installation alias module](https://github.com/infoweb-internet-solutions/yii2-cms-alias)
- [Installation analytics widget](https://github.com/infoweb-internet-solutions/yii2-cms-analytics)

Usage
-----

Once the extension is installed, simply modify your common application configuration as follows:

```php

use \kartik\datecontrol\Module;

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

Add htaccess files  
  
Root  

```
<IfModule mod_rewrite.c>
    Options +FollowSymlinks
    RewriteEngine On
</IfModule>

<IfModule mod_rewrite.c>
    # deal with admin first
    RewriteCond %{REQUEST_URI} ^/(admin)
    RewriteRule ^admin/assets/(.*)$ backend/web/assets/$1 [L]
    RewriteRule ^admin/css/(.*)$ backend/web/css/$1 [L]
    RewriteRule ^admin/js/(.*)$ backend/web/js/$1 [L]

    RewriteCond %{REQUEST_URI} !^/backend/web/(assets|css)/
    RewriteCond %{REQUEST_URI} ^/(admin)
    RewriteRule ^.*$ backend/web/index.php [L]

    RewriteCond %{REQUEST_URI} ^/(assets|css)
    RewriteRule ^assets/(.*)$ frontend/web/assets/$1 [L]
    RewriteRule ^css/(.*)$ frontend/web/css/$1 [L]
    RewriteRule ^js/(.*)$ frontend/web/js/$1 [L]

    RewriteCond %{REQUEST_URI} !^/(frontend|backend)/web/(assets|css)/
    RewriteCond %{REQUEST_URI} !index.php
	RewriteCond %{REQUEST_URI} !^/preview
    RewriteCond %{REQUEST_FILENAME} !-f [OR]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^.*$ frontend/web/index.php
</IfModule>
```
  
backend/web/  
  
```
RewriteEngine on

# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# otherwise forward it to index.php
RewriteRule . index.php

Options FollowSymLinks
```

frontend/web/

```
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)/$ http://%{HTTP_HOST}/$1 [R=301,L] # Remove trailing slash

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php
```
