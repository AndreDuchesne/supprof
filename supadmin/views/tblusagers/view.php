<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tblusagers */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblusagers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tblusagers-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Modifier'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Suprimer'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'fiche',
            'nom',
            'prenom',
            'username',
            [
                'label'=>'password',
                'value'=>'************'
            ],            
            'niveau',
            'status',
            'email',
            'datecreation',
        ],
    ]) ?>

</div>
