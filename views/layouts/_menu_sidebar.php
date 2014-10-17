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
        'iconPrefix' => 'fa fa-',
        'linkTemplate' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
        'items' => [
            [
                'label' => 'Home',
                'url' => Yii::$app->homeUrl,                            
                'icon' => 'home',
                'template' => $sideBarItemTemplate,
                'visible' => true,
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
                        'url' => Url::toRoute('/admin/assigment'),
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
            // Content
            [
                'label' => Yii::t('app', 'Content'),
                'icon' => 'file-text',
                'items' => [
                    // Menu
                    [
                        'label' => Yii::t('app', 'Menu'),
                        'url'   => Url::toRoute('/menu/menu'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showMenuModule')) ? true : false,
                    ],
                    // Pages
                    [
                        'label' => Yii::t('app', 'Pages'),
                        'url'   => Url::toRoute('/pages/page'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showPagesModule')) ? true : false,
                    ],
                    // Partials
                    [
                        'label' => Yii::t('app', 'Partials'),
                        'url'   => Url::toRoute('/partials/page-partial'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showPagePartialsModule')) ? true : false,
                    ],
                    // SEO
                    [
                        'label' => Yii::t('app', 'SEO'),
                        'url'   => Url::toRoute('/seo/seo'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showSeoModule')) ? true : false,
                    ],
                    // Alias
                    [
                        'label' => Yii::t('app', 'Alias'),
                        'url'   => Url::toRoute('/alias/alias'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showAliasModule')) ? true : false,
                    ],
                    // Translations
                    [
                        'label' => Yii::t('app', 'Translations'),
                        'url'   => Url::toRoute('/translations'),
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
                'items' => [
                    // Employees
                    [
                        'label' => Yii::t('app', 'Employees'),
                        'url'   => Url::toRoute('/employees/employee'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showEmployeesModule')) ? true : false,
                    ],
                    // Publications
                    [
                        'label' => Yii::t('app', 'Publications'),
                        'url'   => Url::toRoute('/publications/publication'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showPublicationsModule')) ? true : false,
                    ],
                    // Fields
                    [
                        'label' => Yii::t('app', 'Fields'),
                        'url'   => Url::toRoute('/fields/field'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showFieldsModule')) ? true : false,
                    ],
                    // News
                    [
                        'label' => Yii::t('app', 'News'),
                        'url'   => Url::toRoute('/news/news'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showNewsModule')) ? true : false,
                    ],
                ],
                'visible' => (Yii::$app->user->can('showModulesModule')) ? true : false,
            ],
        ]
    ]);
    ?>
    
</div>