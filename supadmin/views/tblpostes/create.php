<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tblpostes */

$this->title = Yii::t('app', 'Create Tblpostes');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblpostes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tblpostes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
