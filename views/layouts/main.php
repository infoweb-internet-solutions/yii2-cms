<?php
use infoweb\cms\CMSAsset;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use kartik\widgets\SideNav;
use kartik\icons\Icon;
use yii\helpers\Url;
use dektrium\user\models\User;

// Register assets
$cmsAssets = CMSAsset::register($this);

// Active font-awesome icons
Icon::map($this);

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
    
        <ul class="nav navbar-left hidden-xs">
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
                    'items' => [
                        [
                            'url' => Yii::$app->homeUrl,
                            'label' => 'Home',
                            'icon' => 'home',
                            'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                            'visible' => true,
                            'active' => true,
                        ],
                        // Users module
                        [
                            'url' => Url::toRoute('/user/admin/index'),
                            'label' => Yii::t('app', 'Users'),
                            'icon' => 'user',
                            'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                            'visible' => (Yii::$app->user->can('showUsersModule')) ? true : false,
                        ],
                        // Rights module
                        [
                            'label' => Yii::t('app', 'Rights'),
                            'icon' => 'tasks',
                            'items' => [
                                ['label' => Yii::t('app', 'Assigments'), 'url' => Url::toRoute('/admin/assigment')],
                                ['label' => Yii::t('app', 'Roles'), 'url' => Url::toRoute('/admin/role')],
                                ['label' => Yii::t('app', 'Permissions'), 'url' => Url::toRoute('/admin/permission')],
                                ['label' => Yii::t('app', 'Routes'), 'url' => Url::toRoute('/admin/route')],
                                ['label' => Yii::t('app', 'Rules'), 'url' => Url::toRoute('/admin/rule')],
                                //['label' => Yii::t('app', 'Menu'), 'url' => Url::toRoute('/admin/menu')],
                            ],
                            'visible' => (Yii::$app->user->can('showRightsModule')) ? true : false,
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