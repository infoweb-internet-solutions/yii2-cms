<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

    <h1>
        <?= "<?= " ?>Html::encode($this->title) ?>
        <?php // Buttons ?>
        <div class="pull-right">
            <?= "<?= " ?>Html::a(Yii::t('<?= $generator->messageCategory ?>', 'Create {modelClass}', [
            'modelClass' => Yii::t('<?= $generator->messageCategory ?>', '<?= Inflector::camel2words(StringHelper::basename($generator->modelClass)) ?>'),
            ]), ['create'], ['class' => 'btn btn-success']) ?>
        </div>
    </h1>

    <?= "<?php " ?> // Flash messages ?>
    <?= "<?= " ?>$this->render('_flash_messages') ?>

    <?= "<?php " ?> // Gridview ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            // '" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>

            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{update} {delete} {active}',
                'buttons' => [
                    'active' => function ($url, $model) {
                        if ($model->active == true) {
                            $icon = 'glyphicon-eye-open';
                        } else {
                            $icon = 'glyphicon-eye-close';
                        }

                        return Html::a(
                            '<span class="glyphicon ' . $icon . '"></span>', $url, [
                                'title' => Yii::t('app', 'Toggle active'),
                                'data-pjax' => '0',
                                'data-toggleable' => 'true',
                                'data-toggle-id' => $model->id,
                                'data-toggle' => 'tooltip',
                            ]
                        );
                    },
                ],
                'updateOptions'=>['title' => Yii::t('app', 'Update'), 'data-toggle' => 'tooltip'],
                'deleteOptions'=>['title' => Yii::t('app', 'Delete'), 'data-toggle' => 'tooltip'],
                'width' => '120px',
            ],
        ],
    ]); ?>

</div>
