<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tblusagers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tblusagers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'fiche')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nom')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prenom')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'niveau')->dropDownList([ 'enseignant' => 'Enseignant', 'etudiant' => 'Etudiant', 'admin' => 'Admin', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
    
    <?php if($model->isNewRecord): ?>    
        <?= $form->field($model, 'datecreation')->textInput(['readonly'=>'readonly','value'=>date('Y-m-d H:i:s')]) ?>
    <?php else: ?>
        <?= $form->field($model, 'datecreation')->textInput(['readonly'=>'readonly']) ?>
    <?php    endif; ?>
    
    
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
