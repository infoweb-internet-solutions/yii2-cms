<?php
use yii\helpers\StringHelper;

$modelClass = StringHelper::basename($generator->modelLangClass);
$moduleName = $generator->getModuleName();

$model = new $generator->modelLangClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>
use mihaildev\ckeditor\CKEditor;
?>
<div class="tab-content default-language-tab">
    <?php foreach ($generator->getActiveLangColumnNames() as $attribute) {
        if (in_array($attribute, $safeAttributes)) {
            echo "    <?= " . $generator->generateActiveLangField($attribute) . " ?>\n\n";
        }
    } ?>
</div>
