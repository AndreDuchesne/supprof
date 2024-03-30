<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblblocsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tblblocs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idtblblocs') ?>

    <?= $form->field($model, 'numeroBloc') ?>

    <?= $form->field($model, 'sujet') ?>

    <?= $form->field($model, 'plateau') ?>

    <?= $form->field($model, 'cours') ?>

    <?php // echo $form->field($model, 'url') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
