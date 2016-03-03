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

    <?= "<?=" ?> $form->field($model, 'active')->widget(SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
            'onColor' => 'success',
            'offColor' => 'danger',
            'onText' => Yii::t('app', 'Yes'),
            'offText' => Yii::t('app', 'No'),
        ]
    ]); ?>


    <?php foreach ($generator->getColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes)) {
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        }
    } ?>


</div>