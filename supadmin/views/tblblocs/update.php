<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tblblocs */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tblblocs',
]) . ' ' . $model->idtblblocs;
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblblocs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idtblblocs, 'url' => ['view', 'id' => $model->idtblblocs]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Modifier');
?>
<div class="tblblocs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
