<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tbldemandes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbldemandes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'NomEleve')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'NoFiche')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Local')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Poste')->textInput() ?>

    <?= $form->field($model, 'HeureDebut')->textInput() ?>

    <?= $form->field($model, 'HeureFin')->textInput() ?>

    <?= $form->field($model, 'CodeUsager')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Etat')->dropDownList([ 'En attente' => 'En attente', 'En cours' => 'En cours', 'Termine' => 'Termine', 'Annule' => 'Annule', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'TypeDemande')->dropDownList([ 'Validation' => 'Validation', 'Explication' => 'Explication', 'Examen' => 'Examen'], ['prompt' => '']) ?>

    <?= $form->field($model, 'Plateau')->dropDownList([ 'LOG' => 'LOG', 'MAT' => 'MAT', 'SER' => 'SER', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'IP')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'HeureInscription')->textInput() ?>

    <?= $form->field($model, 'Cours')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Bloc')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Question')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Ajouter') : Yii::t('app', 'Modifier'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
