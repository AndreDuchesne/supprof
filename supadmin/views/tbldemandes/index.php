<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use app\models\Tbldemandes;

//use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TbldemandesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tbldemandes');
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
                url : \'index.php?r=tbldemandes/multiple-delete\',
                data : {row_id: HotId},
                success : function() {
                    $(this).closest(\'tr\').remove(); //or whatever html you use for displaying rows
                    }
                });
            }
        });
        




        
    });', \yii\web\View::POS_READY);

?>



<div class="tbldemandes-index">

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
                'ID',
                'NomEleve',
                'Local',
                'Poste',
                'HeureInscription',                
                'HeureDebut',
                'HeureFin',
                'Etat',
                'TypeDemande',                
                'Plateau',   
                'Cours',
                'Bloc',   
                [
                    'class' => ActionColumn::className(),
                    'urlCreator' => function ($action, Tbldemandes $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'id' => $model->ID]);
                     }
                ],                             
                
                ],
            
           
        ]);
    ?>
    
    
</div>
