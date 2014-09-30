<?php
use infoweb\cms\CMSAsset;
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use kartik\icons\Icon;
use kartik\widgets\SideNav;
use yii\helpers\Url;
use dektrium\user\models\User;

// Fontawesome
Icon::map($this);

// Register assets
CMSAsset::register($this);

// Get current user
$user = User::findOne(Yii::$app->user->id);

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
        <?php // echo Html::csrfMetaTags(); ?>
        <?php $this->head() ?>
    </head>
    <body>

    <?php /* <div class="resolution"></div> */ ?>

    <?php $this->beginBody() ?>


    <?php
    NavBar::begin([
        'brandLabel' => 'Infoweb',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
        'renderInnerContainer' => false,
    ]);

    /*

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post']
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    */

    ?>

    <ul class="nav navbar-left hidden-xs">
        <li>
            <button class="navbar-toggle navbar-minimalize">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </li>
    </ul>

    <?= $this->render('_menu_top_right') ?>

    <div role="navigation" class="navbar-default sidebar">

        <div class="avatar hidden-xs">
            <img src="<?php echo Yii::getAlias('@web') . '/img/profile-picture.png'; ?>" alt="avatar">
            <div><?= yii::t('app', 'Welcome') ?><br><?php echo (!empty($user->username)) ? $user->username : ''; ?></div>
        </div>
        <div class="clearfix"></div>

        <div class="sidebar-nav navbar-collapse">

            <?php
            $items = [
                [
                    'url' => Yii::$app->homeUrl,
                    'label' => 'Home',
                    'icon' => 'home',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    'visible' => (Yii::$app->user->can('sidebarHome')) ? true : false,
                    'active' => true,
                ],
                [
                    'url' => Url::toRoute('/pages/page/index'),
                    'label' => Yii::t('app', 'Pages'),
                    'icon' => 'file',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    'visible' => (Yii::$app->user->can('sidebarPage')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/seo'),
                    'label' => Yii::t('app', 'Seo'),
                    'icon' => 'file',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    //'visible' => (Yii::$app->user->can('sidebarSeo')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/menu/menu/index'),
                    'label' => Yii::t('app', 'Menu'),
                    'icon' => 'book',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    'visible' => (Yii::$app->user->can('sidebarMenu')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/user/admin/index'),
                    'label' => Yii::t('app', 'Users'),
                    'icon' => 'user',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    'visible' => (Yii::$app->user->can('sidebarUser')) ? true : false,
                ],
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
                    'visible' => (Yii::$app->user->can('sidebarRights')) ? true : false,
                ],
                [
                    'label' => Yii::t('app', 'Modules'),
                    'icon' => 'align-justify',
                    'items' => [
                        ['label' => Yii::t('app', 'Sliders'), 'url' => Url::toRoute('/sliders')],
                        ['label' => Yii::t('app', 'Media'), 'url' => '#', 'options' => ['id' => 'media']],
                        ['label' => Yii::t('app', 'Module 1'), 'url' => '#'],
                        ['label' => Yii::t('app', 'Module 2'), 'url' => '#'],
                        ['label' => Yii::t('app', 'Module 3'), 'url' => '#'],
                    ],
                    'visible' => (Yii::$app->user->can('sidebarModule')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/gii'),
                    'label' => Yii::t('app', 'Gii'),
                    'icon' => 'inbox',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    'visible' => (Yii::$app->user->can('sidebarGii')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/tour/index'),
                    'label' => Yii::t('app', 'Tour'),
                    'icon' => 'plus',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    'visible' => (Yii::$app->user->can('sidebarTour')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/translations'),
                    'label' => Yii::t('app', 'Translations'),
                    'icon' => 'download-alt',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    //'visible' => (Yii::$app->user->can('sidebarTranslations')) ? true : false,
                ],
                [
                    'label' => 'Help',
                    'icon' => 'question-sign',
                    'items' => [
                        ['label' => 'About', 'icon' => 'info-sign', 'url' => '#'],
                        ['label' => 'Contact', 'icon' => 'phone', 'url' => '#'],
                    ],
                    'visible' => (Yii::$app->user->can('sidebarHelp')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/site/analytics'),
                    'label' => 'Analytics',
                    'icon' => 'graph',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    'visible' => (Yii::$app->user->can('sidebarAnalytics')) ? true : false,
                ],
                [
                    'url' => Url::toRoute('/utility'),
                    'label' => 'Migrations',
                    'icon' => 'search',
                    'template' => '<a href="{url}">{icon}<span class="nav-label">{label}</span></a>',
                    //'visible' => (Yii::$app->user->can('sidebarAnalytics')) ? true : false,
                ],
            ];
            ?>

            <?php echo SideNav::widget([
                'type' => SideNav::TYPE_DEFAULT,
                'heading' => false,
                'indItem' => false,
                'activateParents' => true,
                'items' => $items,
            ]);
            ?>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <?php


    NavBar::end();
    ?>

    <?= Breadcrumbs::widget([
        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
    ]) ?>

    <div id="page-wrapper" <?php echo (isset($this->params['breadcrumbs'])) ? 'class="breadcrumb-padding"' :''; ?>>
        <?= $content ?>
        <footer class="footer">
            <span class="pull-left">&copy; Infoweb <?= date('Y') ?></span>
            <span class="pull-right"><?= Yii::powered() ?></span>
        </footer>

    </div>



    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>