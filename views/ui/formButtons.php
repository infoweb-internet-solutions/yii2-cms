<?php
use yii\helpers\Html;
?>
<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
        'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
        'name'  => 'save'
    ]) ?>&nbsp;
<?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create & close') : Yii::t('app', 'Update & close'), [
        'class' => 'btn btn-default',
        'name'  => 'save-close'
    ]) ?>&nbsp;
<?= Html::submitButton(Yii::t('app', $model->isNewRecord ? 'Create & new' : 'Update & new'), [
        'class' => 'btn btn-default',
        'name'  => 'save-add'
    ]) ?>&nbsp;
<?= Html::a(Yii::t('app', 'Close'), ['index'], [
        'class' => 'btn btn-danger',
        'name'  => 'close'
    ]) ?>