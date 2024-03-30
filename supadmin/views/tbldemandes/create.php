<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tbldemandes */

$this->title = Yii::t('app', 'Create Tbldemandes');
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tbldemandes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tbldemandes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
