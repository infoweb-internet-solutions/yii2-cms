<?php

use infoweb\cms\MediaAsset;
use yii\helpers\Url;

// Register assets
//MediaAsset::register($this);


?>
<style type="text/css">
    #page-wrapper {
        height: 100%;
        padding: 50px 0 60px 0;
    }
</style>


<iframe src="<?= Url::toRoute('media/moxie') ?>" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%" width="100%"></iframe>
