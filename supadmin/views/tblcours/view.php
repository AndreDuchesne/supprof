<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Tblcours $model */

$this->title = $model->idtblCompetences;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblcours'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tblcours-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Modier'), ['update', 'id' => $model->idtblCompetences], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Supprimer'), ['delete', 'id' => $model->idtblCompetences], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Voulez-vous vraiment effacer cet élément ?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'idtblCompetences',
            'titreCompetence',
            'numeroCompetence',
            'plateau',
        ],
    ]) ?>

</div>
