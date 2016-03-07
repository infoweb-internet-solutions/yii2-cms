<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\crud\Generator */

use yii\widgets\Pjax;
use infoweb\cms\assets\GeneratorAsset;
GeneratorAsset::register($this);

echo $form->field($generator, 'modelClass')->textInput(['data-url' => \yii\helpers\Url::toRoute('/site/test')]);
echo $form->field($generator, 'modelLangClass');
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'gridColumns')->checkboxList(["test" => 'test']);
echo $form->field($generator, 'enablePjax')->checkbox();
echo $form->field($generator, 'messageCategory');

?>

<?php Pjax::begin([
    'id' => 'test',
]) ?>
<div class="form-group">

    <?= $generator->modelClass ?>
    <?php if ($generator->modelClass): ?>
    <?php echo '<pre>'; print_r($generator->getColumnNames()); echo '</pre>'; ?>
    <?php endif; ?>
</div>
<?php Pjax::end() ?>