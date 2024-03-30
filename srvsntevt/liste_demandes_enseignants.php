<?php
session_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');


//Initialiser la connexion SQL
require_once '../supadmin/config/connexion.php';



//Définir l'affichage des contacts
global $lien, $BD, $NbDemandes, $banniere, $stats ,$PLATEAU; 

//Vérifier si l'enseignant est connecté sur un plateau
if(!isset($_SESSION['Plateau']))
    //Arrêter le script
    die('Erreur de session');
    




    
//Définir un datapacketb les données de la demande
$rows = array();
$stats = array();
$bloc = array();

//Lire la date et l'heure actuelle
$maintenant = date("Y-m-d", time());

//Formuler la requéte pour lire tous les commentaires visiteur
$clause ="tbldemandes.HeureInscription >='$maintenant' 
          AND (tbldemandes.Etat ='En attente' OR tbldemandes.Etat ='En cours')
          AND (tbldemandes.Plateau = '" . $_SESSION['Plateau'] . "') 
          AND (tbldemandes.idtblblocs = tblblocs.idtblblocs)  
          ORDER BY ID ASC";


$db = new Database();
$lignes = $db->select('tbldemandes.ID, 
                       tbldemandes.NoFiche, 
                       tbldemandes.NomEleve, 
                       tbldemandes.Local, 
                       tbldemandes.Poste, 
                       tbldemandes.TypeDemande, 
                       tbldemandes.HeureDebut, 
                       tbldemandes.Etat, 
                       tbldemandes.Plateau, 
                       tbldemandes.Cours, 
                       tbldemandes.Bloc, 
                       tbldemandes.Question, 
                       tbldemandes.url, 
                       tblblocs.sujet',

                       'tbldemandes,tblblocs',
                       
                       $clause,true);            


        $NbDemandes = count($lignes);

        foreach($lignes as $ligne){
            
            
            //Récupérer UNE ligne d'information dans un tableau associatif
            //Récupérer l'état
            $Etat = $ligne['Etat'];
                        
            // Récupérer le numéro de ID
            $ID = $ligne['ID'];
            


            // Récupérer le numéro de fiche de l'éléve
            $Fiche = $ligne['NoFiche'];

            //Récupérer le nom de l'éléve
            $NomEleve = htmlentities($ligne['NomEleve']);

            // Afficher le nombre de demande
            $NbDemandeEleve = affiche_nombre_demande_eleve($Fiche);
            
            // Récupérer le numéro de local
            $Local = $ligne['Local'];
            
            //Récupérer le numéro du poste
            $Poste = $ligne['Poste'];

            //Récupérer le numéro du poste
            $TypeDemande = $ligne['TypeDemande'];


            //Récupérer le numéro du poste
            $Etat = $ligne['Etat'];
            
            
            //Cours
            $Cours = $ligne['Cours'];
            //Bloc moodle
            $Bloc = $ligne['Bloc'];
            
            //Question 
            $Question = stripcslashes($ligne['Question']);
            //URL
            $url = $ligne['url'];
          
            //sujet
            $sujet = $ligne['sujet'];
            
            //Créer une ligne vide pour cumuler tableau
            $row = array();
            
            //Ajouter la ligne de tableau scalaire      
            array_push($row, $ID, $Fiche,$NomEleve,$NbDemandeEleve,$Local,$Poste,$Etat,$Cours,$Bloc,$Question,$url,$sujet,$TypeDemande);            
            
            //Ajouter la ligne dans le tableau
            array_push($rows, $row);
            
            
        }         
        
        
    // Afficher les statistiques journaliéres
    $stats = ajax_calculer_stats_jour();
    

    //Créer le datapacket pour transmettre à la fonction javascript
    $bloc['demandes']=$rows;
    $bloc['stats']=$stats;
    $bloc['NbDemande']=$NbDemandes;
    $bloc['plateau']=$_SESSION['Plateau'];
            





    /******************************************************
     * 
     *  CRÉER LISTE DE NOM DES ENSEIGNANTS DU PLATEAU
     * 
     * 
     * 
     * 
     */
    //Initialiser le tampon enseignants
    $profs = array();
    $enseignant = array();
    $enseignants = array();
    
    
    //Vérifier si le plateau par défaut est sélectionné
    if(!isset($PLATEAU))
        $PLATEAU = $_SESSION['Plateau'];
    

    $clause ="status='".$PLATEAU."'";

    $db = new Database();
    $enseignants = $db->select('*','user',$clause,true);

   
        
     //Boucler pour lire tous les enseignants
     //while($enseignant = mysqli_fetch_array($reponse)){ 
     foreach($enseignants as $enseignant){   
         //empiler l'enseignant dans le tableau
         array_push($profs, ['prenom'=> $enseignant['prenom'],
                             'nom'=> $enseignant['nom'],
                             'email'=> $enseignant['email']]);
     }






     /***********************************************
      ************** AFFICHAGE SSE *****************
      **********************************************/

    echo "data: " . json_encode($bloc)   . "\n\n";    
    
    echo "data:profs " . json_encode($profs)   . "\n\n";

    flush();
    //}
    
    
    
    



























    
    
    
    
    
    
    
/**
 * Fonction pour calculer le nombre de demande d'un éléve dans la journée
 * 
 * 
 * 
 * 
 */
function affiche_nombre_demande_eleve($fiche) {


    //Définir l'affichage des contacts
    global $lien, $BD, $NbDemandes, $banniere, $stats;

    //Lire la date et l'heure actuelle
    $maintenant = date("Y-m-d", time());

        $clause = "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`) <=0) AND NoFiche=$fiche";
        $db = new Database();
        $colonnes = $db->select('NoFiche, COUNT(`ID`) AS nbdemande','tbldemandes',$clause,true);
        // Récupérer le nombre de demande de l'éléve
        //$colonne = mysqli_fetch_array($reponse);
        foreach($colonnes as $colonne){
        // Retourner le nombre de demande
        return $colonne['nbdemande'];
    }
}    
    




















/**
 * 
 *  FONCTION CALCULER LES STATISTIQUES GLOBALE DU JOUR
 * 
 * 
 * 
 * 
 * 
 */
function ajax_calculer_stats_jour() {
    // Recouvrir les valeurs de connexions
    global $lien, $BD, $titre;

    //Tableau des statistiques
    $stats = array();

    
    $clause = "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`)) =0 AND Plateau='" . $_SESSION['Plateau'] . "' AND Etat = 'Termine'";
    $db = new Database();
    $colonnes = $db->select('COUNT(`ID`) AS nbdemande','tbldemandes',$clause,true);
    foreach($colonnes as $colonne){
        $nbtotaldemande = $colonne['nbdemande'];
    }



        $clause = "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`) =0)  
                    AND TIMESTAMPDIFF(SECOND,`HeureDebut`,`HeureFin`)<>0
                    AND `Etat` LIKE '%Termin%' AND Plateau='" . $_SESSION['Plateau'] . "'";

        $db = new Database();
        $colonnes = $db->select('TIMESTAMPDIFF(SECOND,`HeureDebut`,`HeureFin`) as duree','tbldemandes',$clause,true);

        // Boucler pour cumuler la durée total et la durée moyenne
        foreach($colonnes as $colonne){
            // Récupérer le nombre de demande total
            $totalduree += $colonne['duree'];
        }
        // Lire le nombre total de demande terminée
        $nbdemandetermine = count($colonnes);

        // Vérifier si aucune demande
        if ($nbdemandetermine == 0)
        // Assurer moyenne = /1
            $nbdemandetermine = 1;


        // Lire la durée total en seconde des demandes
        $time = $totalduree;
        // Calculer les durées en heure, minute secondes
        $seconds = $time % 60;
        $time = ($time - $seconds) / 60;
        $minutes = $time % 60;
        $hours = ($time - $minutes) / 60;
   // }
    
    //Créer duretotal en hh:mm:ss
    $dureetotale =  date("H:i:s", strtotime($hours . ":" . $minutes . ":" . $seconds));

    //Créer durée moyenne format 1 dédimale
    $dureemoyenne = sprintf("%0.1f", ($totalduree / $nbdemandetermine));
    
    //Créer le tableau statistique
    array_push($stats, $nbtotaldemande, $dureetotale ,$dureemoyenne);

    // Retourner les statistiques du jour
    return $stats;
}

?>