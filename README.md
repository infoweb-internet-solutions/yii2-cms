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
  
Add to your composer file if you want to add git folders
```
"config": {
    ...
	"preferred-install": "source"
},
```
  
Run command `init` to initialize the application with a specific environment.
  
Adjust `adminEmail` in backend/config/params.php
Adjust 	`adminEmail` and `supportEmail` in common/config/params.php
Adjust `adminEmail` in console/config/params.php
  
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

Adjust backend/config/main.php
```
'modules' => [
    'admin' => [
        'class' => 'mdm\admin\Module',
    ],
    ...
],
```
  
Adjust common/config/main.php
```
'components' => [
	....
	'authManager' => [
	    'class' => 'yii\rbac\DbManager',
	]
],
```
  
Apply migrations with console commands. This will create tables needed for the application to work.
```
yii migrate
yii migrate/up --migrationPath=@vendor/yiisoft/yii2/rbac/migrations
yii migrate/up --migrationPath=@vendor/infoweb-internet-solutions/yii2-cms/migrations
```
  
Usage
-----

Once the extension is installed, simply modify your common application configuration as follows:

```php

use \kartik\datecontrol\Module;

return [
	'name' => 'My application',
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
        // Override views
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@infoweb/user/views'
                ],
            ],
        ],
      	'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@infoweb/cms/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'host',
                'username' => 'user',
                'password' => 'password',
                'port' => 'port'
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error'],
                    'categories' => ['yii\db\*'],
                    'message' => [
                       'from' => ['info@domain.com'],
                       'to' => ['developer@domain.com'],
                       'subject' => '[MySQL error @ domain.com]',
                    ],
                ],
            ],
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
(dont forget to update the settings of the **mailer** and **log** components!)

your backend configuration as follows:

```php
return [
    ...
    'bootstrap' => ['log','cms'],
    ...
    'modules' => [
    	...
        'cms' => [
            'class' => 'infoweb\cms\Module',
        ],
       	'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
  	'media' => [
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
	'request' => [
            'class' => 'common\components\Request',
            'web'=> '/backend/web',
            'adminUrl' => '/admin'
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
  
  
Docs
-----
  
Follow all usage instructions, but do not run composer, all modules are already added to the infoweb-cms composer file and should be installed already
  
- [Installation i18n module](https://github.com/zelenin/yii2-i18n-module)
- [Installation user module](https://github.com/infoweb-internet-solutions/yii2-cms-user)
- [Installation settings module](https://github.com/infoweb-internet-solutions/yii2-cms-settings)
- [Installation pages module](https://github.com/infoweb-internet-solutions/yii2-cms-pages)
- [Installation partials module](https://github.com/infoweb-internet-solutions/yii2-cms-partials)
- [Installation seo module](https://github.com/infoweb-internet-solutions/yii2-cms-seo)
- [Installation menu module](https://github.com/infoweb-internet-solutions/yii2-cms-menu)
- [Installation alias module](https://github.com/infoweb-internet-solutions/yii2-cms-alias)
- [Installation analytics widget](https://github.com/infoweb-internet-solutions/yii2-cms-analytics)
  
  
Images:
  
Add to common config modules:
  
'yii2images' => [
	'class' => 'rico\yii2images\Module',
	'imagesStorePath' => '@uploadsBasePath/img', //path to origin images
	'imagesCachePath' => '@uploadsBasePath/img/cache', //path to resized copies
	'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
	'placeHolderPath' => '@infoweb/cms/assets/img/placeholder.png',
],
  
`php yii migrate/up --migrationPath=@vendor/costa-rico/yii2-images/migrations`
  
  
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

@todo:
Create user
login to admin
  
add to frontend/web/css  
editor.css  
```
body {
    padding: 15px;
}
```
  
and empty main.css
  
  
@todo: Add frontend config settings (request, etc..)
Add to file in common components
Request.php
  
```
<?php
namespace common\components;

/**
 * This extension of the Request component can be used to replace parts of the 
 * requested url.
 * 
 * It has to be enabled in the 'components' area of the main configuration files
 * for the front- and backend:
 * 
 * eg: If you want to replace '/frontend/web' from the url's, put this in
 *     frontend/config.main.php in the 'components' section.
 * 
 *      'request'=>[
 *          'class' => 'common\components\Request',
 *          'web'=> '/frontend/web'
 *      ]
 * 
 *
 */
class Request extends \yii\web\Request
{
    public $web;
    public $adminUrl;

    /**
     * Takes the base url from the parent class and replaces the 'web' url that
     * you defined with an empty string:
     * 
     *  eg: the 'web' url is set to 'frontend/web'
     *      www.mydomain.com/frontend/web becomes www.mydomain.com/
     * 
     * @return  string
     */
    public function getBaseUrl()
    {
        return str_replace($this->web, '', parent::getBaseUrl()) . $this->adminUrl;
    }


    /**
     * This function ensures that www.mydomain.com/admin (without trailing slash) will not
     * throw a 404 error
     * 
     * @return  string
     */
    public function resolvePathInfo()
    {
        if ($this->getUrl() === $this->adminUrl) {
            return '';
        } else {
            return parent::resolvePathInfo();
        }
    }
}
```
