<?php
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
use miloschuman\highcharts\HighchartsAsset;

HighchartsAsset::register($this)->withScripts(['highcharts', 'modules/exporting', 'modules/drilldown']);

/* @var $this yii\web\View */

$this->title = 'suPProf (Stats)';

?>




<?php if(!Yii::$app->user->isGuest):?>


<div class="site-index">



    <div class="body center-block">
                
        <!--   
        
           D R I L L D O W N 
        
        -->
            <?php
                
                echo Highcharts::widget([
                       'options' => [
                           
                            'chart'=> ['type'=> 'pie'],
                           
                            'credits'=>['enabled'=>false],
                           
                            'title' => ['text' => 'Programme Soutien Informatique'],
                           
                            'subtitle' => ['text' => 'Demandes par plateau et compétence'],                           
                           
                            'plotOptions'=> [
                                        'series'=>[
                                            'dataLabels'=> [
                                                'enabled'=>true,
                                                'format'=> '{point.name}: {point.y:.0f}'
                                                ]
                                            ]
                                        ],
                           
                            'tooltip'=>[
                                        'headerFormat'=>'<span style="font-size:11px">{series.name}</span><br>',
                                        'pointFormat'=>'<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>',                                
                            ],
                           
                           
                    'series'=> [[
                        'name'=> 'SoutienInformatique',
                        'colorByPoint'=>true,
                        'data'=> $plateaux
                                
                        ]],
                           
                           
                    'drilldown'=>[
                        'series'=>$competences
                        ]
                    ],
                ]);
                ?>        
        
        
        
  
    </div>
    
</div>

<?php else:?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Erreur !</h1>

        <p class="error-summary">
            Vous n'avez pas l'autorisation d'accès dans cette section.  Vous devez fournir un nom
            d'usager et un mot de passe valide.
        </p>

    </div>

    <div class="body-content">
        
    </div>

</div>

<?php endif; ?>



