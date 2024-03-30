<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

    /****************************************************************
     * 
     *    GENERER LA LISTE DES DEMANDES 
     * 
     * 
     * 
     * 
     * 
     ****************************************************************/

// Réatblir la connexion DB
require_once '../supadmin/config/connexion.php';

//Définir l'affichage des contacts
global $lien, $BD, $NbDemandes, $banniere, $stats ,$PLATEAU; 
  

//Lire le plateau
$PLATEAU = $_REQUEST['PLATEAU'];    
    
    
    
//Définir un datapacketb les données de la demande
$rows = array();



//Lire la date et l'heure actuelle
$maintenant = date("Y-m-d", time());


$clause ="HeureInscription >='$maintenant' 
	    AND (Etat ='En attente' OR Etat ='En cours')
	    AND tbldemandes.Plateau = '$PLATEAU'  
	    ORDER BY ID ASC";


$db = new Database();
$lignes = $db->select('ID,NomEleve,Local,Poste,TypeDemande,HeureDebut,Etat','tbldemandes',$clause,true);

        
        //Boucler pour chaque ligne allant de 1 jusqu'é nombre de ligne
        $NbDemandeEleve = count($lignes);
        foreach($lignes as $ligne){
            
            
            //Récupérer UNE ligne d'information dans un tableau associatif
            //$ligne = mysqli_fetch_assoc($reponse);

            // Récupérer le numéro de ID
            $ID = $ligne['ID'];

   
            
            // Récupérer le numéro de fiche de l'éléve
            $Fiche = $ligne['NoFiche'];
            
            //Récupérer le nom de l'éléve
            $NomEleve = htmlentities($ligne['NomEleve']);
            
            // Récupérer le numéro de local
            $Local = $ligne['Local'];
            
            // Récupérer le numéro de local
            $TypeDemande = $ligne['TypeDemande'];

            //Récupérer le numéro du poste
            $Poste = $ligne['Poste'];

            //Récupérer le numéro du poste
            $Etat = $ligne['Etat'];
                          
                
            //Créer une ligne vide pour cumuler tableau
            $row = array();
            
            //Ajouter la ligne de tableau scalaire      
            array_push($row, $ID, $Fiche,$NomEleve,$NbDemandeEleve,$Local,$Poste,$Etat,$TypeDemande);            
               
            //Ajouter la ligne dans le tableau
            array_push($rows, $row);
            
            
        }
    
    //Créer le datapacket pour transmettre à la fonction javascript
    $bloc['demandes']=$rows;
    $bloc['NbDemande']=$NbDemandeEleve;
    $bloc['plateau']=$PLATEAU;
       

    
    
    
    
    
    /****************************************************************
     * 
     *    GENERER LES MESSAGES DE LA BANNIERE
     * 
     * 
     * 
     * 
     * 
     ****************************************************************/
    $clause = "date_evenement >= '".date("Y-m-d")."' ORDER BY date_evenement DESC";
    $db = new Database();
    $reponse = $db->select('*','tblmessages',$clause,true);

    
    
        //Initialiser les tableaux pour l'affichage de la bannière
        $messages = array();
        //Initisaliser le checksum
        $chksum=0;
        
        //Récupérer le message d'avis rajouter les boutons
       // while($message = mysqli_fetch_array($reponse)){
         foreach($reponse as $message){   
         
            
            //SI délaiaffichage = 0 ALORS
            if(intval($message['delaiaffichage'])==0){
                
             //Calculer le checksum des bannières
            $chksum += $message['ID'];   
            
            //Ajouter la ligne dans la bannière
            array_push($messages, array(
                                        'ID'=>$message['ID'],
                                        'description'=>$message['description'],
                                        'date_evenement'=>$message['date_evenement'],
                                        'debut'=>date("H:i",  strtotime($message['debut'])),
                                        'fin'=>date("H:i",  strtotime($message['fin'])),
                                        'message'=>$message['titre'])
                                        );
            }
            else {
            
                 //  Lire le time stampordi
                $h = time();
                //  Composer le time stampmessage de l'événement avec la date et l'heure de début
                $t = strtotime($message['date_evenement']." ".$message['debut']);
                //  Soustraire du time stampmessage avec délai d'affichage
                $t = $t - intval($message['delaiaffichage']);                
                //  SI le time stampordi >= timstampmessage ALORS
                if($h >= $t){    
                    
                //Calculer le checksum des bannières
                $chksum += $message['ID'];   

                //Ajouter la ligne dans la bannière
                array_push($messages, array(
                                            'ID'=>$message['ID'],
                                            'description'=>$message['description'],
                                            'date_evenement'=>$message['date_evenement'],
                                            'debut'=>date("H:i",  strtotime($message['debut'])),
                                            'fin'=>date("H:i",  strtotime($message['fin'])),
                                            'message'=>$message['titre'])
                                            );                    
                }
            }
        
        }

    
    //Ajouter le checksum dans le message banniere
    array_push($messages, array("checksum"=>$chksum));
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
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

     
    
     
   
    //***************************************************** SORTIE DU MESSAGE SSE *****************************************//
    
    
    
    
    //Transmettre les demandes vers l'affichage
    echo "data: " . json_encode($bloc)   . "\n\n";

       
    
    //Transmettre la banniere vers l'affichage
    echo "event:banner\n";
    echo "data: " . json_encode($messages) . "\n\n" ;


    
    //Transmettre le nom des enseignants du plateau
    echo "event:profs\n";
    echo "data: " . json_encode($profs)   . "\n\n";   

    

    flush();
    //}
?>