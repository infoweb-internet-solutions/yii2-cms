<?php

namespace infoweb\cms;

use Yii;
use \yii\helpers\Url;
use yii\bootstrap\BootstrapAsset;
use frontend\assets\FontAsset;

use yii\web\Session;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'infoweb\cms\controllers';

    /**
     * @var array   The items that are shown in the sidebar navigation
     */
    public $sideBarItems = [];
    
    /**
     * @var array   The cached stylesheets for the ckeditor
     */
    private $_ckEditorStylesheets = [];

    public function init()
    {
        parent::init();

        // Disable kartik\grid\GridView export functionality for all instances
        Yii::$container->set('kartik\grid\GridView', [
            'export' => false,
        ]);
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
                'active'    => (stripos(Yii::$app->request->url, $item['url']) !== false) ? true : false
            ];
        }

        return $items;
    }

    public function getCKEditorStylesheets()
    {
        // No cached version found
        if (!$this->_ckEditorStylesheets) {
            
            // Initialize moxiemanager session vars
            $this->initMoxiemanagerSession();
            
            // Get the bootstrap asset url
            $bootstrapAsset = BootstrapAsset::register(Yii::$app->view);

            // Add default css
            $css = [
                $bootstrapAsset->baseUrl . '/css/bootstrap.min.css',
                Yii::getAlias('@frontendUrl') . '/css/main.css',
                Yii::getAlias('@frontendUrl') . '/css/editor.css'
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
        $editorOptions = [
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
            ],
            'removeButtons' => 'Smiley,Iframe,Templates,Outdent,Indent,Flash,Table,SpecialChar,PageBreak',
            'contentsCss' => $this->getCKEditorStylesheets(),
            'extraAllowedContent' => 'div(*)',
            'extraPlugins' => 'codemirror,moxiemanager',
            //'tinymce' => false,
            'enterMode' => 2,
        ];

        return $editorOptions;
    }

    public function initMoxiemanagerSession()
    {
        $session = new Session;
        $session->open();
        $session['moxieman-is-logged-in'] = true;
        $session['moxieman-user'] = 'infoweb';
        $session['moxieman-license-key'] = Yii::$app->params['moxiemanager']['license-key'];
        $session['moxieman-filesystem-rootpath'] = Yii::getAlias('@uploadsBasePath');
        $session['moxieman-filesystem-wwwroot'] = Yii::getAlias('@basePath');
        $session['moxieman-filesystem-urlprefix'] = Yii::getAlias('@baseUrl');    
    }
}