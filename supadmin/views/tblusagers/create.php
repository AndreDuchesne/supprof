<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tblusagers */

$this->title = Yii::t('app', 'Create Tblusagers');
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblusagers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tblusagers-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
