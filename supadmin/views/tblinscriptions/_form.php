<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tblinscriptions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tblinscriptions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idParticipant')->textInput() ?>

    <?= $form->field($model, 'idEvenement')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Ajouter') : Yii::t('app', 'Modifier'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
