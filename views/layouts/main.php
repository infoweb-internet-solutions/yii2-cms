<?php
use infoweb\cms\CMSAsset;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use kartik\widgets\SideNav;
use kartik\icons\Icon;
use yii\helpers\Url;
use dektrium\user\models\User;

// Active font-awesome icons
Icon::map($this);

// Register assets
$cmsAssets = CMSAsset::register($this);

// The html template for the sidebar items
$sideBarItemTemplate = '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>';

/**
 * @var \yii\web\View $this
 * @var string $content
 */
?>
<?php $this->beginPage() ?>
 <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>   
    
        <?php
        // Navbar
        NavBar::begin([
            'brandLabel' => Yii::$app->params['companyName'],
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
            'renderInnerContainer' => false,
        ]);
        ?>
        
        <?php // Sidebar toggler ?>
        <ul class="nav navbar-left hidden-xs" style="display: none;">
            <li>
                <button class="navbar-toggle navbar-minimalize">
                    <span class="sr-only"><?= Yii::t('app', 'Toggle navigation'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </li>
        </ul>
    
        <?php // Top right menu ?>
        <?= $this->render('_menu_top_right') ?>
    
        <?php // Sidebar ?>
        <div role="navigation" class="navbar-default sidebar">
    
            <div class="avatar hidden-xs">
                <img src="<?php echo $cmsAssets->baseUrl; ?>/img/profile-picture.png" alt="avatar">
                <div>
                    <?= Yii::t('app', 'Welcome') ?>
                    <?php if (!empty(Yii::$app->user->identity->profile->name)) : ?>
                    <br /><?php echo Yii::$app->user->identity->profile->name; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="clearfix"></div>
    
            <div class="sidebar-nav navbar-collapse">    
                <?php // Main menu ?>
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
                                    'url'   => Url::toRoute('/page-partials/page-partial'),
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
                            ],
                            'visible' => (Yii::$app->user->can('showContentModule')) ? true : false,
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
        <?php NavBar::end(); ?>
    
        <?php // Breadcrumbs ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    
        <?php // Page content ?>
        <div id="page-wrapper" <?php echo (isset($this->params['breadcrumbs'])) ? 'class="breadcrumb-padding"' :''; ?>>
            <?= $content ?>
            
            <?php // Footer ?>
            <footer class="footer">
                <span class="pull-left">&copy; Infoweb <?= date('Y') ?></span>
                <span class="pull-right"><?= Yii::powered() ?></span>
            </footer>    
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>