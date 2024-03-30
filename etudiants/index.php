<?php
// &eacute;tablir la connexion avec la base de donn&eacute;es DBContacts
require_once("../supadmin/config/connexion.php");


// Ajouter le lien sur le fichier de fonctions fonctions.php
require_once("fonctions.php");

//Initier la db
$db = new Database();


// D&eacute;finir la banni&eacute;re
$banniere="<img src=\"images/banniere.png\">";


$menu="<div id=\"menuhorizontale\"><ul>\n
	<li><a href=\"".$_SERVER['PHP_SELF']."?selItem=afficher_demandes\">Lister les demandes</a></li>\n
	<li><a href=\"".$_SERVER['PHP_SELF']."?selItem=ajouter_demande\">Ajouter une demande</a></li>\n
	</ul>
	</div>";    
    
    
// Définr le message en entéte é partir de la table des messages
$avis = afficher_avis_non_perime();

//Définir l'arrière-plan
$stylefond = definir_stylefond();


//Initialiser selItem
$_REQUEST['selItem'] = (isset($_REQUEST['selItem'])?$_REQUEST['selItem']:"");
 
// V&eacute;rifier l'option choisie par l'utilisateur
switch($_REQUEST['selItem'])
    {
        case "afficher_demandes":
        case "":

                    // Afficher l'accueil
                    $main = afficher_demandes();
                    break;
                               
        case "ajouter_demande":
   
                    // D&eacute;finir le titre du la section Ajouter un lien
                    $titre = "Ajouter une demande de support enseignant(s)";
                    // Afficher le formulaire pour ajouter un lien
                    $main = ajouter_demande();
                    break;
		
        case "ajouter_demande_db":
  
                    // D&eacute;finir le titre du la section Ajouter un lien
                    $titre = "Ajouter une demande de support enseignant(s)";
                    // Afficher le formulaire pour ajouter un lien
                    $main = ajouter_demande_db();	    
                    break;
                
		
        case "supprimer_demande":
   
                    // D&eacute;finir le titre du la section Ajouter un lien
                    $titre = "Supprimer une demande";
                    // Afficher le formulaire pour ajouter un lien
                    $main = supprimer_demande(mysqli_real_escape_string($lien,$_REQUEST['ID']));
                    break;
		
        case "supprimer_demande_db":
   
                    // D&eacute;finir le titre du la section Ajouter un lien
                    $titre = "Supprimer une demande";
                    // Afficher le formulaire pour ajouter un lien
                    $main = supprimer_demande_db(mysqli_real_escape_string($lien,$_REQUEST['ID']), mysqli_real_escape_string($lien,$_REQUEST['txtCodeUsager']));	    
                    break;

              
                                
        case "afficher_inscription":
 
                    // D&eacute;finir le titre du la section Ajouter un lien
                    $titre = "<b>Confirmation d'inscription</b>";
                    // Afficher le formulaire pour ajouter un lien
                    $main = afficher_inscription();	    
                    break;                

                
        case "ajouter_inscription_db":
   
                    // D&eacute;finir le titre du la section Ajouter un lien
                    $titre = "Ajouter une demande de support enseignant(s)";
                    // Afficher le formulaire pour ajouter un lien
                    $main = ajouter_inscription_db();	    
                    break;  
                
       case "supprimer_inscription_db":
   
                    // D&eacute;finir le titre du la section Ajouter un lien
                    $titre = "Supprimer une inscription";
                    // Afficher le formulaire pour ajouter un lien
                    $main = supprimer_inscription_db();	    
                    break;                  
                
                
                
                
                
        case "ajaxSetLstBloc":                    
		    // Retourner requête ajax
                    ajaxSetLstBloc(mysqli_real_escape_string($lien,$_REQUEST['cours']));
                    break;                
                
                
        case "ajax_afficher_demandes":  

		    // Retourner requête ajax
                    ajax_afficher_demandes();
                    break;      
                
                
        case "ajaxChkFiche":  
                    $fiche = mysqli_real_escape_string($lien,$_REQUEST['fiche']);
		    // Retourner requête ajax
                    ajaxChkFiche($fiche);                   
                    break;                 
                
                
        case "generer_ics":
                    // Récupérer le message d'avis a partir de SQL
                    $reponse = recuperer_avis($_REQUEST["idEvenement"]);
                    //$tableau = mysqli_fetch_array($reponse);
                    $tableau = $reponse[0]; //test 
                    // Ajouter le message dans un fichier ics 
                    $event = new ICS(
                            $tableau['date_evenement'] . " " . $tableau['debut'], $tableau['date_evenement'] . " " . $tableau['fin'], $tableau['titre'], $tableau['description'], $tableau['local']
                    );
                    //Vider le tampon de sortie
                    ob_clean();
                    // Générer le calendrier
                    $event->show();
                    //Transmet le buffer
                    ob_flush();

                    break;
		
		
    } 
         
//Afficher les contenus des variables        
require_once("affichage.php");

// Refermer la connexion apr&eacute;s affichage
mysqli_close($lien);

?>
