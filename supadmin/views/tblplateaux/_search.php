<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TblplateauxSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tblplateaux-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idtblplateaux') ?>

    <?= $form->field($model, 'plateau') ?>

    <?= $form->field($model, 'nomplateau') ?>

    <?= $form->field($model, 'plan') ?>

    <?= $form->field($model, 'locaux') ?>

    <?php // echo $form->field($model, 'description') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
