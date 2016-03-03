<?php

use yii\helpers\StringHelper;

$modelClass = StringHelper::basename($generator->modelClass);

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

</div>