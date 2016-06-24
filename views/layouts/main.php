<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\widgets\Breadcrumbs;
use kartik\icons\Icon;
use infoweb\cms\CMSAsset;

// Active font-awesome icons
Icon::map($this);

// Register assets
$cmsAssets = CMSAsset::register($this);
$this->params['cmsAssets'] = $cmsAssets;
$this->registerJs("CMS.setCkeditorEntitylinkConfiguration(".json_encode(Yii::$app->getModule('menu')->getCkeditorEntitylinkConfiguration()).");", \yii\web\View::POS_READY, 'ckeditorEntitylinkConfiguration');

?>
<?php $this->beginPage() ?>
 <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <link rel="shortcut icon" href="<?= $this->params['cmsAssets']->baseUrl.'/img/favicon.ico' ?>">
        <?php $this->head() ?>
    </head>
    <body class="dark-sidebar-layout<?php echo (isset($_COOKIE['infoweb-admin-sidebar-state']) && $_COOKIE['infoweb-admin-sidebar-state'] == 'closed') ? ' mini-navbar' : ''; ?><?php echo (Yii::$app->user->isGuest) ? ' is-guest' : ''; ?>">
        <?php $this->beginBody() ?>

        <?php
        // Navbar
        NavBar::begin([
            //'brandLabel' => Html::img($cmsAssets->baseUrl.'/img/logo-infoweb.png', ['class' => 'brand-logo', 'alt' => 'brand-logo']),
            //'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
            'renderInnerContainer' => false,
        ]);
        ?>

        <?php // Sidebar toggler ?>
        <?php if (!Yii::$app->user->isGuest) : ?>
        <ul class="nav navbar-left hidden-xs sidebar-toggle" style="display: none;">
            <li>
                <button class="navbar-toggle navbar-minimalize">
                    <span class="sr-only"><?= Yii::t('app', 'Toggle navigation'); ?></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </li>
        </ul>
        <?php endif; ?>

        <?php // Top right menu ?>
        <?= $this->render('_menu_top_right') ?>

        <?php // Sidebar ?>
        <div role="navigation" class="navbar-default sidebar">

            <?php /*
            <div class="avatar hidden-xs" style="display: none;">
                <img src="<?php echo $cmsAssets->baseUrl; ?>/img/profile-picture.png" alt="avatar">
                <div>
                    <?= Yii::t('app', 'Welcome') ?>
                    <?php if (!empty(Yii::$app->user->identity->profile->name)) : ?>
                    <br /><?php echo Yii::$app->user->identity->profile->name; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="clearfix"></div>
            */ ?>
            <?php // Sidebar menu ?>
            <?= $this->render('_menu_sidebar') ?>

        </div>

        <?php NavBar::end(); ?>

        <?php if (!Yii::$app->user->isGuest) : ?>

        <?php // Breadcrumbs ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?php endif; ?>

        <?php // Page content ?>
        <div id="page-wrapper" <?php echo (isset($this->params['breadcrumbs'])) ? 'class="breadcrumb-padding"' :''; ?>>
            <?= $content ?>
            <?php // Footer ?>
            <footer class="footer">
                <span class="pull-left">&copy; Infoweb <?= date('Y') ?></span>
                <span class="pull-right"><?php /*<?= Yii::powered() ?>*/ ?></span>
            </footer>
        </div>

        <?php // Duplicateable plugin modal ?>
        <?php Modal::begin([
            'id' => 'duplicateable-modal',
            'header' => '<h2>'.Yii::t('infoweb/cms', 'Duplicate content').'</h2>',
            'footer' => '<button type="button" class="btn btn-primary" id="do-duplication">'.Yii::t('infoweb/cms', 'Duplicate').'</button><button type="button" class="btn btn-default" data-dismiss="modal">'.Yii::t('app', 'Close').'</button>'
        ]); ?>
        <div class="row">
            <div class="col-sm-6">
                <h3><?= Yii::t('infoweb/cms', 'Languages'); ?></h3>
                <ul class="duplicateable-languages">
                    <?php foreach (Yii::$app->params['languages'] as $k => $v) : ?>
                    <li>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="<?php echo $k; ?>" name="duplicateable-languages[]" class="duplicateable-languages" checked /> <?php echo $v; ?>
                            </label>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-sm-6">
                <h3><?= Yii::t('infoweb/cms', 'Options'); ?></h3>
                <ul class="duplicateable-settings">
                    <li>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="duplicate-empty-values" class="duplicate-empty-values" /> <?= Yii::t('infoweb/cms', 'Duplicate empty fields'); ?>
                            </label>
                        </div>
                    </li>
                    <li>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="overwrite-values" class="overwrite-values" checked /> <?= Yii::t('infoweb/cms', 'Overwrite populated fields'); ?>
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <?php Modal::end(); ?>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>