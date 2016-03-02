<?php

use yii\helpers\StringHelper;

$modelClass = StringHelper::basename($generator->modelClass);

?>
<?= "<?php" ?> if (Yii::$app->getSession()->hasFlash('<?= $modelClass ?>')): ?>
<div class="alert alert-success">
    <p><?= "<?=" ?> Yii::$app->getSession()->getFlash('<?= $modelClass ?>') ?></p>
</div>
<?= "<?php" ?> endif; ?>

<?= "<?php" ?> if (Yii::$app->getSession()->hasFlash('<?= $modelClass ?>-error')): ?>
<div class="alert alert-danger">
    <p><?= "<?=" ?> Yii::$app->getSession()->getFlash('<?= $modelClass ?>-error') ?></p>
</div>
<?= "<?php" ?> endif; ?>
