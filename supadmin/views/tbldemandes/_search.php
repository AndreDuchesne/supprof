<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TbldemandesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tbldemandes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'ID') ?>

    <?= $form->field($model, 'NomEleve') ?>

    <?= $form->field($model, 'NoFiche') ?>

    <?= $form->field($model, 'Local') ?>

    <?= $form->field($model, 'Poste') ?>

    <?php // echo $form->field($model, 'HeureDebut') ?>

    <?php // echo $form->field($model, 'HeureFin') ?>

    <?php // echo $form->field($model, 'CodeUsager') ?>

    <?php // echo $form->field($model, 'Etat') ?>

    <?php // echo $form->field($model, 'Plateau') ?>

    <?php // echo $form->field($model, 'IP') ?>

    <?php // echo $form->field($model, 'HeureInscription') ?>

    <?php // echo $form->field($model, 'Cours') ?>

    <?php // echo $form->field($model, 'Bloc') ?>

    <?php // echo $form->field($model, 'Question') ?>

    <?php // echo $form->field($model, 'url') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
