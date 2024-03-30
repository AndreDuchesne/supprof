<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\models\Tblinscriptions;

//use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblinscriptionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tblinscriptions');
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
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
                url : \'index.php?r=tblinscriptions/multiple-delete\',
                data : {row_id: HotId},
                success : function() {
                    $(this).closest(\'tr\').remove(); //or whatever html you use for displaying rows
                    }
                });
            }
        });
        




        
    });', \yii\web\View::POS_READY);

?>







<div class="tblinscriptions-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Ajouter'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::button(Yii::t('app', 'Supprimer'), ['class' => 'btn btn-danger','title'=>'Supprimer des cours','id'=>'BtnDelete']) ?>  
    </p>

    
    
    
    
    <?php
        echo GridView::widget([
            'dataProvider'=>$dataProvider,
            'filterModel'=>$searchModel,
            'columns'=>[
                            ['class'=> 'yii\grid\CheckboxColumn'],
                            ['class' => 'yii\grid\SerialColumn'],
    
                            'idinscription',
                            'idParticipant',
                                [
                                  'header'=>'Prénom',
                                  'value'=>'users.prenom'
                                ],
                                [
                                  'header'=>'Nom',
                                  'value'=>'users.nom'
                                ],                
                            'idEvenement',
                                [
                                    'header'=>'Titre de la capsule/événement',
                                    'value'=>'messages.titre',
                                ],
                                [
                                    'class' => ActionColumn::className(),
                                    'urlCreator' => function ($action, Tblinscriptions $model, $key, $index, $column) {
                                        return Url::toRoute([$action, 'id' => $model->idinscription]);
                                     }
                                ],                                
                            //[
                            //    'class' => '\kartik\grid\ActionColumn',
                            //    'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
                            //]
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
