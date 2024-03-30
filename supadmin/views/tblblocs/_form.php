<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tblblocs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tblblocs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numeroBloc')->textInput() ?>

    <?= $form->field($model, 'sujet')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plateau')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cours')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Ajouter') : Yii::t('app', 'Ajouter'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
