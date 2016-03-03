<?php

use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}
$modelClass = StringHelper::basename($generator->modelClass);
$moduleName = $generator->getModuleName();

echo "<?php\n";
?>

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Tabs;
use infoweb\<?= $moduleName ?>\assets\<?= $modelClass ?>Asset;

// Register asset bundle(s)
<?= $modelClass ?>Asset::register($this);

?>
<div class="news-form">

    <?= "<?php" ?> // Flash messages ?>
    <?= "<?=" ?> $this->render('_flash_messages'); ?>

    <?= "<?php" ?>
    // Init the form
    $form = ActiveForm::begin([
        'id'                        => '<?= $modelClass ?>-form',
        'options'                   => [
            'class' => 'tabbed-form',
            'enctype' => 'multipart/form-data'
        ],
        'enableAjaxValidation'      => true,
        'enableClientValidation'    => false,
    ]);

    // Initialize the tabs
    $tabs = [];

    // Add the main tabs
    $tabs = [
        [
            'label' => Yii::t('app', 'General'),
            'content' => $this->render('_default_tab', [
                'model' => $model,
                'form' => $form
            ]),
            'active' => true,
        ],
        [
            'label' => Yii::t('app', 'Data'),
            'content' => $this->render('_data_tab', [
                'model' => $model,
                'form' => $form,
            ]),
        ],
    ];

    // Display the tabs
    echo Tabs::widget(['items' => $tabs]);
    ?>

    <div class="form-group buttons">
        <?= "<?=" ?> $this->render('@infoweb/cms/views/ui/formButtons', ['model' => $model]) ?>
    </div>

    <?= "<?php" ?> ActiveForm::end(); ?>

</div>
