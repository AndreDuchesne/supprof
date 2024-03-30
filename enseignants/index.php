<?php

// établir la connexion avec la base de données DBContacts
require_once("../supadmin/config/connexion.php");


// Ajouter le lien sur le fichier de fonctions fonctions.php
require_once("fonctions.php");


//Initier l'accès db
$db = new Database();

//Initialiser selItem
$_REQUEST['selItem']=(isset($_REQUEST['selItem'])?$db->escapeString($_REQUEST['selItem']):"");

// Vérifier si la session d'un enseignant est débuté
if (!isset($_SESSION['id']) && $_REQUEST['selItem'] != "verifier_usager") {
    // Définir l'option selItem pour affiche le login box
    $_REQUEST['selItem'] = "afficher_login";
}

// Définir la banniére

$menu = "<div id=\"menuhorizontale\"><ul>\n
	<li><a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">Lister les demandes</a></li>\n        
	<li><a onclick=\"clearInterval(objtimer);location='" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message'\" href=\"#\">Ajouter un message</a></li>\n
        <li><a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_demande\">Ajouter une demande</a></li>\n                    
        </ul>
	</div>";




// Définr le message en entéte é partir de la table des messages
$avis = afficher_avis_non_perime();

// Définr le message en entéte é partir de la table des messages
$stylefond = definir_stylefond();



// Vérifier l'option choisie par l'utilisateur
switch ($_REQUEST['selItem']) {


    case "afficher_demandes":
    case "":
        // Afficher le plan de classe
        $plan = "<div id=\"plan\"></div>";
        // Afficher l'accueil
        $main = afficher_demandes();
        break;
    
   case "configurer_profil":

        // Définir le titre du la section Ajouter un lien
        $titre = "Configurer les paramètres du profil";
        // Afficher le formulaire pour ajouter un lien
        $main = configurer_profil();
        break;
    
   case "configurer_profil_db":

        // Définir le titre du la section Ajouter un lien
        $titre = "Configurer les paramètres du profil";
        // Afficher le formulaire pour ajouter un lien
        $main = configurer_profil_db();
        break;
    
    
    case "ajouter_demande":

        // Définir le titre du la section Ajouter un lien
        $titre = "Ajouter une demande de support enseignant(s)";
        // Afficher le formulaire pour ajouter un lien
        $main = ajouter_demande();
        break;

    case "ajouter_demande_db":

        // Définir le titre du la section Ajouter un lien
        $titre = "Ajouter une demande de support enseignant(s)";
        // Afficher le formulaire pour ajouter un lien
        $main = ajouter_demande_db();
        break;

    case "supprimer_demande":

        // Définir le titre du la section Ajouter un lien
        $titre = "Supprimer une demande";
        // Afficher le formulaire pour ajouter un lien
        $main = supprimer_demande($_REQUEST['ID']);
        break;

    case "supprimer_demande_db":

        // Définir le titre du la section Ajouter un lien
        $titre = "Supprimer une demande";
        // Afficher le formulaire pour ajouter un lien
        $main = supprimer_demande_db($_REQUEST['ID'], $_REQUEST['txtCodeUsager']);
        break;


    case "terminer_demande":

        // Définir le titre du la section Ajouter un lien
        $titre = "Terminer une demande";
        // Afficher le formulaire pour ajouter un lien
        $main = terminer_demande($_REQUEST['ID']);
        break;

    case "terminer_demande_db":

        // Définir le titre du la section Ajouter un lien
        $titre = "Terminer une demande";
        // Afficher le formulaire pour ajouter un lien
        $main = terminer_demande_db($_REQUEST['ID'], $_REQUEST['txtCodeUsager']);
        break;

    case "marquer_en_cours":

        // Définir le titre du la section Ajouter un lien
        $titre = "Demande en cours";
        // Afficher le formulaire pour ajouter un lien
        $main = marquer_en_cours($_REQUEST['ID']);
        break;








    case "ajouter_message":

        // Définir le titre du la section Ajouter un lien
        $titre = "Ajouter un message d'avis";
        // Afficher le formulaire pour ajouter un lien
        $main = ajouter_message();
        break;

    case "ajouter_message_db":
 
        // Définir le titre du la section Ajouter un lien
        $titre = "Ajouter un message d'avis";
        // Afficher le formulaire pour ajouter un lien
        $main = ajouter_message_db();
        break;

    case "modifier_message":

        // Définir le titre du la section Ajouter un lien
        $titre = "Modifier le message d'avis";
        // Afficher le formulaire pour ajouter un lien
        $main = modifier_message($_REQUEST['ID']);
        break;

    case "modifier_message_db":

        // Définir le titre du la section Ajouter un lien
        $titre = "Modifier le message d'avis";
        // Afficher le formulaire pour ajouter un lien
        $main = modifier_message_db($_REQUEST['ID']);
        break;

    case "dupliquer_message":

        // Définir le titre du la section Ajouter un lien
        $titre = "<strong style=\"color:orange\">Dupliquer un message d'avis</strong>";
        // Afficher le formulaire pour ajouter un lien
        $main = dupliquer_message($_REQUEST["ID"]);

        //récupérer le dernier ID
        $reponse = $db->select("ID","tblmessages"," 1 ORDER BY ID DESC LIMIT 1",false);
        $ligne = $reponse[0];

        // Réafficher la copie
        $main = modifier_message($ligne["ID"]);
    
        break;        

    case "supprimer_message":

        // Définir le titre du la section Ajouter un lien
        $titre = "Supprimer le message d'avis";
        // Afficher le formulaire pour ajouter un lien
        $main = supprimer_message_db($_REQUEST['ID']);
        break;
    
    case "gerer_inscriptions":
        // Définir le titre du la section Ajouter un lien
        $titre = "Gestion des inscriptions";
        // Afficher le formulaire pour ajouter un lien
        $main = gerer_inscriptions();
        break;
    
    
    
    

    case "generer_ics":
        // Récupérer le message d'avis é partir de SQL
        $reponse = recuperer_avis($db->escapeString($_REQUEST['ID']));
        //$tableau = mysqli_fetch_array($reponse);
        $tableau = $reponse[0];

        // Ajouter le message dans un fichier ics 
        // ICS (start,end,name,description,location)

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
    
    case "exporter_stats":
        exporter_stats();
        break;

    case "rapport_stats":
        rapport_stats();
        break;

   


    case "afficher_login":
     
        // Effacer le mene
        $menu = "";
        // Effacer le plan de classe
        $plan = "";
        //Effacer la ligne des enseignants du plateau
        $profs = "";
        // Afficher l'accueil
        $main = afficher_login();
        break;

    case "verifier_usager":

        // Effacer le mene
        $menu = "";
        // Vérifier l'identité et initialiser la session
        $main = verifier_usager($db->escapeString($_REQUEST['txtUser']), $db->escapeString($_REQUEST['txtPass']));

        break;

    case "terminer_session":

        // Définir le titre du la section Ajouter un lien
        $titre = "Fin de session"; 
        
        //Modifier le status de l'enseignant
        //modifier_status_usager($_SESSION['id'], $_SESSION['Plateau']);

        $reponse = $db->update("user",["status"=>""],"id=".$_SESSION["id"]);
                
        //Terminer la session
        session_destroy();
        session_unset();
        
        // Fermer la connexion au serveur mysqli
        mysqli_close($lien);
        
        // Retourner au formulaire de connexion
        header("location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_login");
        break;
    
  case "terminer_session_prof":

        // Définir le titre du la section Ajouter un lien
        $titre = "Fin de session"; 
        
        //Modifier le status de l'enseignant
        //terminer_session_prof($db->escapeString($_REQUEST['ID']));
        $reponse = $db->update("user",["status"=>""],"id=".$_REQUEST["ID"]);
        //terminer les variables de session

        
        // Retourner au formulaire de connexion
        header("location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes");
        break;
    
    
    
    case "imprimer_inscriptions":
        // Créer le pdf pour liste inscription
        imprimer_inscriptions();
        die();
        break;     
        
    
    
    case "ajax_afficher_demandes":
        // Afficher l'accueil
        $listedemandes = ajax_afficher_demandes();
        break;     
    
    case "ajax_marquer_en_cours":
        // Afficher le formulaire pour ajouter un lien
        $errmsg = ajax_marquer_en_cours($db->escapeString($_REQUEST['ID']));
        // Afficher l'accueil
        $listedemandes = ajax_afficher_demandes();
        break;
    
    

    
    
    
}




//Afficher les contenus des variables        
require_once("affichage.php");
?>
