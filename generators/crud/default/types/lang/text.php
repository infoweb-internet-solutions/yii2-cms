$form->field($model, "[{$model->language}]<?= $attribute ?>")->widget(CKEditor::className(), [
    'name' => "Lang[{$model->language}][<?= $attribute ?>]",
    'editorOptions' => Yii::$app->getModule('cms')->getCKEditorOptions(),
    'options' => ['data-duplicateable' => Yii::$app->getModule('<?= $moduleName ?>')->allowContentDuplication ? 'true' : 'false']
]);