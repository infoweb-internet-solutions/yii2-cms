<?php

use yii\helpers\StringHelper;

$modelClass = StringHelper::basename($generator->modelClass);

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";

?>

use yii\helpers\Html;
use kartik\widgets\SwitchInput;

?>
<div class="tab-content data-tab">
    <?php foreach ($generator->getActiveColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes)) {
            echo "<?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        }
    } ?>
</div>