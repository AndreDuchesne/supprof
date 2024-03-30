<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use app\models\Tblplateaux;
//use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TblplateauxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tblplateaux');
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
                url : \'index.php?r=tblplateaux/multiple-delete\',
                data : {row_id: HotId},
                success : function() {
                    $(this).closest(\'tr\').remove(); //or whatever html you use for displaying rows
                    }
                });
            }
        });
        




        
    });', \yii\web\View::POS_READY);

?>










<div class="tblplateaux-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Ajouter'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button(Yii::t('app', 'Supprimer'), ['class' => 'btn btn-danger','title'=>'Supprimer des éléments','id'=>'BtnDelete']) ?>  
    </p>



    
    
    
        <?php
        echo GridView::widget([
            'dataProvider'=>$dataProvider,
            'filterModel'=>$searchModel,
        'columns' => [
            ['class'=> 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\SerialColumn'],

            'idtblplateaux',
            'plateau',
            'nomplateau',
            //'plan',            
            'locaux',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Tblplateaux $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->idtblplateaux]);
                 }
            ],
            // Afficher le champ "plan" en tant que fichier SVG
            [
                'attribute' => 'plan',
                'format' => 'raw',
                'value' => function ($model) {
                    // Retourner le contenu SVG
                    $thumbnail = '<div class="svg-thumbnail">';
                    $thumbnail .= $model->plan;
                    $thumbnail .='</div>';
                    return $thumbnail;
                },
            ],            

            // 'description',

            //        [
            //            'class' => '\kartik\grid\ActionColumn',
            //            'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            //        ]
            ],

                       
            
            //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
            //'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            //'filterRowOptions'=>['class'=>'kartik-sheet-style'],
            //'pjax'=>true, // pjax is set to always true for this demo
            // set export properties
            //'export'=>[
            //    'fontAwesome'=>true
            //],
            // parameters from the demo form
            //'bordered'=>true,
            //'striped'=>$striped,
            //'condensed'=>$condensed,
            //'responsive'=>$responsive,
            //'hover'=>$hover,
            //'showPageSummary'=>$pageSummary,
            //'panel'=>[
            //    'type'=>GridView::TYPE_PRIMARY,
            //    'heading'=>$heading,
            //],
            //'persistResize'=>false,
            //'exportConfig'=>$exportConfig,
        ]);
    ?>
    
    
</div>
