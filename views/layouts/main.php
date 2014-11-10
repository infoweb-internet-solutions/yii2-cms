<?php
use yii\helpers\Html;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use kartik\icons\Icon;
use infoweb\cms\CMSAsset;

// Active font-awesome icons
Icon::map($this);

// Register assets
$cmsAssets = CMSAsset::register($this);
$this->params['cmsAssets'] = $cmsAssets;
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
    <body class="dark-sidebar-layout <?php echo (Yii::$app->user->isGuest) ? 'is-guest' : ''; ?>">
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
        <ul class="nav navbar-left hidden-xs sidebar-toggle">
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

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>