<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tblplateaux */

$this->title = $model->idtblplateaux;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblplateaux'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tblplateaux-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Modifier'), ['update', 'id' => $model->idtblplateaux], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Supprimer'), ['delete', 'id' => $model->idtblplateaux], [
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
            'idtblplateaux',
            'plateau',
            'nomplateau',
            'locaux',
            'stylefond',
            'description:ntext',
            [
                'attribute' => 'plan',
                'format' => 'raw',
                'value' => function ($model) {
                    // Retourner le contenu SVG
                    $thumbnail = '<div class="svg-view">';
                    $thumbnail .= $model->plan;
                    $thumbnail .='</div>';
                    return $thumbnail;
                },
            ],
        ],
    ]) ?>

</div>
