<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Alert;

$this->title = 'Connexion';
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php 
    if(Yii::$app->session->hasFlash('ErreurNiveau')){
    echo Alert::widget([
        'options'=>['class'=>'alert-danger'],
        'body'=>Yii::$app->session->getFlash('ErreurNiveau'),
    ]);
    }

    

    ?>



    <p>S.V.P. Complétez les champs <strong>Nom d'usager</strong> et <strong>Mot de passe</strong> afin d'accéder la section de gestion des tables:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-2\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>


        <div class="form-group">
            <div class="col-lg-offset-4 col-lg-11">
                <?= Html::submitButton('Connexion', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-0">
        
        <code><strong>Attention !</strong></code><br/>
        <div class="text-muted col-lg-offset-1">
        Seulement les utilisateurs qui ont le niveau d'accèes <strong>Administrateur</strong> peuvent avoir accès à la gestion 
        des tables du système. Vous devez fournir une nom d'usager et un mot de passe.
        </div>
        
    </div>
</div>
