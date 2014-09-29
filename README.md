CMS module for Yii 2
========================

Docs
-----
- [Installation admin module](https://github.com/mdmsoft/yii2-admin)
- [Installation user module](https://github.com/infoweb-internet-solutions/yii2-cms-user)
- [Installation migration utitlity](https://github.com/c006/yii2-migration-utility)


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

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'components' => [
        ...        
        
        // Rewrite url's
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
        ],
    ],
    ...
];
```