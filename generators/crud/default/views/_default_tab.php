<?php
$moduleName = $generator->getModuleName();

echo "<?php\n";
?>
use yii\bootstrap\Tabs;
use kartik\icons\Icon;

// Add the language tabs
foreach (Yii::$app->params['languages'] as $languageId => $languageName) {
    $tabs[] = [
        'label'   => $languageName .
            (
                (Yii::$app->getModule('<?= $moduleName ?>')->allowContentDuplication) ?
                Icon::show('exchange', ['class' => 'duplicateable-all-icon not-converted', 'data-language' => $languageId]) :
                ''
            ),
        'content' => $this->render('_default_language_tab', [
            'model' => $model->getTranslation($languageId),
            'form'  => $form,
        ]),
    ];
}
?>
<div class="tab-content default-tab">
    <?= "<?=" ?> Tabs::widget(['items' => $tabs, 'encodeLabels' => false]); ?>
</div>
