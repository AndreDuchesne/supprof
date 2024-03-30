<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tblcours $model */

$this->title = Yii::t('app', 'Update Tblcours: {name}', [
    'name' => $model->idtblCompetences,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblcours'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idtblCompetences, 'url' => ['view', 'id' => $model->idtblCompetences]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="tblcours-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
