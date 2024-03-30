<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblmessagesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tblmessages-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'titre') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'lien') ?>

    <?= $form->field($model, 'local') ?>

    <?php // echo $form->field($model, 'date_evenement') ?>

    <?php // echo $form->field($model, 'debut') ?>

    <?php // echo $form->field($model, 'fin') ?>

    <?php // echo $form->field($model, 'idenseignant') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
