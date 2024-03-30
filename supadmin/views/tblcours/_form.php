<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Tblcours $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="tblcours-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'titreCompetence')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numeroCompetence')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plateau')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
