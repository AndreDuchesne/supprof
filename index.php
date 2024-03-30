<?php
// &eacute;tablir la connexion avec la base de donn&eacute;es DBContacts
require_once("./supadmin/config/connexion.php");


// Ajouter le lien sur le fichier de fonctions fonctions.php
require_once("./etudiants/fonctions.php");

//Récupérer la page d'accueil

    //Formuler la requéte pour lire tous les capsules
    $requete = "SELECT * FROM tblplateaux";

    $db = new Database();

    //Transmettre la requéte au serveur mysqli et recevoir la réponse
    $tableau = $db->select('*','tblplateaux','',true);
    



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="fr">
<head>
    
    <title>Liste des demandes de support enseignant(s)</title>
    <link href="styles.css" rel="stylesheet" type="text/css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>

<body>
   <div id="page"> 
    	
	<!--Affichage de la banniere-->
	<div id="NbDemandes"><?php echo (isset($banniere)?$banniere:"") ?></div>

	<?php echo (isset($menu)?$menu:"") ?>   
       
        <!--Affichage de la rubrique sélectionnée-->
        <div class="main">

            <!--Titre de la rubrique-->            
            <div style="text-align:center"> <h1>suPProf-demandes</h1></div>
            <!--Affichage principal-->
            <hr>
		    <p>
			Bienvenue sur le site pour les demandes de support aux enseignants 
			sur les plateaux. Sélectionnez le plateau dans lequel vous
			êtes en cours avant de pouvoir inscrire une demande.
		    </p>

		<div class="accueil">
		<table valign="center" width="100%">
		<?php		

        foreach($tableau as $ligne){
		?>
			<tr class="cellule" style="<?=strtolower($ligne["stylefond"])?>">
				<td>
            	<a href="./etudiants/index.php?lstPlateau=<?=$ligne["plateau"]?>&Niveau=etudiant" target="_blank"><?=$ligne["nomplateau"]?><br/><font size="5">Locaux (<?=$ligne["locaux"]?>)</font></a>
				</td>
		    </tr>
		<?php
		}
		?>
                    
		</table>
		    
			
		</div>
	    <br/>
            <div style="text-align: right" class="liendoc"><a href="./docs/eleves/supprof-etudiants.pdf" target="_blank">Guide de utilisateur<img src="./images/iconedoc.png" /></a></div>
        
    </div>
    

</body>
</html>
