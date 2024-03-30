<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tblmessages */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tblmessages',
]) . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblmessages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tblmessages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
