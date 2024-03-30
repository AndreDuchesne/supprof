<?php

use app\models\Tblcours;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var app\models\TblcoursSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Tblcours');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php 

    $this->registerJs(' 

    $(document).ready(function(){

    $(\'#BtnDelete\').click(function(){
        var choix = confirm(\'Voulez-vous vraiment supprimer les éléments sélectionnés ?\');
        if(choix==1){
            var HotId = $(\'#w0\').yiiGridView(\'getSelectedRows\');
              $.ajax({
                type: \'POST\',
                url : \'index.php?r=tblcours/multiple-delete\',
                data : {row_id: HotId},
                success : function() {
                    $(this).closest(\'tr\').remove(); //or whatever html you use for displaying rows
                    }
                });
            }
        });
        




        
    });', \yii\web\View::POS_READY);

?>






<div class="tblcours-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Ajouter'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button(Yii::t('app', 'Supprimer'), ['class' => 'btn btn-danger','title'=>'Supprimer des cours','id'=>'BtnDelete']) ?>  
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class'=> 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],
            

            'idtblCompetences',
            'titreCompetence',
            'numeroCompetence',
            'plateau',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Tblcours $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->idtblCompetences]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
