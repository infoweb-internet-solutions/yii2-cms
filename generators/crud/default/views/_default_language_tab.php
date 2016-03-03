<?php
use yii\helpers\StringHelper;
$modelClass = StringHelper::basename($generator->modelClass);
$moduleName = $generator->getModuleName();


$model = new $generator->modelLangClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use mihaildev\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
?>
<div class="tab-content default-language-tab">
    <?= "<?=" ?> $form->field($model, "[{$model->language}]question")->textInput([
        'maxlength' => 255,
        'name' => "Lang[{$model->language}][question]",
        'data-duplicateable' => Yii::$app->getModule('<?= $moduleName ?>')->allowContentDuplication ? 'true' : 'false'
    ]); ?>

    <?= "<?=" ?> $form->field($model, "[{$model->language}]answer")->widget(CKEditor::className(), [
        'name' => "Lang[{$model->language}][answer]",
        'editorOptions' => Yii::$app->getModule('cms')->getCKEditorOptions(),
        'options' => ['data-duplicateable' => Yii::$app->getModule('<?= $moduleName ?>')->allowContentDuplication ? 'true' : 'false']
    ]); ?>

    <?php foreach ($generator->getLangColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes)) {
            echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
        }
    } ?>


</div>
