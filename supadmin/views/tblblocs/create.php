<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tblblocs */

$this->title = Yii::t('app', 'Create Tblblocs');
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblblocs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tblblocs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
