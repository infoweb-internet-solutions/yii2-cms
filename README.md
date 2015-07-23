CMS module for Yii 2
========================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

You can then install the application using the following command:

```bash
php composer.phar global require "fxp/composer-asset-plugin:1.0.0-beta4"
php composer.phar create-project --prefer-dist --stability=dev yiisoft/yii2-app-advanced advanced
```

### Configure environments  
Create a new dev and production database and adjust the `components['db']` configuration in `environments/dev/common/config/main-local.php` and `environments/prod/common/config/main-local.php` accordingly.
  
Add the `components['assetManager']` configuration in the same files:
```php
'assetManager' => [
    'linkAssets' => true,
],
```
  
Also remove the `components['mailer']` configuration from both files because it will be added to `common/config/main.php`
 
### Update composer.json file  
Update the `config` section of `composer.json` if you want composer to download git folders for the packages
```json
"config": {
    ...
	"preferred-install": "source"
},
```

Add the `infoweb-internet-solutions/yii2-cms` and `fishvision/yii2-migrate` packages
```php
"require": [
    ...
    "infoweb-internet-solutions/yii2-cms": "*"
]
```

Add references to the custom repositories that are needed to override certain vendor packages
```php
...
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/infoweb-internet-solutions/yii2-i18n-module"
    },
    {
        "type": "vcs",
        "url": "https://github.com/infoweb-internet-solutions/yii2-ckeditor"
    }
]
...
```

Check if `"minimum-stability": "dev"` is set

After this run `composer update` to install the package

### Init environment  
Run command `init` to initialize the application with a specific environment.

Create folders in `frontend/web/`
```
uploads/img
uploads/files
```

and add `.gitignore` file in `uploads/`

```
*
!.gitignore
```
  
Adjust `adminEmail` in `backend/config/params.php`, `common/config/params.php`, `frontend/config/params.php` and `console/config/params.php`
Adjust `supportEmail` in `common/config/params.php`

Usage
-----

Once the extension is installed, simply modify `common/config/main.php` as follows:

```php

use \kartik\datecontrol\Module;

return [
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
		'authManager' => [
			'class' => 'yii\rbac\DbManager',
		],
		'i18n' => [
			'class' => Zelenin\yii\modules\I18n\components\I18N::className(),
			'languages' => ['nl']
		],
    ],
    ...
    'modules' => [
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                Module::FORMAT_DATE => 'php:d-m-Y',
                Module::FORMAT_TIME => 'php:H:i',
                Module::FORMAT_DATETIME => 'dd-MM-yyyy HH:mm:ss',
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
        ],
		'yii2images' => [
			'class' => 'rico\yii2images\Module',
			'imagesStorePath' => '@uploadsBasePath/img', //path to origin images
			'imagesCachePath' => '@uploadsBasePath/img/cache', //path to resized copies
			'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
			'placeHolderPath' => '@infoweb/cms/assets/img/transparent-placeholder.png',
		],
    ],
    ...
    'params' => [
        // Font Awesome Icon framework
        'icon-framework' => 'fa',  
    ],
	'controllerMap' => [
		'migrate' => [
			'class' => 'fishvision\migrate\controllers\MigrateController',
			'autoDiscover' => true,
			'migrationPaths' => [
				'@vendor'
			],
		],
	],
];
```
(dont forget to update the settings of the **mailer**, **log** and **i18n** components!)

`backend/config/main.php` as follows:

```php
return [
    ...
	'name' => 'My application',
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
		'email' => [
            'class' => 'infoweb\email\Module'
        ],
		'admin' => [
			'class' => 'mdm\admin\Module',
		],
		'i18n' => Zelenin\yii\modules\I18n\Module::className(),
    ],
    ...
    'components' => [
        ...
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views/layouts' => '@infoweb/cms/views/layouts',
                    '@dektrium/user/views' => '@infoweb/user/views'
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

`backend/config/params.php` as follows:
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

and `common/config/params.php` as follows:

```php
return [
    ...
    // Enabled languages
    'languages' => [
        'nl'    => 'Nederlands',
        'fr'    => 'Français',
        'en'    => 'English',
    ],
    'companyName'   => 'YourCompany'
    ...
];
```

and `frontend/config/main.php` as follows:

```php
return [
    ...
    'components' => [
		'user' => [
            'identityClass' => 'infoweb\user\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request'=>[
            'class' => 'common\components\Request',
            'web' => '/frontend/web',
            'csrfParam' => '_frontendCSRF',
        ],
        // Override the urlManager component
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<alias:[\d\w\-]+>' => 'site/index',
            ],
        ],
    ],
	...
];
```
  
  
Docs
-----
  
Follow all usage instructions  
Do not run composer, all modules are included in the infoweb-cms composer file and should be already installed  
Do not run any migrations and don't import messages, we'll do this later  
  
- [Installation user module](https://github.com/infoweb-internet-solutions/yii2-cms-user)
- [Installation settings module](https://github.com/infoweb-internet-solutions/yii2-cms-settings)
- [Installation pages module](https://github.com/infoweb-internet-solutions/yii2-cms-pages)
- [Installation partials module](https://github.com/infoweb-internet-solutions/yii2-cms-partials)
- [Installation seo module](https://github.com/infoweb-internet-solutions/yii2-cms-seo)
- [Installation menu module](https://github.com/infoweb-internet-solutions/yii2-cms-menu)
- [Installation alias module](https://github.com/infoweb-internet-solutions/yii2-cms-alias)
- [Installation analytics widget](https://github.com/infoweb-internet-solutions/yii2-cms-analytics)
  
  
   
Add a couple of system aliases to `common/config/bootstrap.php`
```php
...
// System aliases
Yii::setAlias('baseUrl', 'http://' . ((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '') . ((YII_ENV_DEV) ? '/directory-in-your-localhost' : '')); <-- Change your path
Yii::setAlias('basePath', dirname(dirname(__DIR__)));
Yii::setAlias('uploadsBaseUrl', Yii::getAlias('@baseUrl') . '/frontend/web/uploads');
Yii::setAlias('uploadsBasePath', Yii::getAlias('@basePath') . '/frontend/web/uploads');
Yii::setAlias('frontendUrl', Yii::getAlias('@baseUrl') . '/frontend/web');
...
```

Apply migrations with console commands. This will create tables needed for the application to work.
```bash
yii migrate/up
```

Import the translations
```bash
yii i18n/import @infoweb/cms/messages --interactive=0
yii i18n/import @Zelenin/yii/modules/I18n/messages --interactive=0
yii i18n/import @infoweb/settings/messages --interactive=0
yii i18n/import @infoweb/pages/messages --interactive=0
yii i18n/import @infoweb/partials/messages --interactive=0
yii i18n/import @infoweb/seo/messages --interactive=0
yii i18n/import @infoweb/alias/messages --interactive=0
yii i18n/import @infoweb/analytics/messages --interactive=0
yii i18n/import @infoweb/email/messages --interactive=0
```
Yii messages import fails sometimes, try importing without --interactive=0 and keep hitting return until it's done
```bash
yii i18n/import @yii/messages

```
  
Add htaccess files  
  
Root  

```apache
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

# Optional

# Upload limit
php_value upload_max_filesize 200M
php_value post_max_size 200M
php_value max_execution_time 6000
php_value max_input_time 2400
php_value max_file_uploads 100

<IfModule mod_php5.c>
php_value memory_limit 256M
</IfModule>
```
  
backend/web/  
  
```apache
RewriteEngine on

# if a directory or a file exists, use it directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# otherwise forward it to index.php
RewriteRule . index.php

Options FollowSymLinks
```

frontend/web/

```apache
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)/$ http://%{HTTP_HOST}/$1 [R=301,L] # Remove trailing slash

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . index.php
```

  
Add new file`frontend/web/css/editor.css`  
```css
body {
    padding: 15px;
}
```
  

  
  
Add new class in `common/components/Request.php`
  
```php
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
  
Add to gitignore
```
# Yii files
README.md
LICENSE.md
requirements.php
```

Add the following to `console/config/main.php` to enable creation of users via the cli:
```php
'modules' => [
    'user' => [
        'class' => 'dektrium\user\Module',
        'modelMap' => [
            'User' => 'infoweb\user\models\User',
        ],
    ],
],
```

You can then create the superadmin user with the following command:
```bash
yii user/create email@domain.com username password
```
  
Login @ `/admin` and enjoy!
(If you can't access this page, remove `ac access` in `backend/config/main.php`)
