<?php

use yii\bootstrap5\Alert;

/* @var $this yii\web\View */

$this->title = 'suPProf (Gestion)';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Bienvenue !</h1>

        <p class="lead">Interface de gestion pour l'application suPProf</p>

        <p><a class="btn btn-lg btn-success" href="?r=site/gestion">Gestion des tables</a></p>

        <?php 
        
    if(Yii::$app->session->hasFlash('ErreurNiveau')){
    echo Alert::widget([
        'options'=>['class'=>'alert-danger'],
        'body'=>Yii::$app->session->getFlash('ErreurNiveau'),
    ]);
    }

    

    ?>

    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Plateau Logiciel</h2>

                <p>Pour accéder directement l'interface des demandes du plateau logiciel appuyez sur le bouton LOG</p>

                <p><a class="btn btn-default" target="_blank" href="../../../supprof/etudiants/index.php?lstPlateau=LOG&niveau=etudiant">LOG &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Plateau Matériel</h2>

                <p>Pour accéder directement l'interface des demandes du plateau matériel appuyez sur le bouton MAT</p>

                <p><a class="btn btn-default" target="_blank" href="../../../supprof/etudiants/index.php?lstPlateau=MAT&niveau=etudiant">MAT &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Plateau Sytèmes d'exploitation et réseau</h2>

                <p>Pour accéder directement l'interface des demandes du plateau systèmes d'exploitation et réseau appuyez sur le bouton SER</p>

                <p><a class="btn btn-default" target="_blank" href="../../../supprof/etudiants/index.php?lstPlateau=SER&niveau=etudiant">SER &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Enseignants</h2>

                <p>Pour accéder directement l'interface des enseignants pour le traitement des demandes appuyez sur le bouton Enseignant.  
                    Pour accéder cette partie de l'application vous devrez fournir un nom d'usager et un mot de passe.</p>

                <p><a class="btn btn-info" target="_blank" href="../../../supprof/enseignants/index.php">Enseignants &raquo;</a></p>
            </div>            
            <div class="col-lg-4">
                <h2>Gestion des données de l'application</h2>

                <p>Pour accéder l'interface de gestion  des données de l'application suPProf, vous devrez fournir un nom d'usager et un mot de passe.</p>

                <p><a class="btn btn-success" href="?r=site/gestion">Gestion &raquo;</a></p>
            </div>             

            <div class="col-lg-4">
                <h2>Graphes et statistiques</h2>

                <p>Permet de visualiser un graphe des statistiques sur les demandes de support par plateau sous forme de pointe de tarte.</p>
                    
                <p><a class="btn btn-warning" href="?r=site/stats">Stats &nbsp;<i class="glyphicon btn glyphicon-signal"></i>&raquo;</a></p>
            </div>              
            
            
        </div>

    </div>
</div>
