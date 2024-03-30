<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tblinscriptions */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tblinscriptions',
]) . ' ' . $model->idinscription;
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblinscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idinscription, 'url' => ['view', 'id' => $model->idinscription]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tblinscriptions-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
