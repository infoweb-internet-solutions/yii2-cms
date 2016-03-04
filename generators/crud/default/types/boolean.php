$form->field($model, '<?= $attribute ?>')->widget(
    SwitchInput::classname(), [
        'inlineLabel' => false,
        'pluginOptions' => [
        'onColor' => 'success',
        'offColor' => 'danger',
        'onText' => Yii::t('app', 'Yes'),
        'offText' => Yii::t('app', 'No'),
    ]
]);