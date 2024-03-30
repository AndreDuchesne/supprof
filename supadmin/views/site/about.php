<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;



 

$this->title = 'À Propos';
$this->params['breadcrumbs'][] = ['label'=>'Gestion','url'=>'?r=site/gestion'];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>
    <h3>Interface de gestion pour l'application web suPProf</h3>
    
    
    <p>    
    Le système suPProf comprend trois niveaux d'accès basé sur le rôle de l'utilisateur. 
    suPProf (gestion) est l'interface principale de gestion pour les administrateurs. 
    Cette interface permet de modifier directement le contenu des différentes tables du système.
    Pour connaître les fonctions principales consulter le guide d'utilisation pour les responsables 
    de plateau.
    </p>
    
    <div class="paragraphe">
    <h4 class="bright">suPProf (Gestion)</h4>
     Guide d'utilisation pour les responsables de plateau. 
    <br/><br/>
    <?php
    $url = Url::to('/supprof/docs/admins/supprof-administrateur.pdf');
    ?>
    <a href="<?= $url ?>" target="_blank"> Guide d'utilisation - Responsables de plateau</a>    
    </div>
    
    
    <div class="paragraphe"> 
    <h4>suPProf (Enseignants)</h4>
       
    Guide d'utilisation pour les enseignants. 
    
    <br/><br/>
    <?php
    $url = Url::to('/supprof/docs/enseignants/supprof-enseignants.pdf');
    ?>
    <a href="<?= $url ?>" target="_blank"> Guide d'utilisation - Enseignants</a>    
    </div>
    
    
    
    
    
    <div class="paragraphe"> 
    <h4>suPProf (Étudiants)</h4>
       
    Guide d'utilisation pour les étudiants. 
    
    <br/><br/>
    <?php
    $url = Url::to('/supprof/docs/eleves/supprof-etudiants.pdf');
    ?>
    <a href="<?= $url ?>" target="_blank"> Guide d'utilisation - Étudiants</a>    
    </div>    

</div>


