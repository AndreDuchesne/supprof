<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\FileInput;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TblusagersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tblusagers');
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
                url : \'index.php?r=tblusagers/multiple-delete\',
                data : {row_id: HotId},
                success : function() {
                    $(this).closest(\'tr\').remove(); //or whatever html you use for displaying rows
                    }
                });
            }
        });
        


    $(\'#BtnExport\').click(function(){
        var choix = confirm(\'Voulez-vous vraiment exporter les éléments sélectionnés ?\');
        if(choix==1){
            var HotId = $(\'#w0\').yiiGridView(\'getSelectedRows\');  
            if(HotId.length!=0){
                $.ajax({
                  type: \'POST\',
                  url : \'index.php?r=tblusagers/multiple-export\',
                  data : {row_id: HotId},
                  success : function() {
                      alert(\'Exportation terminée\');
                      }
                  });
                }
             else{
                alert(\'ERREUR EXPORTATION !\nVous devez sélectionner une ou plusieurs lignes a exporter\');
             }                
            }
        });


$(\'#BtnImport\').click(function(){
        var choix = confirm(\'Voulez-vous vraiment IMPORTER les éléments et ajouter les lignes à la table ?\');
        if(choix==1){
            document.getElementById(\'w0\').submit();
            }
        });


        
    });', \yii\web\View::POS_READY);

?>


<div class="tblusagers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    
    <?= Html::a(Yii::t('app', 'Ajouter un Usager'), ['create'], ['class' => 'btn btn-success','title'=>'Ajouter un usager']) ?>        
    <?= Html::button(Yii::t('app', 'Supprimer usager(s)'), ['class' => 'btn btn-danger','title'=>'Supprimer des usagers','id'=>'BtnDelete']) ?>  
    
    
    <?php
    /*
    echo Html::button(Yii::t('app', ' < Exporter'), ['class' => 'btn btn-default text-success glyphicon glyphicon-floppy-remove','title'=>'Exporter vers Excel','id'=>'BtnExport']);


    
    echo Html::beginForm(['tblusagers/import'],'post',['name'=>'import','enctype'=>'multipart/form-data']);
    echo Html::submitButton(Yii::t('app', ' > Importer'), ['class' => 'btn btn-default text-success glyphicon glyphicon-floppy-remove','title'=>'Importer de Excel','id'=>'BtnImport']);
    echo Html::fileInput('fichiercsv','',['class'=>'btn btn-default','style'=>'display:inline;width:320px','id'=>'fileimport','name'=>'fileimport']);
    */
    ?>


    <?php if(Yii::$app->session->hasFlash('success')):?>
    <div class="alert-success">
        <?php echo Yii::$app->session->getFlash('success'); ?>
    </div>
    <?php elseif(Yii::$app->session->hasFlash('error')):?>
    <div class="alert-danger">
        <?php echo Yii::$app->session->getFlash('error'); ?>
    </div>
    <?php endif ?>

   
    <?= Html::endForm(); ?>
    
    
  
    </p>  
    


    
    
    
    
    <?php
        echo GridView::widget([
            'dataProvider'=>$dataProvider,
            'filterModel'=>$searchModel,
            'columns'=>[                
                        [
                            'class'=>'yii\grid\CheckboxColumn',
                        ],
                        'id',
                        'fiche',
                        'nom',
                        'prenom',
                        'username',
                        //'password',
                        'niveau',     
                        'status',                          
                        [
                            'class'=>'yii\grid\ActionColumn',
                        ],                        
                        // Action column with edit, update and delete
                        //    [
                        //        'class' => '\kartik\grid\ActionColumn',
                        //        'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
                        //    ],
                        //Checkbox column
                        //    [
                        //        'class' => 'yii\grid\CheckboxColumn',
                        //        // you may configure additional properties here
                        //    ],
                        ],
            
            //'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
            //'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            //'filterRowOptions'=>['class'=>'kartik-sheet-style'],
            //'pjax'=>true, // pjax is set to always true for this demo
            // set export properties
            //'pjaxSettings' => [
            //                    'options' => [
            //                        'enablePushState' => true,
            //                        'options' => ['id' => 'unique-pjax-id'] // UNIQUE PJAX CONTAINER ID
            //                        ],
            //                    ],
            
      
            

            //'toolbar' => [
            //     [
            //         'content'=>
            //         
            //             Html::a('<i class="glyphicon glyphicon-repeat"></i>', 
            //             ['index'], 
            //             [
            //                 'class' => 'btn btn-default', 
            //                 'title'=>'Actualiser la grille'
            //             ]),
            //     ],
            //     '{toggleData}'
            // ],

            
            // parameters from the demo form
            //'bordered'=>true,
            //'striped'=>true,
            //'condensed'=>false,
            //'responsive'=>true,
            //'hover'=>true,
            //'showPageSummary'=>$pageSummary,
            //'panel'=>[
            //    'type'=>GridView::TYPE_PRIMARY,
            //    'heading'=>$this->title,
            //],
            //'persistResize'=>false,
            //'exportConfig'=>true,
        ]);
    ?>

  
</div>
