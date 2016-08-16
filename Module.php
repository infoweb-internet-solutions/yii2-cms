<?php

namespace infoweb\cms;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\BootstrapAsset;
use yii\web\Session;
use kartik\icons\FontAwesomeAsset;
use frontend\assets\FontAsset;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'infoweb\cms\controllers';

    /**
     * The php session name that is used for setting sessions
     * @var string
     */
    public $sessionName = 'PHPSESSID';

    /**
     * @var array   The items that are shown in the sidebar navigation
     */
    public $sideBarItems = [];

    /**
     * The sorting of images in modules and widgets that use the image behavior
     * @var string
     */
    public $imagesSorting = SORT_DESC;

    /**
     * The default configuration of the ckEditor
     * @var array
     */
    public $ckEditorOptions = [
        'height' => 300,
        'preset' => 'custom',
        'toolbarGroups' => [
            ['name' => 'clipboard', 'groups' => ['mode','undo', 'selection', 'clipboard', 'doctools']],
            ['name' => 'editing', 'groups' => ['tools']],
            ['name' => 'paragraph', 'groups' => ['templates', 'list', 'indent', 'align']],
            ['name' => 'insert'],
            ['name' => 'basicstyles', 'groups' => ['basicstyles', 'cleanup']],
            ['name' => 'colors'],
            ['name' => 'links'],
            ['name' => 'others'],
            ['name' => 'styles']
        ],
        'removeButtons' => 'Smiley,Iframe,Templates,Outdent,Indent,Flash,SpecialChar,PageBreak,Font,FontSize',
        'allowedContent' => true,
        'extraPlugins' => 'codemirror,moxiemanager,dialogui,dialog,lineutils,widget,entitylink',
        'enterMode' => 2,
        'stylesSet' => [],
    ];

    /**
     * @var array   The cached stylesheets for the ckeditor
     */
    private $_ckEditorStylesheets = [];

    public function init()
    {
        parent::init();

        // Gridview default settings
        $gridviewSettings = [
            'export' => false,
            'responsive' => true,
            'floatHeader' => false,
            //'floatHeaderOptions' => ['top' => 88],
            'hover' => true,
            'pjax' => true,
            'pjaxSettings' => [
                'options' => [
                    'id' => 'grid-pjax',
                ],
            ],
            'resizableColumns' => false,
        ];

        Yii::$container->set('kartik\grid\GridView', $gridviewSettings);
        Yii::$container->set('infoweb\sortable\SortableGridView', $gridviewSettings);

        // Fileinput default settings
        $fileInputSettings = [
            'pluginOptions' => [
                'showPreview' => true,
                'showCaption' => true,
                'showRemove' => true,
                'showUpload' => false,
                'browseLabel' => Yii::t('app', 'Browse'),
                'removeLabel' => Yii::t('app', 'Remove'),
                'removeTitle' => Yii::t('app', 'Remove selected files'),
                'uploadLabel' => Yii::t('app', 'Upload'),
                'uploadTitle' => Yii::t('app', 'Upload selected files'),
                'cancelLabel' => Yii::t('app', 'Cancel'),
            ],
        ];

        Yii::$container->set('kartik\widgets\FileInput', $fileInputSettings);

        // Initialize moxiemanager session vars
        $this->initMoxiemanagerSession();
    }

    /**
     * Returns the items for the sidebar, formatted for usage in the SideNav widget
     *
     * @param   string  $group      The group that contains the items
     * @param   string  $template   The template that is used to render the html of the items
     * @return  array
     */
    public function getSideBarItems($group = '', $template = '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>')
    {
        $items = [];

        if (!isset($this->sideBarItems[$group]))
            return $items;

        foreach ($this->sideBarItems[$group] as $item) {
            $items[] = [
                'label'     => Yii::t($item['i18nGroup'], $item['label']),
                'url'       => Url::toRoute($item['url']),
                'template'  => $template,
                'visible'   => (isset($item['authItem']) && Yii::$app->user->can($item['authItem'])) ? true : false,
                'active'    => (stripos(Yii::$app->request->url, (isset($item['activeUrl'])) ? $item['activeUrl'] : $item['url']) !== false) ? true : false
            ];
        }

        return $items;
    }

    public function getCKEditorStylesheets()
    {
        // No cached version found
        if (!$this->_ckEditorStylesheets) {

            // Get the asset url's
            $bootstrapAsset = BootstrapAsset::register(Yii::$app->view);
            $fontAwesome = FontAwesomeAsset::register(Yii::$app->view);

            // Add default css
            $css = [
                $bootstrapAsset->baseUrl . '/css/bootstrap.min.css',
                Yii::getAlias('@frontendUrl') . '/css/main.css',
                Yii::getAlias('@frontendUrl') . '/css/editor.css',
                $fontAwesome->baseUrl . '/' . $fontAwesome->css[0],
            ];

            // Add font assets if they exist
            if (class_exists('\frontend\assets\FontAsset')) {
                // Get the font asset
                $fontAsset = new FontAsset;

                // Add google fonts
                foreach ($fontAsset->css as $font) {
                    $css[] = $fontAsset->basePath . '/' . $font;
                }
            }

            $this->_ckEditorStylesheets = $css;
        }

        return $this->_ckEditorStylesheets;
    }

    public function getCKEditorOptions()
    {
        return ArrayHelper::merge($this->ckEditorOptions, ['contentsCss' => $this->getCKEditorStylesheets()]);
    }

    public function initMoxiemanagerSession()
    {
        $session = new Session;
        $session->open();
        $session->name = $this->sessionName;
        $session['moxieman-is-logged-in'] = true;
        $session['moxieman-user'] = 'infoweb';
        $session['moxieman-license-key'] = Yii::$app->params['moxiemanager']['license-key'];
        $session['moxieman-filesystem-rootpath'] = Yii::getAlias('@uploadsBasePath');
        $session['moxieman-filesystem-wwwroot'] = Yii::getAlias('@basePath');
        $session['moxieman-filesystem-urlprefix'] = YII_DEBUG ? Yii::getAlias('@baseUrl') : '/';
    }
}