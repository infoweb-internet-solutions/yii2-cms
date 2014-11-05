<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\SideNav;

// The html template for the sidebar items
$sideBarItemTemplate = '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>';
?>
<div class="sidebar-nav navbar-collapse">    

    <?php echo SideNav::widget([
        'type' => SideNav::TYPE_DEFAULT,
        'heading' => false,
        'indItem' => false,
        'activateParents' => true,
        'iconPrefix' => 'fa fa-fw fa-',
        'linkTemplate' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
        'items' => [
            [
                'label' => 'Home',
                'url' => Yii::$app->homeUrl,                            
                'icon' => 'home',
                'template' => $sideBarItemTemplate,
                'visible' => true,
            ],
            // Content
            [
                'label' => Yii::t('app', 'Content'),
                'icon' => 'file-text',
                'items' => [
                    // Pages
                    [
                        'label' => Yii::t('infoweb/pages', 'Pages'),
                        'url'   => Url::toRoute('/pages/page'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showPagesModule')) ? true : false,
                    ],
                    // Partials
                    [
                        'label' => Yii::t('infoweb/partials', 'Partials'),
                        'url'   => Url::toRoute('/partials/page-partial'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showPagePartialsModule')) ? true : false,
                    ],
                    // Menu
                    [
                        'label' => Yii::t('infoweb/menu', 'Menu'),
                        'url'   => Url::toRoute('/menu/menu'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showMenuModule')) ? true : false,
                    ],                    
                    // SEO
                    [
                        'label' => Yii::t('infoweb/seo', 'Seo'),
                        'url'   => Url::toRoute('/seo/seo'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showSeoModule')) ? true : false,
                    ],
                    // Alias
                    [
                        'label' => Yii::t('infoweb/alias', 'Aliases'),
                        'url'   => Url::toRoute('/alias/alias'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showAliasModule')) ? true : false,
                    ],
                    // Translations
                    [
                        'label' => Yii::t('app', 'Translations'),
                        'url'   => Url::toRoute('/i18n'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showTranslationsModule')) ? true : false,
                    ],
                ],
                'visible' => (Yii::$app->user->can('showContentModule')) ? true : false,
            ],
            // Modules
            [
                'label' => Yii::t('app', 'Modules'),
                'icon' => 'tasks',
                'items' => Yii::$app->getModule('cms')->getSideBarItems('modules', $sideBarItemTemplate),
                'visible' => (Yii::$app->user->can('showModulesModule')) ? true : false,
            ],
            // Users
            [
                'label' => Yii::t('app', 'Users'),
                'url' => Url::toRoute('/user/admin/index'),
                'icon' => 'user',
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showUsersModule')) ? true : false,
            ],
            // Rights
            [
                'label' => Yii::t('app', 'Rights'),
                'icon' => 'lock',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Assigments'),
                        'url' => Url::toRoute('/admin/assignment'),
                        'template' => $sideBarItemTemplate,
                    ],
                    [
                        'label' => Yii::t('app', 'Roles'),
                        'url' => Url::toRoute('/admin/role'),
                        'template' => $sideBarItemTemplate,
                    ],
                    [
                        'label' => Yii::t('app', 'Permissions'),
                        'url' => Url::toRoute('/admin/permission'),
                        'template' => $sideBarItemTemplate,
                    ],
                    [
                        'label' => Yii::t('app', 'Routes'),
                        'url' => Url::toRoute('/admin/route'),
                        'template' => $sideBarItemTemplate,
                    ],
                    [
                        'label' => Yii::t('app', 'Rules'),
                        'url' => Url::toRoute('/admin/rule'),
                        'template' => $sideBarItemTemplate,
                    ],
                ],
                'visible' => (Yii::$app->user->can('showRightsModule')) ? true : false,
            ],
            // Settings
            [
                'label' => Yii::t('infoweb/settings', 'Settings'),
                'url' => Url::toRoute('/settings/setting'),
                'icon' => 'user',
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showSettingsModule')) ? true : false,
            ],            
        ]
    ]);
    ?>
    
</div>