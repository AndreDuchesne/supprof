<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tblpostes */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tblpostes',
]) . ' ' . $model->idtblpostes;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblpostes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idtblpostes, 'url' => ['view', 'id' => $model->idtblpostes]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tblpostes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
