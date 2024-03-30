<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Tblplateaux */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Tblplateaux',
]) . ' ' . $model->idtblplateaux;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblplateaux'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idtblplateaux, 'url' => ['view', 'id' => $model->idtblplateaux]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tblplateaux-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
