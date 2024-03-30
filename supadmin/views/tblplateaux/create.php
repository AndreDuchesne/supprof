<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tblplateaux */

$this->title = Yii::t('app', 'Create Tblplateaux');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tblplateaux'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tblplateaux-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
