<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<ul class="nav navbar-top-controls navbar-right">

    <?php if (!Yii::$app->user->isGuest) : ?>

    <li class="pull-left nav-item-globe">
        <a target="_blank" href="<?= str_replace('/admin', '', Yii::$app->homeUrl); ?>" title="<?php echo Yii::t('infoweb/cms', 'View website'); ?>">
            <i class="fa fa-globe fa-fw"></i>
        </a>
    </li>

    <?php if (Yii::$app->getModule('email') && Yii::$app->user->can('showEmailModule')) : ?>
    <li class="pull-left nav-item-unread-mails"<?php if (Yii::$app->getModule('email')->getUnreadEmails()): ?> style="padding-right: 25px;"<?php endif; ?>>
        <a href="<?= Url::toRoute('/email/email'); ?>" class="btn-unread-emails" title="<?php echo Yii::t('infoweb/email', 'Emails'); ?>">
            <i class="fa fa-envelope fa-fw"></i>
            <?php if (Yii::$app->getModule('email')->getUnreadEmails()): ?>
            &nbsp;
            <span class="label label-danger unread-emails">
                <?php echo Yii::$app->getModule('email')->getUnreadEmails(); ?>
            </span>
        <?php endif; ?>
        </a>
    </li>
    <?php endif; ?>

    <li class="dropdown pull-left">        
        <a href="#" id="dropdown-menu-user" class="dropdown-toggle user" data-toggle="dropdown">
            <img src="<?php echo (Yii::$app->user->identity->getImage(false)) ? Yii::$app->user->identity->image->getUrl('60px') : $this->params['cmsAssets']->baseUrl . '/img/avatar.png'; ?>" alt="avatar" class="avatar img-circle">
            <?php if (!empty(Yii::$app->user->identity->profile->name)) : ?>
            <?php echo ((isset(Yii::$app->user->identity->profile->firstname)) ? Yii::$app->user->identity->profile->firstname . ' ' : '') . Yii::$app->user->identity->profile->name; ?>
            <?php else : ?>
            <?php echo Yii::$app->user->identity->username; ?>    
            <?php endif; ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdown-menu-user">
            <?php if (Yii::$app->user->can('showProfile')): ?>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="<?= Url::toRoute('/user/settings/profile'); ?>" title="<?php echo Yii::t('user', 'Profile'); ?>">
                    <span class="fa fa-fw fa-user"></span> <?php echo Yii::t('user', 'Profile'); ?>
                </a>
            </li>
            <?php endif; ?>
            <?php if (Yii::$app->user->can('showAccount')): ?>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="<?= Url::toRoute('/user/settings/account'); ?>" title="<?php echo Yii::t('app', 'Account'); ?>">
                    <span class="fa fa-fw fa-cogs"></span> <?php echo Yii::t('user', 'Account'); ?>
                </a>
            </li>
            <?php endif; ?>
            <li role="presentation">
                <a role="menuitem" tabindex="-1" href="<?= Url::toRoute('/user/security/logout', true); ?>" title="<?php echo Yii::t('user', 'Logout'); ?>" data-method="post">
                    <span class="fa fa-fw fa-power-off"></span> <?php echo Yii::t('user', 'Logout'); ?>
                </a>
            </li>    
        </ul>
    </li>
    
    <?php endif; ?>
</ul>