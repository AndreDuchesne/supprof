<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Tblplateaux */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tblplateaux-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'plateau')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nomplateau')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'locaux')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'stylefond')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'plan')->textarea(['rows'=>12]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Ajouter') : Yii::t('app', 'Modifier'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
