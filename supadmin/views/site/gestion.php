<?php
use yii\helpers\Html;
use yii\bootstrap5\Alert;


/* @var $this yii\web\View */

$this->title = 'suPProf (Gestion)';



?>

<?php if(!Yii::$app->user->isGuest):?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Gestion</h1>

        <p class="lead">Gestion des données de l'application suPProf</p>

    </div>
    

    <div class="body">

        <div style="text-align: left">
        <table class='table table-striped'>
            
           <tr class='row'>
                <td> <?= Html::a('<span class="glyphicon glyphicon-user btn btn-default btn-success" style="width: 160px"> Tblusagers </span>', ['tblusagers/index']) ?></td>
                <td class="text-left">Gestion de la table des utilisateurs</td>
            </tr>            
            
            <tr class='row'>
                <td> <?= Html::a('<span class="glyphicon glyphicon-bullhorn btn btn-default btn-success" style="width: 160px"> Tbldemandes </span>', ['tbldemandes/index']) ?></td>
                <td class="text-left">Gestion des demandes de support et exportation des données vers chiffrier</td>
            </tr>

       
            <tr class='row'>
                <td> <?= Html::a('<span class="glyphicon glyphicon-blackboard btn btn-default btn-success" style="width: 160px"> Tblmessages </span>', ['tblmessages/index']) ?></td>
                <td class="text-left">Gestion des détails des messages pour l'affichage de la banière annonce des capsules de formation</td>
            </tr>
                    
            
            <tr class="row">
                <td>
                      <?= Html::a('<span class="glyphicon glyphicon-link btn btn-default btn-success" style="width: 160px"> Tblblocs</span>', ['tblblocs/index']) ?>
                </td>
                <td class="text-left">
                Gestion de la table des blocs de cours pour définir les numéros ainsi que les titres 
                de bloc et les liens a établir avec la plate-forme Moodle.
                </td>
            </tr>
            
            <tr class='row'>
                <td> <?= Html::a('<span class="glyphicon glyphicon-briefcase btn btn-default btn-success" style="width: 160px"> Tblcours</span>', ['tblcours/index']) ?> </td>
                <td class="text-left">Gestion des cours, titre, numéro et plateau</td>
            </tr>


            <tr class='row'>
                <td> <?= Html::a('<span class="glyphicon glyphicon-check btn btn-default btn-success" style="width: 160px"> Tblinscriptions </span>', ['tblinscriptions/index']) ?></td>
                <td class="text-left">Gestiond des inscriptions aux capsules de formation</td>
            </tr>
             
            
            
        
            <tr class='row'>
                <td> <?= Html::a('<span class="glyphicon glyphicon-tag btn btn-default btn-success" style="width: 160px"> Tblpostes </span>', ['tblpostes/index']) ?></td>
                <td class="text-left">Gestion de la numérotation des postes de travail sur les plateaux</td>
            </tr>
 
             

            <tr class='row'>
                <td> <?= Html::a('<span class="glyphicon glyphicon-tags btn btn-default btn-success" style="width: 160px"> Tblplateaux </span>', ['tblplateaux/index']) ?></td>
                <td class="text-left">Gestion de l'identification des plateaux</td>
            </tr>
 
            
        

        </table>
            
        </div>
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



