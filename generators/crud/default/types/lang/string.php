$form->field($model, "[{$model->language}]<?= $attribute ?>")->textInput([
    'maxlength' => 255,
    'name' => "Lang[{$model->language}][<?= $attribute ?>]",
    'data-duplicateable' => Yii::$app->getModule('<?= $moduleName ?>')->allowContentDuplication ? 'true' : 'false'
]);