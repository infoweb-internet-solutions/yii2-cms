<?php
use kartik\helpers\Html;
?>
<?= Html::textInput('fields[2426ced136024a631a2a0672a269b700]', '', [
    'class' => 'form-control',
    'placeholder' => Yii::t('frontend', 'Vul hier je e-mailadres in'),
    'addon' => [
        'append' => [
            'content' => Html::button(Yii::t('frontend', 'Verzenden'), ['class'=>'btn btn-primary']),
            'asButton' => true
        ]
    ]
]);
?>
