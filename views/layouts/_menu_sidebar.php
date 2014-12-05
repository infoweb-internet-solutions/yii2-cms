<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\SideNav;

// The html template for the sidebar items
$sideBarItemTemplate = '<a href="{url}" title="{label}">{icon}<span class="nav-label">{label}</span></a>';
?>
<div class="sidebar-nav navbar-collapse">
    
    <a class="navbar-brand" href="<?php echo Yii::$app->homeUrl; ?>">
        <?php echo Html::img($this->params['cmsAssets']->baseUrl.'/img/logo-infoweb.png', ['class' => 'brand-logo', 'alt' => 'brand-logo']); ?>
    </a>

    <?php echo SideNav::widget([
        'type' => SideNav::TYPE_DEFAULT,
        'heading' => false,
        'indItem' => false,
        'activateParents' => true,
        'iconPrefix' => 'fa fa-fw fa-',
        'linkTemplate' => '<a href="{url}" title="{label}">{icon}<span class="nav-label">{label}</span></a>',
        'items' => [
            [
                'label' => 'Home',
                'url' => Yii::$app->homeUrl,                            
                'icon' => 'home',
                'template' => $sideBarItemTemplate,
                'visible' => true,
                'active' => (Yii::$app->request->url == Yii::$app->homeUrl) ? true : false
            ],
            // Content
            [
                'label' => Yii::t('app', 'Website'),
                'icon' => 'file-text',
                'template' => '<a href="{url}" title="{label}" class="kv-toggle">{icon}<span class="nav-label">{label}</span></a>',
                'items' => [
                    // Pages
                    [
                        'label' => Yii::t('infoweb/pages', 'Pages'),
                        'url'   => Url::toRoute('/pages/page'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showPagesModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/pages/page') !== false) ? true : false 
                    ],
                    // Partials
                    [
                        'label' => Yii::t('infoweb/partials', 'Partials'),
                        'url'   => Url::toRoute('/partials/page-partial'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showPagePartialsModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/partials/page-partial') !== false) ? true : false
                    ],
                    // Menu
                    [
                        'label' => Yii::t('infoweb/menu', 'Menu\'s'),
                        'url'   => Url::toRoute('/menu/menu'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showMenuModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/menu/') !== false) ? true : false
                    ],
                    // Emails
                    [
                        'label' => Yii::t('infoweb/email', 'Emails'),
                        'url'   => Url::toRoute('/email/email'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showEmailModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/email/email') !== false) ? true : false
                    ],                    
                    // Alias
                    [
                        'label' => Yii::t('infoweb/alias', 'Aliases'),
                        'url'   => Url::toRoute('/alias/alias'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showAliasModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/alias/alias') !== false) ? true : false
                    ],                   
                    // Translations
                    [
                        'label' => Yii::t('app', 'Translations'),
                        'url'   => Url::toRoute('/translations'),
                        'template' => $sideBarItemTemplate,
                        'visible' => (Yii::$app->user->can('showTranslationsModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/translations') !== false) ? true : false
                    ],                    
                    // Modules
                    [
                        'label' => Yii::t('app', 'Modules'),
                        'template' => '<a href="{url}" title="{label}" class="kv-toggle toggle-level-2">{icon}<span class="nav-label">{label}</span></a>',
                        'items' => Yii::$app->getModule('cms')->getSideBarItems('modules', $sideBarItemTemplate),
                        'visible' => (Yii::$app->user->can('showModulesModule')) ? true : false,
                    ],
                ],
                'visible' => (Yii::$app->user->can('showContentModule')) ? true : false,
            ],
            // Shop
            [
                'label' => Yii::t('ecommerce', 'Catalog'),
                'icon' => 'shopping-cart',
                'template' => '<a href="{url}" title="{label}" class="kv-toggle">{icon}<span class="nav-label">{label}</span></a>',
                'items' => [
                    // Categories
                    [
                        'label' => Yii::t('ecommerce', 'Categories'),
                        'url'   => Url::toRoute('/catalogue/category/index'),
                        'template' => $sideBarItemTemplate,
                        //'visible' => (Yii::$app->user->can('showCategoryModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/catalogue/category') !== false) ? true : false
                    ],
                    // Products
                    [
                        'label' => Yii::t('ecommerce', 'Products'),
                        'url'   => Url::toRoute('/catalogue/product/index'),
                        'template' => $sideBarItemTemplate,
                        //'visible' => (Yii::$app->user->can('showProductModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/catalogue/product') !== false) ? true : false
                    ],
                    // Manufacturers
                    [
                        'label' => Yii::t('ecommerce', 'Manufacturers'),
                        'url'   => Url::toRoute('/catalogue/manufacturer/index'),
                        'template' => $sideBarItemTemplate,
                        //'visible' => (Yii::$app->user->can('showProductModule')) ? true : false,
                        'active' => (stripos(Yii::$app->request->url, '/catalogue/manufacturer') !== false) ? true : false
                    ],
                ],
            ],
            // Media
            [
                'label' => Yii::t('infoweb/app', 'Media'),
                'icon' => 'film',
                'url'   => Url::toRoute('/media/media'),
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showMediaModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/media/media') !== false) ? true : false,
            ],
            // SEO
            [
                'label' => Yii::t('infoweb/seo', 'Seo'),
                'icon' => 'tags',
                'url'   => Url::toRoute('/seo/seo'),
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showSeoModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/seo/seo') !== false) ? true : false
            ],
            // SEA
            [
                'label' => Yii::t('infoweb/sea', 'SEA'),
                'icon' => 'filter',
                'url'   => Url::toRoute('/sea/sea'),
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showSeaModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/sea/sea') !== false) ? true : false
            ],
            // Social media
            [
                'label' => Yii::t('infoweb/social-media', 'Social Media'),
                'icon' => 'share-alt',
                'url'   => Url::toRoute('/social-media/social-media'),
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showSocialMediaModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/social-media/social-media') !== false) ? true : false
            ],
            // Emailmarketing
            [
                'label' => Yii::t('infoweb/emailmarketing', 'Email Marketing'),
                'icon' => 'envelope',
                'url'   => Url::toRoute('/emailmarketing/emailmarketing'),
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showEmailmarketingModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/emailmarketing/emailmarketing') !== false) ? true : false
            ],
            // Analytics
            [
                'label' => Yii::t('infoweb/analytics', 'Analytics'),
                'icon' => 'bar-chart',
                'url'   => Url::toRoute('/analytics/analytics'),
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showAnalyticsModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/analytics/analytics') !== false) ? true : false
            ],
            // Users
            [
                'label' => Yii::t('app', 'Users'),
                'url' => Url::toRoute('/user/admin/index'),
                'icon' => 'user',
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showUsersModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/user/admin') !== false) ? true : false
            ],
            // Rights
            [
                'label' => Yii::t('app', 'Rights'),
                'icon' => 'lock',
                'template' => '<a href="{url}" title="{label}" class="kv-toggle">{icon}<span class="nav-label">{label}</span></a>',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Assigments'),
                        'url' => Url::toRoute('/admin/assignment'),
                        'template' => $sideBarItemTemplate,
                        'active' => (stripos(Yii::$app->request->url, '/admin/assignment') !== false) ? true : false
                    ],
                    [
                        'label' => Yii::t('app', 'Roles'),
                        'url' => Url::toRoute('/admin/role'),
                        'template' => $sideBarItemTemplate,
                        'active' => (stripos(Yii::$app->request->url, '/admin/role') !== false) ? true : false
                    ],
                    [
                        'label' => Yii::t('app', 'Permissions'),
                        'url' => Url::toRoute('/admin/permission'),
                        'template' => $sideBarItemTemplate,
                        'active' => (stripos(Yii::$app->request->url, '/admin/permission') !== false) ? true : false
                    ],
                    [
                        'label' => Yii::t('app', 'Routes'),
                        'url' => Url::toRoute('/admin/route'),
                        'template' => $sideBarItemTemplate,
                        'active' => (stripos(Yii::$app->request->url, '/admin/route') !== false) ? true : false
                    ],
                    [
                        'label' => Yii::t('app', 'Rules'),
                        'url' => Url::toRoute('/admin/rule'),
                        'template' => $sideBarItemTemplate,
                        'active' => (stripos(Yii::$app->request->url, '/admin/rule') !== false) ? true : false
                    ],
                ],
                'visible' => (Yii::$app->user->can('showRightsModule')) ? true : false,
            ],
            // Settings
            [
                'label' => Yii::t('infoweb/settings', 'Settings'),
                'url' => Url::toRoute('/settings/setting'),
                'icon' => 'gear',
                'template' => $sideBarItemTemplate,
                'visible' => (Yii::$app->user->can('showSettingsModule')) ? true : false,
                'active' => (stripos(Yii::$app->request->url, '/settings/setting') !== false) ? true : false
            ],
        ]
    ]);
    ?>

</div>