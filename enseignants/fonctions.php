<?php

/* * ****************************************
 * 					  *
 *       LIBRAIRIE DES FONCTIONS          *
 *  					  *
 * **************************************** */

/**
 *
 *  FONTION PRINCIPALE POUR AFFICHER LA LISTE DES DEMANDES
 *
 * */
//Fonction pour afficher la liste des contacts
function afficher_demandes() {
    
    //Initialiser la variable main
    $main = (isset($main)?$main:"");

    //Définir l'affichage des contacts
    global $lien, $BD, $NbDemandes, $banniere, $stats;

    //Lire la date et l'heure actuelle
    $maintenant = date("Y-m-d", time());

    $db = new Database();
    $reponse = $db->select("tbldemandes.ID, 
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
                            tbldemandes.CodeUsager,
                            tblblocs.sujet",

                            "tbldemandes, tblblocs",

                            "(tbldemandes.HeureInscription >='".$maintenant."') 
                            AND (tbldemandes.Etat ='En attente' OR tbldemandes.Etat ='En cours') 
                            AND (tbldemandes.Plateau = '".$_SESSION['Plateau']."') 
                            AND (tbldemandes.idtblblocs = tblblocs.idtblblocs) 
                            ORDER BY ID ASC",

                            true);


        //Lire le nombre de ligne obtenues dans la réponse du serveur mysqli 
        $nbligne = count($reponse);

        // Afficher le nombre de demande en cours
        $banniere = $_SESSION['Plateau'] . "(" . $nbligne . ")";

        // Afficher les statistiques journaliéres
        $stats = calculer_stats_jour();


        // Afficher le nombre de demandes dans le titre de page
        $NbDemandes = $nbligne;


      //Affiche le plan de la classe en svg
        $main .= "<div id=\"plan\" class=\"svg-view\">
				    <iframe id=\"planloc\" name=\"planloc\" width=510 height=500  frameborder=0  marginwidth=0 marginheight=0 scrolling=no allowtransparency=\"false\" style=\"background:#ffffff;\" srcdoc='".htmlspecialchars(afficher_plan($_SESSION['Plateau']))."'>
                    </iframe> 
			      </div>";
        

		
        //Débuter la création du formulaire
		$main.= "<div id=\"listedemandes\">
				<form name=\"frmDemandes\" method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "?selItem=terminer_demande\">\n";

        //Débuter l'affichage du tableau des demandes
        $main .= "<table border=\"0\" class=\"ListeDemandes\">";

        // Affiche la ligne titre du tableau
        $main .= "<tr class=\"LigneTitre\">
                        <td class=\"lienref\">Réf.</td>
                        <td class=\"Nom\">Nom </td>
                        <td class=\"Poste\">Poste</td>
                        <td class=\"Operations\">Op&eacute;rations</td>			
                    </tr><tbody id=\"coreLstDemandes\">";
        
        
        
        

         
        //Boucler pour chaque ligne allant de 1 jusqu'é nombre de ligne
            //Récupérer UNE ligne d'information dans un tableau associatif
            foreach($reponse as $ligne){
            // Récupérer le numéro de ID
            $ID = $ligne['ID'];
            
            // Récupérer l'état de la demande
            $Etat = $ligne['Etat'];            
            
            // Récupérer le numéro de local
            $Local = $ligne['Local'];

            // Récupérer le numéro de fiche de l'éléve
            $Fiche = $ligne['NoFiche'];

            // Afficher le nombre de demande
            $NbDemandeEleve = affiche_nombre_demande_eleve($Fiche);

            //Récupérer le nom de l'éléve
            $NomEleve = htmlentities($ligne['NomEleve']);

            //Récupérer le type de demande
            $TypeDemande = htmlentities($ligne['TypeDemande']);


 	    //Vérifier si le local est A-122
	    if($Local=="A-122"){
		//Ajouter un fond rouge
		$NomEleve = "<span style=\"background-color:red\">".$NomEleve."</span>";
        $TypeDemande = "Examen";
	    }
        

            //Récupérer le numéro du poste
            $Poste = $ligne['Poste'];
            
            //Récupérer le cours 
            $Cours = $ligne['Cours'];
            
            //Récupérer le numéro du bloc
            $Bloc = $ligne['Bloc'];
            
            //Récupérer la question
            $Question = stripcslashes($ligne['Question']);
            
            //Récupérer l'url de la demande
            $url = $ligne['url'];

            //Récupérer le sujet du bloc
            $sujet = $ligne['sujet'];
            
            
            
            // *********** Débuter affichage d'une demande
             
            
            //Vérifie si c'est la premiére 
            if ($ligne['Etat'] == "En cours") {
                // Effacer le style pour les demandes en cours
                $styledemande = " class=\"EnCours " . $ligne['CodeUsager'] . "\"";

            } elseif ($ligne['Etat'] == "En attente") {
                // Appliquer un style pour la demande en attente
                $styledemande = " class=\"EnAttente\ " . $ligne['CodeUsager'] . "\"";
            } else {
                // Enlever le style par défaut
                $styledemande = " class=\"". $ligne['CodeUsager'] . "\"";
            }





            // Ajouter le style de de rangée
            $main .= "\n<tr $styledemande>\n";

            //Ajouter le lien référence à la demande dans moodle
            $main .= "<td><a href=\"".$url."\" target=\"moodle\">
                        <img title=\"Ouvrir un URL\" class=\"IconeURL\" src=\"images/url.png\">
                      </a></td>";                               
            
            //Afficher le nom de l'éléve
            $main .= "<td class=\"Nom\" onclick=\"javascript:marquer_en_cours($ID)\">$NomEleve
                        <span class=\"nbdemande\">$NbDemandeEleve</span>
                        <span class=\"".$TypeDemande."\">$TypeDemande</span>
                        <br/>";
            $main .= "<font size=\"-1\">($Cours) #$Bloc " .substr($sujet,0,15) ." <br/>$Question </font></td>\n";

            //Afficher le numéro de la demande
            $main .= "<td class=\"Poste\"><b><font size=\"3\">$Poste</font><br/><font size=\"3\">$Local</font></b></td>\n";

            //Afficher les icénes pour la gestion des demandes
            $main .= "<td class=\"Operations\">";

            // Afficher trois boutons sur la premiére ligne 

            $main .= "<a href=\"#\" onclick=\"confirmer_terminer_demande(". $ID . ")\">
                          <img title=\"Marquer une demande terminée\" class=\"IconeTerminer\" src=\"images/terminer.png\"/>
                      </a>&nbsp;&nbsp;
			    		
                      <a href=\"index.php?selItem=supprimer_demande&ID=" . $ID . "\">
                          <img title=\"Annuler une demande\" class=\"IconeSupprimer\" src=\"images/supprimer.png\"/>
                      </a>";

            $main .= "</td>";

            //Afficher la fin du bloc lien
            $main .="\n</tr>\n";

           
            // ********* Terminer l'affichage de la liste de liens
        }
        
        
        //Refermer le tableau de la liste
        $main .= "</tbody></table>";
        

        //Refermer le formulaire
        $main .= "</form>
                </div>\n";

    //}



//Retourner le tableau des contacts
    return $main;
}




/**
 * 
 * Fonction pour récupérer le plan svg à partir de la 
 * base de données
 *
 * 
 */
function afficher_plan($plateau){

    global $lien, $DB;


    $db = new Database();
    $reponse = $db->select("tblplateaux.plan","tblplateaux","plateau = '".$plateau."'",true);

    //Vérifier si un plan de classe existe 
    $nbplan = count($reponse);
    if($nbplan>0){
        foreach($reponse as $plan){
        return $plan['plan'];
        }
    }else{
        return "<h1>Erreur plan introuvable</h1>";
    }

}











/**
 *
 * 
 * 
 * 
 * 
 * 
 * 
 *  AJAX pour récupérer les demandes en arrière-plan
 * 
 *  FONTION PRINCIPALE POUR AFFICHER LA LISTE DES DEMANDES
 *
 * 
 *
 * 
 * 
 * 
 * 
 * * */
//Fonction pour afficher la liste des contacts
function ajax_afficher_demandes() {

    //Définir l'affichage des contacts
    global $lien, $BD, $NbDemandes, $banniere, $stats; 

    
    //Définir un datapacketb les données de la demande
    $rows = array();
    $stats = array();
    $bloc = array();
    $profs = array();

    //Lire la date et l'heure actuelle
    $maintenant = date("Y-m-d", time());

    $db = new Database();
    $reponse = $db->select("tbldemandes.ID, 
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
                            tbldemandes.CodeUsager,
                            tbldemandes.url,
                            tblblocs.sujet",

                            "tbldemandes, tblblocs",

                            "(tbldemandes.HeureInscription >='".$maintenant."') 
                            AND (tbldemandes.Etat ='En attente' OR tbldemandes.Etat ='En cours') 
                            AND (tbldemandes.Plateau = '" . $_SESSION['Plateau'] . "')     
                            AND (tbldemandes.idtblblocs = tblblocs.idtblblocs)                          
                            ORDER BY ID ASC",

                            true);

        //Lire le nombre de ligne obtenues dans la réponse du serveur mysqli 
        $NbDemandes = count($reponse);         
        
        //Boucler pour chaque ligne allant de 1 jusqu'é nombre de ligne
       // for ($i = 1; $i <= $NbDemandes; $i++) {            
       foreach($reponse as $ligne){     


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

            //Récupérer le type de demande
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
            
            $usernameProf = $ligne['CodeUsager'];
            
            //Créer une ligne vide pour cumuler tableau
            $row = array();
            
            //Ajouter la ligne de tableau scalaire      
            array_push($row, $ID, $Fiche,$NomEleve,$NbDemandeEleve,$Local,$Poste,$Etat,$Cours,$Bloc,$Question,$url,$sujet,$usernameProf,$TypeDemande);            
            
            //Ajouter la ligne dans le tableau
            array_push($rows, $row);
            
            
        }              
    // Afficher les statistiques journaliéres
    $stats = ajax_calculer_stats_jour();
    
    //Récupérer le nom des enseignants du plateau
    $profs = ajax_identifier_profs_plateau($_SESSION['Plateau']);
    
    //Créer le datapacket pour transmettre à la fonction javascript
    $bloc['demandes']=$rows;
    $bloc['stats']=$stats;
    $bloc['NbDemande']=$NbDemandes;
    $bloc['plateau']=$_SESSION['Plateau'];
    $bloc['profs']=$profs;
            
    //Retourcer le packet de donnée
    die (json_encode($bloc));
    //}
}















/**
 * Fonction pour calculer le nombre de demande d'un éléve dans la journée
 * 
 * 
 * 
 * 
 */
function affiche_nombre_demande_eleve($Fiche) {


    //Définir l'affichage des contacts
    global $lien, $BD, $NbDemandes, $banniere, $stats;

    //Lire la date et l'heure actuelle
    $maintenant = date("Y-m-d", time());

    $db = new Database();
    $reponse = $db->select("NoFiche, COUNT(`ID`) AS nbdemande",
                           "tbldemandes",
                           "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`) <=0) AND NoFiche=$Fiche",true);
    //Récupérer la ligne
    $colonne = $reponse[0];


        // Retourner le nombre de demande
        return $colonne['nbdemande'];
    //}
}


















/**
 *
 * Afficher la page de connexion au systéme
 *
 *
 * * */
function afficher_login() {


    //Définir l'affichage des contacts
    global $lien, $BD;

    $db = new Database();
    $reponse = $db->select("plateau, nomplateau","tblplateaux","");

   
            //Lire le nombre de ligne obtenues dans la réponse du serveur mysqli 
            
            $nbplateaux = count($reponse);

            $listePlateaux = "";

            //Boucler pour chaque ligne allant de 1 jusqu'é nombre de ligne
            //for ($i = 1; $i <= $nbplateaux; $i++) {    
            foreach($reponse as $colonne){
                        // Récupérer le nombre de demande de l'éléve
                        //$colonne = mysqli_fetch_array($reponse);

                        $listePlateaux .="<option value=\"".$colonne['plateau']."\">".$colonne['nomplateau']."</option>\n";
            }
    //}
  
    
    // Afficher un message de bienvenue pour les enseignants
    $main = "<script>clearInterval(objtimer);</script><h1>suPProf - enseignants</h1>
		<hr><br/>
		Bienvenue dans l'application pour la gestion des demandes de support.
		Veuillez entrer un nom d'usager et un mot de passe valide.<br/><br/>
		<img style=\"float:right;width:200px\" src=\"images/terminer.png\"/><br/>";



    // Créer un tableau pour une boéte de connexion avec le champ user et pass
    $main .= "<div class=\"loginbox\">
		\n<form name=\"frmLogin\" action=\"" . $_SERVER['PHP_SELF'] . "?selItem=verifier_usager\" method=\"post\">
		<table>
		<tr>
		    <td>
			Nom d'usager :
		    <td>
		    <td>
			<input type = \"text\" name=\"txtUser\">
		    </td>
		</tr>
		<tr>
		    <td>
			Mot de passe :
		    <td>
		    <td>
			<input type = \"password\" name=\"txtPass\">
		    </td>
		</tr>
		<tr>
		    <td>
			Plateau :
		    <td>
		    <td>
			<select name=\"lstPlateau\">
                                ".$listePlateaux."
			</select>
		    </td>
	
		<tr>
		    <td colspan=\"2\">
			<input type = \"submit\" name=\"cmdLogin\" value=\"CONNECTER\">
		    </td>
		</tr>
		</table>
		</form>
	    </div>
<br/>
<br/>
<div style=\"text-align: right\" class=\"liendoc\"><a href=\"../docs/enseignants/supprof-enseignants.pdf\" target=\"_blank\">Guide de utilisateur<img src=\"./images/iconedoc.png\" /></a></div>

";

// Retourner l'affichage
    return $main;
}






















/**
 *
 *   AFFICHER FORMULAIRE POUR UNE NOUVELLE DEMANDE
 *
 * */
//Fonction pour ajouter un contact é l'aide d'un formulaire
function ajouter_demande() {
// Recouvrir les variables globales
    global $LOCAUX;



    //Construire la liste des plateaux
    $db = new Database();
    $reponse = $db->select("plateau, nomplateau","tblplateaux","");

    $nbplateaux = count($reponse);

    $listePlateaux = "";

    //Boucler pour chaque ligne allant de 1 jusqu'é nombre de ligne
    //for ($i = 1; $i <= $nbplateaux; $i++) {    
    foreach($reponse as $colonne){
                // Récupérer le nombre de demande de l'éléve
                //$colonne = mysqli_fetch_array($reponse);

                $listePlateaux .="<option value=\"".$colonne['plateau']."\">".$colonne['nomplateau']."</option>\n";
    }







    //Ajouter un nouveau contact dans un formulaire
    $main = "<div class=\"ListeDemandes\">
	     <form name=\"frmAjouter\"  action=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_demande_db\" method=\"POST\">\n
                <fieldset>
			<legend>Détail de la demande</legend>
			<table class=\"FormAjout\">
			<tr>
			    <td>
			    Nom ou texte :
			    </td>
			    <td>
			    <input list=\"liste\" type=\"text\" size=\"30\" name=\"txtNom\">
                                  <datalist id=\"liste\">
                                    <option value=\"SALLE EN EVALUATION\">
                                    <option value=\"SUPPORT EN LOGICIEL\">
                                    <option value=\"SUPPORT EN MATÉRIEL\">
                                    <option value=\"SUPPORT EN RÉSEAU\">
                                  </datalist>
			    </td>
			</tr>



                        <tr>
                            <td>
                                Destination du plateau :
                            <td>
                            <td style=\"margin-left:0px;\">
                                <select name=\"lstPlateau\">
                                $listePlateaux
                                </select>
                            </td>

                        <tr>
                        
                        <tr>
                            <td colspan=\"2\" style=\"text-align:right\">
                              <br/><br/><br/><input type=\"submit\" class=\"cmdBouton\" name=\"cmdDemande\" value=\"Ajouter\">
                            </td>

                        <tr>
                        
                              

	    </table>
		\n
		<div  class=\"retour\">
		<a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
		<img src=\"images/retour.png\"/><br/>Retour</a>
		</div>
	    </fieldset>   
	  </form>
	  </div>";

//Retourner l'affichage du contact
    return $main;
}













/**
 *
 *   AFFICHER FORMULAIRE POUR CONFIGURER PROFIL
 *
 * */
//Fonction pour ajouter un contact é l'aide d'un formulaire
function configurer_profil() {
// Recouvrir les variables globales
    global $LOCAUX,$lien,$BD;
        
//Recouvrir variable de connexion
    global $lien, $BD;

    $db = new Database();
    $reponse = $db->select("*","user","id='".$_SESSION["id"]."'");


    //Récupérer la ligne de config
    $ligne = $reponse[0];
    

    //Ajouter un nouveau contact dans un formulaire
    $main = "<div class=\"ListeDemandes\">
	     <form id=\"frmConfigurerProfil\" name=\"frmConfigurerProfil\" onSubmit=\"validate(); return false;\" method=\"post\">\n
                <fieldset>
			<legend>Paramétres du profil</legend>
			<table class=\"FormProfil\">

			<tr>
			    <td>
			    Nom :
			    </td>
			    <td>
			    ".$ligne["prenom"]." ".$ligne["nom"]."
                                <input type=\"hidden\" name=\"txtNomComplet\" value=\"".$ligne["prenom"]." ".$ligne["nom"]."\"/>
			    </td>
			</tr>

			<tr>
			    <td>
			    Nom d'usager :
			    </td>
			    <td>
			    <input type=\"text\" size=\"15\" id=\"txtUser\" name=\"txtUser\" onblur=\"validate_username()\" value=\"".$ligne["username"]."\"><span class=\"msgerreur\" id=\"resultusername\"></span>
			    </td>
			</tr>
                        
			<tr>
			    <td>
			    Mot de passe :
			    </td>
			    <td>
			    <input type=\"password\" size=\"15\" id=\"txtPass\" name=\"txtPass\" onblur=\"validate_pass()\" value=\"".$ligne["password"]."\"><span class=\"msgerreur\" id=\"resultpass\"></span>
			    </td>
			</tr>

                        
			<tr>
			    <td>
			    Confirmation :
			    </td>
			    <td>
			    <input type=\"password\" size=\"15\" id=\"txtConfirm\" name=\"txtConfirm\" onblur=\"validate_confirm()\"  value=\"".$ligne["password"]."\"> <span class=\"msgerreur\" id=\"resultconfirm\"></span>
			    </td>
			</tr>
                        
			<tr>
			    <td>
			    Email :
			    </td>
			    <td>
			    <input type=\"text\" size=\"30\" id=\"txtEmail\" name=\"txtEmail\"  onblur=\"validate_email()\" value=\"".$ligne["email"]."\"><span class=\"msgerreur\" id=\"resultemail\"></span>
			    </td>
			</tr>
                        
                        <tr>
                            <td colspan=\"2\" style=\"text-align:right\">
                              <br/><br/><br/>
                                <input type=\"button\" id=\"cmdEnregistrerProfil\" onclick=\"javascript:history.back();\" class=\"cmdBouton\" name=\"cmdAnnule\" value=\"Annuler\">
                                <input type=\"submit\" class=\"cmdBouton\" id=\"cmdEnregistre\" value=\"Enregistrer\">
                            </td>

                        <tr>

	    </table>
		\n
		<div  class=\"retour\">
		<a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
		<img src=\"images/retour.png\"/><br/>Retour</a>
		</div>
	    </fieldset>   
	  </form>
	  </div>";

//Retourner l'affichage du contact
    return $main;
}




/**
 *
 *   AFFICHER FORMULAIRE POUR CONFIGURER PROFIL
 *
 * */
//Fonction pour ajouter un contact é l'aide d'un formulaire
function configurer_profil_db() {
    // Recouvrir les variables globales
    global $LOCAUX,$lien,$BD;
    
   
    
    
    $db = new Database();

    // Récupérer et filtrer les données 
    $email = $db->escapeString($_REQUEST['txtEmail']);
    $username = $db->escapeString($_REQUEST['txtUser']);
    $motdepass = $db->escapeString($_REQUEST['txtPass']);

    $reponse = $db->update("user",
                          ['email'=>$email,'username'=>$username,'password'=>$motdepass],
                          "`id`='".$_SESSION["id"]."'");

    
    //Réinitialiser les variables de session
    $_SESSION['username'] = $username;
    $_SESSION['password']= $motdepass;    
    
    
    //Ajouter un nouveau contact dans un formulaire
    $main = "<div class=\"ListeDemandes\">
	     <form id=\"frmConfigurerProfil\" name=\"frmConfigurerProfil\" onSubmit=\"validate(); return false;\" method=\"post\">\n
                <fieldset>
			<legend>Paramétres du profil</legend>
			<table class=\"FormProfil\">

			<tr>
			    <td>
			    Nom :
			    </td>
			    <td>
			    ".  $_REQUEST['txtNomComplet']."
			    </td>
			</tr>

			<tr>
			    <td>
			    Nom d'usager :
			    </td>
			    <td>
			    <span class=\"\" id=\"resultusername\">".$_REQUEST['txtUser']."</span>
			    </td>
			</tr>
                        
			<tr>
			    <td>
			    Mot de passe :
			    </td>
			    <td>			    
                            <span class=\"\" id=\"resultpass\">*******************</span>    
			    </td>
			</tr>
                        
			<tr>
			    <td>
			    Confirmation :
			    </td>
			    <td>			    
                            <span class=\"\" id=\"resultconfirm\">*******************</span>                                
			    </td>
			</tr>
                        
			<tr>
			    <td>
			    Email :
			    </td>
			    <td>			    
                            <span class=\"\" id=\"resultemail\">".$_REQUEST['txtEmail']."</span>                                
			    </td>
			</tr>                        
	    </table>
		\n<h2 style=\"color:green\">Enregistrement complété !</h2> 
		<div  class=\"retour\">
		<a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
		<img src=\"images/retour.png\"/><br/>Retour</a>
		</div>
	    </fieldset>   
	  </form>
	  </div>";

    //Retourner l'affichage du contact
    return $main;
    
}



/**
 * Définir le style de fond pour chaque plateau
 */

 function definir_stylefond(){

    
    if(isset($_SESSION["Plateau"])){
        //Récupérer le style de fond du plateau sélectionné
        $db = new Database();
        $reponse = $db->select("stylefond","tblplateaux","plateau='".$_SESSION["Plateau"]."'",true);
        return $reponse[0]['stylefond'];
    }
    else{
        //Définir une couleur de fond pad défaut
        return "background-color:#cccccc";
    }

}







/**
 *
 *  ****  A T T E N T I O N ****
 *  **** CETTE FONCTION POUR LES ENSEIGNANT ****
 *  AJOUTER UNE DEMANDE POUR LA SALLE D'EXAMENT
 * */

function ajouter_demande_db() {
// Recouvrir le nom de la base de données et l'identifiant de connexion
    global $lien, $BD, $message,  $DELAI_AFFICHAGE;


// Récupérer les champs é partir du formulaire
    $Nom = htmlspecialchars($_REQUEST['txtNom']); //la chaine a affiche dans la liste des demandes
    $Nofiche = 0;
    $Local = '---'; //LOCAL D'EXAMEN
    $Poste = 0;
    $TypeDemande = "Examen";
    $Cours = '462999'; //COURS EXAMEN
    $Bloc =0;
    $Question='';
    $url='';
    $idtblblocs=555; //NUMÉRO BLOC SUPPORT EN SALLE D'EXAMENT
    $HeureInscription = date("Y-m-d H:i:s", time());
    $HeureDebut = "0000-00-00 00:00:00";
    $HeureFin = "0000-00-00 00:00:00";
    $plateau =  $_REQUEST['lstPlateau']; //Récupérer le plateau
    $IP = $_SERVER['REMOTE_ADDR'];

//Si tous les champs sont complétés correctement alors
    if ($Nom != "" && $plateau != "") {
        $db = new Database();
        $data = [  
                    'NomEleve'=>addslashes($Nom),
                    'NoFiche'=>$Nofiche,
                    'Local'=>$Local,
                    'Poste'=>$Poste,
                    'TypeDemande'=>$TypeDemande,
                    'Cours'=>'462999',
                    'Bloc' => $Bloc,
                    'Question' => $Question, 
                    'url' => $url,
                    'idtblblocs'=>'555',
                    'HeureInscription'=>$HeureInscription,
                    'HeureDebut'=>$HeureDebut,
                    'HeureFin'=>$HeureFin,
                    'CodeUsager'=>$_SESSION['username'],
                    'Etat'=>'En attente',
                    'Plateau'=>$plateau,
                    'IP'=>$IP
                ];

        $reponse = $db->insert("tbldemandes",$data);
     
        //Réafficher la liste des demandes
        header("location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes");
    }
//Sinon
    else
    //Initialiser la variable message avec une chaéne indiquant qu'il vaut compléter tous les champs
        $message = "<div class=\"erreur\"><h1>Une erreur est survenue:</h1>
                    <b>Num&eacute;ro d'erreur :</b> 0 <br/>
                    <b>Description :</b>
                    <br/><font color=\"#cc0000\"><b>Erreur d'enregistrement<br>Veuillez compléter tous les champs correctement</b><br/></font>
                    <br/><br/><br/><br/><br/>
                    <input type=\"button\" value=\"Retour\" onclick=\"history.back()\"></div>";

//Retourner message
    return $message;
}










/* * **
 *
 *  FONCTION POUR SUPPRIMER UNE DEMANDE
 *
 * ********* */

function supprimer_demande($id) {    
//Recouvrir variable de connexion
    global $lien, $BD;

    $db =  new Database();
    $reponse = $db->select("*","tbldemandes","ID = $id");    
    $ligne = $reponse[0];


  

//Débuter l'affichage du formulaire de confirmation
    $liste = "\n<form name=\"frmSupprimer\" action=\"" . $_SERVER['PHP_SELF'] . "?selItem=supprimer_demande_db\" method=\"post\">";

//Affiche le tableau des données
    $liste .= "\n<table class=\"ListeDemandes\" border=0 align=\"center\" >
	    \n<tr class=\"LigneTitre\" style=\"background-color:#444444;color:white\">
		<td class=\"ID\">ID</td>
		<td class=\"Nom\">Nom</td>
		<td class=\"Local\">Local</td>
		<td class=\"Poste\">Poste</td>
	    </tr>";

    

    //Afficher une ligne de données
    $liste .= "\n<tr>
		    <td>
			" . $ligne['ID'] . ")
		    </td>
		    
		    <td>
			" . $ligne['NomEleve'] . "
		    </td>
		    
		    <td>
			" . $ligne['Local'] . "
		    </td>
		    
		    <td>
			" . $ligne['Poste'] . "
		    </td>
		    
		</tr>";



    $liste .= "\n</table>
		<div class=\"erreur\" style=\"margin:auto;width:600px;font-size:14pt\">
		<h3> Attention ! </h3>
		Voulez-vous vraiment supprimer cette demande ?<br/>
		<br/>
		</div>
		<input type=\"hidden\" name=\"txtCodeUsager\" value=\"" . $_SESSION['username'] . "\"/>\n
		<input type=\"hidden\" name=\"ID\" value=\"" . $ligne['ID'] . "\"/>\n
		<div style=\"width:600px;margin:auto\">
		\n<input type=\"submit\" name=\"cmdSupprime\" value=\"SUPPRIMER\"/>
		<div  class=\"retour\">
		<a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
		<img src=\"images/retour.png\"/><br/>Retour</a>
		</div>
		</div>";



//Fin du formulaire
    $liste .="\n</form>";

//Retourner l'affichage
    return $liste;
}













/**
*
*   MARQUER UNE DEMANDE ANNULé
*
**/

//Fonction pour supprimer un contact dans la base de données
function supprimer_demande_db($id,$CodeUsager=0)
{
// Recouvrir les valeurs de connexions
global $lien, $BD, $titre;

$clause = "ID = $id";
$db = new Database();
$reponse = $db->select('*','tbldemandes',$clause,true);


$ligne = $reponse[0];

// Vérifier l'état
if(count($ligne)!=0)
    {
    
    /****************************************************************************
    // Formuler la requéte pour marque la demande annulé de l'ID sélectionné
    $requete="UPDATE tbldemandes
		SET Etat='Annulé', CodeUsager='$CodeUsager' 
		WHERE ID = $id";
    ****************************************************************************/
    
    $db = new Database();
    $reponse = $db->delete('tbldemandes',$clause);


	//Réafficher la liste des demandes
	header("Location:".$_SERVER['PHP_SELF']."?selItem=afficher_demandes");
	
    }
// Si NON Erreur de code d'usager
else
    {
    //Affiche le message d'erreur et termine
    $erreur =  "<div class=\"erreur\">Une erreur est survenue:<p/>
	    <b>Num&eacute;ro d'erreur :</b>18<br/>
	    <b>Description :</b>Le num&eacute;ro de fiche ou le code d'usager n'est pas valide
	    </div><br/>
	    <div  class=\"retour\">
	      <img onclick=\"javascript:history.back()\" src=\"images/retour.png\"/><br/>Retour
	    </div>";
	    
    // Retourner le message d'erreur
    return $erreur;
    }			
		

}















/* * **
 *
 *  FONCTION POUR CONFIRMER DEMANDE TERMINER
 *
 * ********* */

function terminer_demande($id) {
//Recouvrir variable de connexion
    global $lien, $BD;


    $db = new Database();
    $reponse = $db->select("*","tbldemandes","ID=$id");


//Récupérer les détais de la demande
//$ligne = mysqli_fetch_array($reponse);
$ligne = $reponse[0];

//Débuter l'affichage du formulaire de confirmation
    $liste = "\n<form name=\"frmSupprimer\" action=\"" . $_SERVER['PHP_SELF'] . "?selItem=terminer_demande_db\" method=\"post\">";

//Affiche le tableau des données
    $liste .= "\n<table class=\"ListeDemandes\" border=0 align=\"center\" >
	    \n<tr class=\"LigneTitre\" style=\"background-color:#444444;color:white\">
		<td class=\"ID\">ID</td>
		<td class=\"Nom\">Nom</td>
		<td class=\"Local\">Local</td>
		<td class=\"Poste\">Poste</td>
	    </tr>";

    //Afficher une ligne de données
    $liste .= "\n<tr>
		    <td>
			" . $ligne['ID'] . ")
		    </td>
		    
		    <td>
			" . $ligne['NomEleve'] . "
		    </td>
		    
		    <td>
			" . $ligne['Local'] . "
		    </td>
		    
		    <td>
			" . $ligne['Poste'] . "
		    </td>
		    
		</tr>";



    $liste .= "\n</table>
		<div class=\"erreur\" style=\"margin:auto;width:600px;font-size:14pt\">
		<h3> Attention ! </h3><br/>
		Voulez-vous vraiment marquer cette demande TERMINéE ?<br/>
		</div>
		<input type=\"hidden\" name=\"txtCodeUsager\" value=\"" . $_SESSION['username'] . "\"/>\n
		<input type=\"hidden\" name=\"ID\" value=\"" . $ligne['ID'] . "\"/>\n
		<div style=\"width:600px;margin:auto\">
			\n<input type=\"submit\" name=\"cmdSupprime\" value=\"TERMINER\"/>
			<div  class=\"retour\">
			<a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
			<img src=\"images/retour.png\"/><br/>Retour</a>
			</div>
		</div>";



//Fin du formulaire
    $liste .="\n</form>";

//Retourner l'affichage
    return $liste;
}
















/**
 *
 *   MARQUER UNE DEMANDE TERMINé
 *
 * */
//Fonction pour temriner une demande dans la base de données
function terminer_demande_db($id, $CodeUsager = 0) {
// Recouvrir les valeurs de connexions
    global $lien, $BD, $titre;
    

    $db = new Database();  
    $data = [
             'Etat'=>'Termine',
             'CodeUsager'=>$_SESSION['username'],
             'HeureFin' =>date("Y-m-d H:i:s", time())
            ];

    $reponse = $db->update("tbldemandes",$data,"ID=$id");


    //Réafficher la liste des demandes
    header("Location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes");
}







/**
 *
 *   MARQUER UNE DEMANDE EN COURS
 *
 * */
//Fonction pour supprimer un contact dans la base de données
function marquer_en_cours($id) {
// Recouvrir les valeurs de connexions
    global $lien, $BD, $titre;

$db = new Database();
$reponse = $db->select("Etat","tbldemandes","ID = $id",false);

$ligne = $reponse[0];

// Vérifier l'état
    if ($ligne['Etat'] == "En cours")
    // Mettre en attente
        $etat = "En attente";
    else
    // Mettre en cours
        $etat = "En cours";

// Lire l'heure actuelle du systéme
    $HeureDebut = date("Y-m-d H:i:s", time());

    $data = [
            'Etat'=>$etat,
            'HeureDebut' => $HeureDebut,
            'CodeUsager'=>$_SESSION["username"]   
            ];

    $reponse = $db->update("tbldemandes",$data,"ID = $id");


    //Réafficher la liste des demandes
    header("Location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes");
}








/**
 *
 *  FONCTION POUR VéRIFIER SI L'USAGER EST VALIDE
 * 
 *
 * **** */
function verifier_usager($user, $pass) {
// Récupérer les variables de connexions
    global $lien, $BD, $LOCAUXLOG, $LOCAUXMAT, $LOCAUXSER;

    $db = new Database();

    $user = $db->escapeString($user);
    $pass = $db->escapeString($pass);

    $reponse = $db->select("*","user","(username='".$user."' AND password='".$pass."' AND niveau='enseignant') OR (username='".$user."' AND password='".$pass."' AND niveau='admin')");

        //Vérifie si l'usager existe
        if(isset($reponse[0])){
            $ligne = $reponse[0];
            }
            else{

                $message = "<div class=\"erreur\"><b class=\"erreurtxt\">Une erreur est survenue:</b><p/>
                        <b>Num&eacute;ro d'erreur :</b>404<br/>
                        <b>Description :</b>Erreur nom d'usager ou mot de passe invalid 
                        <br/></div>";

                // Initialiser le bouton de retour
                $message .="<div  class=\"retour\">
                            <a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
                            <img src=\"images/retour.png\"/><br/>Retour</a>
                            </div>";
                //Terminer la session de prof

                // Terminer la session et détruire les variables
                session_destroy();
                session_unset();


                // Retourner l'affichage du message
                return $message;  
            }

        /**
         *  INITIALISATION DES 
         *  VARIABLES DE SESSION
         *
         * */
        $_SESSION['id'] = $ligne['id'];  // Enregistre le ID
        $_SESSION['username'] = $ligne['username']; // Enregistre le username
        $_SESSION['Plateau'] = $_REQUEST['lstPlateau']; // Enregistre le plateau de travail
        $_SESSION['prenom'] = $ligne['prenom'];
        $_SESSION['nom'] = $ligne['nom'];        
        
        
        //Vérifier la liste des locaux du plateau
        if ($_REQUEST['lstPlateau'] == "LOG")
        // Enregistrer la liste des locaux du plateau logiciel
            $_SESSION['Locaux'] = $LOCAUXLOG;
        elseif ($_REQUEST['lstPlateau'] == "MAT")
        // Enregistrer la liste des locaux du plateau matériel
            $_SESSION['Locaux'] = $LOCAUXMAT;
        else
        // Enregistrer la liste des locaux du plateau réseau
            $_SESSION['Locaux'] = $LOCAUXSER;
        



        // Définir la boîte de déconnexion de l'enseignant
        $_SESSION['disbox'] = "<div class=\"disbox\">
				    <a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=terminer_session\">
				    " . $ligne['prenom'] . " 
				    " . $ligne['nom'] . " 
				    <img src=\"images/logo_login.png\"/>
				    </a>
				</div>";


        //ajoute le status LOG sur l'utilisateur affichage du nom dans la liste prof online
        $reponse = $db->update("user",["status"=>$_REQUEST['lstPlateau']],"id=".$ligne["id"]);
                


        // Afficher la liste des demandes
        header("location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes");
   // }
}



/**************************************************
 * 
 *  MODIFIER LE STATUS DE L'USAGER APRÈS CONNEXION
 * 
 * 
 * 
 */
function modifier_status_usager($id,$plateau){
    global $lien, $BD;

    $db = new Database();
    $reponse = $db->update("user",["status"=>''],"'id'=$id");


    // Afficher le login
    header("location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_login");                
                
        
}


/**************************************************
 * 
 *  MODIFIER LE STATUS DE L'USAGER APRÈS CONNEXION
 * 
 * 
 * 
 */
function terminer_session_prof($id){
    global $lien, $BD;

    $db = new Database();
    $reponse = $db->update("user",["status"=>''],"'id'=".$id);

    session_destroy();
    session_unset();
    //Réafficher la liste des demandes
    header("location:" . $_SERVER['PHP_SELF'] . "?selItem=selItem=afficher_login");
            
}










/**
 *  Modifier le message 
 *  
 *
 *
 *
 *
 * */
function modifier_message($id) {
// Recouvrir les variable
    global $lien, $BD, $LOCAUXLOG, $LOCAUXMAT, $LOCAUXSER;
    
    //Définir le time zone
    //date_default_timezone_set('America/Montreal');

    
   
    
   //Récupérer LE message d'avis sélectionné par ID
    $reponse = recuperer_avis($id);
    
    
   
    
    // Récupérer les informations
    //$tableau = mysqli_fetch_array($reponse);
    $tableau = $reponse[0];
    
    // Afficher le formulaire et compléter les champs avec action modifier
    //Ajouter un nouveau message dans un formulaire
    $main = "<div onload=\"window.open('plan_log.html','win','fullscreen=yes')\" class=\"ListeDemandes\">
	     <form  name=\"frmMessage\"  action=\"" . $_SERVER['PHP_SELF'] . "?selItem=modifier_message_db&ID=" . $tableau['ID'] . "\" method=\"POST\">\n
                <fieldset>
			
			<legend>D&eacute;tails du message d'avis</legend>";
                        


    
       $main .= afficher_liste_messages();



       //Entête de tableau
	$main.=		"<table class=\"FormMessage\">
            

			<tr>
			    <td colspan=\"2\" style=\"text-align:right\">
                                <a class=\"listeInscription\" title=\"Faire une copie du message pour date ultérieure\" href=\"#\" onclick=\"dupliquer_message(".$tableau['ID'].")\" >
                                    Dupliquer <img src=\"images/dupliquer.png\" class=\"IconeDupliquer\" >
                                </a>			    
			    </td>
			</tr>


			<tr>
			    <td>
			    Titre du message :
			    </td>
			    <td>
			    <input type=\"text\" size=\"30\" name=\"txtTitre\" value=\"" . $tableau['titre'] . "\">
			    </td>
			</tr>";




    // Affiche le champ pour la description zone de texte	
    $main.= "<tr>
		<td colspan=\"2\">
		    Description : <br/>
		    <textarea name=\"txtDescription\" cols=\"55\" rows=\"6\">" . stripcslashes(str_replace('\r\n','&#13;&#10;',$tableau['description'])) . "</textarea>
		</td>

	     </tr>";

    
            // Afficher le champ pour l'enseignant    
    $main .= "<tr>
		<td>
		    Enseignant :
		</td>
		<td>
		    <input type=\"text\" readonly size=\"30\" name=\"txtEnseignant\" value=\"".$tableau['enseignant']."\">
                    <input type=\"hidden\" readonly size=\"30\" name=\"txtIdEnseignant\" value=\"".$tableau['idenseignant']."\">                        
		</td>
	     </tr>";
    
    
    
    
    
    // Afficher le champ pour le lien    
    $main .= "<tr>
		<td>
		    Lien référence :
		</td>
		<td>
		    <input type=\"text\" size=\"30\" name=\"txtLien\" value=\"" . $tableau['lien'] . "\">
		</td>
	     </tr>";


    // Afficher le champ pour le local    
    $main .= "<tr>
		<td>
		    Local :
		</td>
		<td>
		    <select name=\"txtLocal\">\n
			<option " . ($tableau['local'] == "A-120" ? " selected " : "") . " value=\"A-120\">A-120</option>\n
			<option " . ($tableau['local'] == "A-121" ? " selected " : "") . "  value=\"A-121\">A-121</option>\n
			<option " . ($tableau['local'] == "A-122" ? " selected " : "") . "  value=\"A-122\">A-122</option>\n
			<option " . ($tableau['local'] == "A-123" ? " selected " : "") . "  value=\"A-123\">A-123</option>\n
			<option " . ($tableau['local'] == "A-124" ? " selected " : "") . "  value=\"A-124\">A-124</option>\n
			<option " . ($tableau['local'] == "A-125" ? " selected " : "") . "  value=\"A-125\">A-125</option>\n
			<option " . ($tableau['local'] == "A-126" ? " selected " : "") . "  value=\"A-126\">A-126</option>\n
			<option " . ($tableau['local'] == "A-127" ? " selected " : "") . "  value=\"A-127\">A-127</option>\n
			<option " . ($tableau['local'] == "A-128" ? " selected " : "") . "  value=\"A-128\">A-128</option>\n
                        <option " . ($tableau['local'] == "A-133" ? " selected " : "") . "  value=\"A-133\">A-133</option>\n
			<option " . ($tableau['local'] == "A-139" ? " selected " : "") . "  value=\"A-139\">A-139</option>\n
			<option " . ($tableau['local'] == "A-133" ? " selected " : "") . "  value=\"A-133\">A-133</option>\n
			<option " . ($tableau['local'] == "B-203" ? " selected " : "") . "  value=\"B-203\">B-203</option>\n
		    </select>
		</td>
	     </tr>";

    // Ajouter la date
    $main .="<tr>
		<td>
		    Date :
		</td>
		<td>
		
		    <input type=\"text\" size=\"15\" name=\"txtDate\" id=\"datepicker\" value=\"" . $tableau['date_evenement'] . "\">
		    
		</td>
	      </tr>";



    // Ajouter l'heure de début
    $main .="<tr>
		<td>
		    Heure de d&eacute;but :
		</td>
		<td>
		    <select name=\"txtDebut\" onchange=\"selectionner_fin()\">\n
			<option " . ($tableau['debut'] == "08:00:00" ? " selected " : "") . " value=\"8:00\">8:00</option>\n
			<option " . ($tableau['debut'] == "08:15:00" ? " selected " : "") . " value=\"8:15\">8:15</option>\n
			<option " . ($tableau['debut'] == "08:30:00" ? " selected " : "") . " value=\"8:30\">8:30</option>\n
			<option " . ($tableau['debut'] == "08:45:00" ? " selected " : "") . " value=\"8:45\">8:45</option>\n
			<option " . ($tableau['debut'] == "09:00:00" ? " selected " : "") . " value=\"9:00\">9:00</option>\n
			<option " . ($tableau['debut'] == "09:15:00" ? " selected " : "") . " value=\"9:15\">9:15</option>\n
			<option " . ($tableau['debut'] == "09:30:00" ? " selected " : "") . " value=\"9:30\">9:30</option>\n
			<option " . ($tableau['debut'] == "09:45:00" ? " selected " : "") . " value=\"9:45\">9:45</option>\n
			<option " . ($tableau['debut'] == "10:00:00" ? " selected " : "") . " value=\"10:00\">10:00</option>\n
			<option " . ($tableau['debut'] == "10:15:00" ? " selected " : "") . " value=\"10:15\">10:15</option>\n
			<option " . ($tableau['debut'] == "10:30:00" ? " selected " : "") . " value=\"10:30\">10:30</option>\n
			<option " . ($tableau['debut'] == "10:45:00" ? " selected " : "") . " value=\"10:45\">10:45</option>\n
			<option " . ($tableau['debut'] == "11:00:00" ? " selected " : "") . " value=\"11:00\">11:00</option>\n
			<option " . ($tableau['debut'] == "11:15:00" ? " selected " : "") . " value=\"11:15\">11:15</option>\n
			<option " . ($tableau['debut'] == "11:30:00" ? " selected " : "") . " value=\"11:30\">11:30</option>\n
			<option " . ($tableau['debut'] == "11:45:00" ? " selected " : "") . " value=\"11:45\">11:45</option>\n
			<option " . ($tableau['debut'] == "12:00:00" ? " selected " : "") . " value=\"12:00\">12:00</option>\n
			<option " . ($tableau['debut'] == "12:15:00" ? " selected " : "") . " value=\"12:15\">12:15</option>\n
			<option " . ($tableau['debut'] == "12:30:00" ? " selected " : "") . " value=\"12:30\">12:30</option>\n
			<option " . ($tableau['debut'] == "12:45:00" ? " selected " : "") . " value=\"12:45\">12:45</option>\n 
			<option " . ($tableau['debut'] == "13:00:00" ? " selected " : "") . " value=\"13:00\">13:00</option>\n
			<option " . ($tableau['debut'] == "13:15:00" ? " selected " : "") . " value=\"13:15\">13:15</option>\n
			<option " . ($tableau['debut'] == "13:30:00" ? " selected " : "") . " value=\"13:30\">13:30</option>\n
			<option " . ($tableau['debut'] == "13:45:00" ? " selected " : "") . " value=\"13:45\">13:45</option>\n
			<option " . ($tableau['debut'] == "14:00:00" ? " selected " : "") . " value=\"14:00\">14:00</option>\n
			<option " . ($tableau['debut'] == "14:15:00" ? " selected " : "") . " value=\"14:15\">14:15</option>\n
			<option " . ($tableau['debut'] == "14:30:00" ? " selected " : "") . " value=\"14:30\">14:30</option>\n
			<option " . ($tableau['debut'] == "14:45:00" ? " selected " : "") . " value=\"14:45\">14:45</option>\n
			<option " . ($tableau['debut'] == "15:00:00" ? " selected " : "") . " value=\"15:00\">15:00</option>\n
			<option " . ($tableau['debut'] == "15:15:00" ? " selected " : "") . " value=\"15:15\">15:15</option>\n

		    </select>
		    <span style=\"font-size:small\">(hh:mm)</span>
		    
		</td>
	      </tr>";


    // Ajouter la durée
    $main .="<tr>
		<td>
		    Heure de fin :
		</td>
		<td>
		    <select name=\"txtFin\">\n
			<option " . ($tableau['fin'] == "08:00:00" ? " selected " : "") . " value=\"8:00\">8:00</option>\n
			<option " . ($tableau['fin'] == "08:15:00" ? " selected " : "") . " value=\"8:15\">8:15</option>\n
			<option " . ($tableau['fin'] == "08:30:00" ? " selected " : "") . " value=\"8:30\">8:30</option>\n
			<option " . ($tableau['fin'] == "08:45:00" ? " selected " : "") . " value=\"8:45\">8:45</option>\n
			<option " . ($tableau['fin'] == "09:00:00" ? " selected " : "") . " value=\"9:00\">9:00</option>\n
			<option " . ($tableau['fin'] == "09:15:00" ? " selected " : "") . " value=\"9:15\">9:15</option>\n
			<option " . ($tableau['fin'] == "09:30:00" ? " selected " : "") . " value=\"9:30\">9:30</option>\n
			<option " . ($tableau['fin'] == "09:45:00" ? " selected " : "") . " value=\"9:45\">9:45</option>\n
			<option " . ($tableau['fin'] == "10:00:00" ? " selected " : "") . " value=\"10:00\">10:00</option>\n
			<option " . ($tableau['fin'] == "10:15:00" ? " selected " : "") . " value=\"10:15\">10:15</option>\n
			<option " . ($tableau['fin'] == "10:30:00" ? " selected " : "") . " value=\"10:30\">10:30</option>\n
			<option " . ($tableau['fin'] == "10:45:00" ? " selected " : "") . " value=\"10:45\">10:45</option>\n
			<option " . ($tableau['fin'] == "11:00:00" ? " selected " : "") . " value=\"11:00\">11:00</option>\n
			<option " . ($tableau['fin'] == "11:15:00" ? " selected " : "") . " value=\"11:15\">11:15</option>\n
			<option " . ($tableau['fin'] == "11:30:00" ? " selected " : "") . " value=\"11:30\">11:30</option>\n
			<option " . ($tableau['fin'] == "11:45:00" ? " selected " : "") . " value=\"11:45\">11:45</option>\n
			<option " . ($tableau['fin'] == "12:00:00" ? " selected " : "") . " value=\"12:00\">12:00</option>\n
			<option " . ($tableau['fin'] == "12:15:00" ? " selected " : "") . " value=\"12:15\">12:15</option>\n
			<option " . ($tableau['fin'] == "12:30:00" ? " selected " : "") . " value=\"12:30\">12:30</option>\n
			<option " . ($tableau['fin'] == "12:45:00" ? " selected " : "") . " value=\"12:45\">12:45</option>\n 
			<option " . ($tableau['fin'] == "13:00:00" ? " selected " : "") . " value=\"13:00\">13:00</option>\n
			<option " . ($tableau['fin'] == "13:15:00" ? " selected " : "") . " value=\"13:15\">13:15</option>\n
			<option " . ($tableau['fin'] == "13:30:00" ? " selected " : "") . " value=\"13:30\">13:30</option>\n
			<option " . ($tableau['fin'] == "13:45:00" ? " selected " : "") . " value=\"13:45\">13:45</option>\n
			<option " . ($tableau['fin'] == "14:00:00" ? " selected " : "") . " value=\"14:00\">14:00</option>\n
			<option " . ($tableau['fin'] == "14:15:00" ? " selected " : "") . " value=\"14:15\">14:15</option>\n
			<option " . ($tableau['fin'] == "14:30:00" ? " selected " : "") . " value=\"14:30\">14:30</option>\n
			<option " . ($tableau['fin'] == "14:45:00" ? " selected " : "") . " value=\"14:45\">14:45</option>\n
			<option " . ($tableau['fin'] == "15:00:00" ? " selected " : "") . " value=\"15:00\">15:00</option>\n
			<option " . ($tableau['fin'] == "15:15:00" ? " selected " : "") . " value=\"15:15\">15:15</option>\n
		    </select>		    
		    <span style=\"font-size:small\">(hh:mm)</span>
		    
		</td>
	      </tr>";
    
    //Ajouter la période de délai pour l'affichage 
    $main .= "<tr>
		<td>
		    Affichage :
		</td>
		<td>
                <select name=\"txtDelaiAffichage\">\n
                    <option " . ($tableau['delaiaffichage'] == 0 ? " selected " : "") . " value=\"0\">Maintenant</option>\n                
                    <option " . ($tableau['delaiaffichage'] == 3600 ? " selected " : "") . " value=\"3600\">1h avant</option>\n
                    <option " . ($tableau['delaiaffichage'] == (3600*3) ? " selected " : "") . " value=\"".(3600*3)."\">3h avant</option>\n
                    <option " . ($tableau['delaiaffichage'] == (3600*24) ? " selected " : "") . " value=\"".(3600*24)."\">1jour avant</option>\n                        
                    <option " . ($tableau['delaiaffichage'] == ((3600*24)*3) ? " selected " : "") . " value=\"".((3600*24)*3)."\">3jours avant</option>\n                        
                </select>
                </td>
              </tr>";
            
    

    // Inscriptions  
    $main .= "<tr>
                    <td>                    
                        Inscriptions :
                    </td>
                    
                    <td>
                        <a class=\"listeInscription\" title=\"Gestion de la liste d'inscriptions\" href=\"#\" onclick=\"gerer_inscriptions(".$tableau['ID'].")\" >
                            Gérer 
                            <img src=\"images/gerer.png\" class=\"IconeGerer\" >
                        </a>
                        
                        
                        <a class=\"listeInscription\" title=\"Imprimer la liste d'inscriptions de l'activité\" href=\"#\" onclick=\"imprimer_inscriptions(".$tableau['ID'].")\" >
                            Imprimer 
                            <img src=\"images/imprimer.png\" class=\"IconeImprimer\" >
                        </a>
                    <td>
	       </tr>";
    
    
    //Bouton envoyer               
    $main.=" <tr>
                    <td colspan=\"2\" style=\"text-align:center\">
                    <br/>
                         <input class=\"cmdBouton\" type=\"submit\" name=\"cmdDemande\" value=\"ENREGISTRER\" >&nbsp;&nbsp;&nbsp;
                    </td>
               </tr>
               
            </table>";
    
    //Bouton de retour 
    
    $main.= "\n\n
		<div  class=\"retour\">
		<a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
		<img src=\"images/retour.png\"/><br/>RETOUR</a>
		</div>
	    </fieldset>   
	  </form>
	  </div>";

    
    
//Retourner l'affichage du contact
    return $main;
}


/**
 *  Affiche la lisste de tous les message pour modification
 * 
 * 
 * 
 */
function afficher_liste_messages()
{
    //Iniitaliser la variable main
    $main = (isset($main)?$main:"");
    
    //Récupérer LES message d'avis de l'usager connecté
    $messages = recuperer_avis("*");

    //Liste des messages
    
    $main.="<div id=\"ListeMessages\">
                <b>Liste des messages</b>
                <table width=\"100%\" border=\"0\" class=\"ListeDemandes\">
                                
                    <thead class=\"LigneTitre\">
                        <tr>
                            <td class=\"TitreMessage\">Titre
                                <a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message&trititre=ASC\"><img src=\"images/b_up.png\"/></a>
                                <a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message&trititre=DESC\"><img src=\"images/b_down.png\"/></a>
                            </td>
                            
                            <td class=\"TitreDateMessage\"> Date <br/>
                                <a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message&tridate=ASC\"><img src=\"images/b_up.png\"/>
                                <a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message&tridate=DESC\"><img src=\"images/b_down.png\"/>
                            </td>
                            
                            <td class=\"OperationsMessage\">                        
                            </td>			
                        </tr>
                    </thead>

                    <tbody id=\"coreLstMessages\">";
                    
                    //Boucler pour chaque message de l'enseignant
                    //while($message = mysqli_fetch_array($messages)){
                    foreach($messages as $message){    
                    //Vérifier le propriétaire 
                    if($message["idenseignant"]!=$_SESSION['id'])
                        $class = "ligneMessageDesabled";
                    else
                        $class="ligneMessage";
                    
                    
                    
                    $main.=         "<tr class=\"".$class."\"> 

                                       <td class=\"TitreMessage\" onclick=\"location='?selItem=modifier_message&ID=".$message['ID']."'\">".$message['titre']."
                                       </td> 
                                       
                                       <td>
                                         <div>
                                            <font size=\"-1\">".$message['date_evenement']." de ".date("H:i",  strtotime($message['debut']))." à ".date("H:i",  strtotime($message['fin']))."</font>
                                         </div>                                         
                                       
                                       </td>


                                       <td class=\"Operations\">
                                           <a href=\"#\"  onclick=\"confirmation(".$message['ID'].")\"><img title=\"Supprimer un message\" class=\"IconeSupprimer\" src=\"images/supprimer.png\"></a>
                                       </td> 
                                    </tr>";
                    }

        $main .= "</tbody>
                                
                </table>                
            </div>";  
        
   return $main;        
}





/**
 * Modifier les détails du message dans 
 * la base de données
 *
 *
 *
 * */
function modifier_message_db($ID) {
// Recouvrir le nom de la base de données et l'identifiant de connexion
    global $lien, $BD, $message,  $DELAI_AFFICHAGE;


// Récupérer les champs é partir du formulaire
    $titre = addslashes(mysqli_real_escape_string($lien,$_REQUEST['txtTitre']));
    $description = addslashes(mysqli_real_escape_string($lien,$_REQUEST['txtDescription']));
    $lienweb = mysqli_real_escape_string($lien,$_REQUEST['txtLien']);
    $local = mysqli_real_escape_string($lien,$_REQUEST['txtLocal']);
    $date_evenement = mysqli_real_escape_string($lien,$_REQUEST['txtDate']);
    $debut = mysqli_real_escape_string($lien,$_REQUEST['txtDebut']);
    $fin = mysqli_real_escape_string($lien,$_REQUEST['txtFin']);
    $idenseignant = mysqli_real_escape_string($lien,$_REQUEST['txtIdEnseignant']);
    $delaiaffichage = mysqli_real_escape_string($lien,$_REQUEST['txtDelaiAffichage']);

    
    
// Vérifier si tous l'enseignant est propriétaire de la demande
    if($_SESSION['id']!=$idenseignant){

        //Affiche le message d'erreur et termine
        $main = "<div class=\"erreur\" onclick=\"javascript:history.back()\"><font color=\"#cc0000\">
                            <b>ERREUR D'ENREGISTREMENT</b></font><p/>
	    		    <b>Num&eacute;ro d'erreur :</b>222<br/><br/>
	    		    <b>Description :</b> <br/> Autorisation de modification interdite. Vous n'avez pas 
                            l'autorisation de modifier ce message car vous n'êtes sont son auteur. 
                            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	    		    <br/>
                            <div class=\"retour\">
                            <img onclick=\"javascript:history.back()\" src=\"images/retour.png\">
                            <br>Retour
                            </div><br/></div>";



            //Afficher l'interface
            include_once("affichage.php");

            //Terminer le script
            exit();
        }      
    
        
    

//Si tous les champs sont complétés correctement alors
    if ($titre != "" && $description != "" && $date_evenement != "") {
        //Formule la requéte pour enregistre la demande
        $requete = "UPDATE tblmessages 
		SET		
		    titre='$titre', 
		    description='$description',
		    lien='$lienweb',
		    local='$local',
		    date_evenement='$date_evenement',
		    debut='$debut',
		    fin='$fin',
                    delaiaffichage = '$delaiaffichage' 
		WHERE ID='$ID'";



        //Vérifie si une erreur est survenue lors de la requéte
        if (mysqli_errno($lien) != 0) {

            //Affiche le message d'erreur et termine
            $erreur = "<div class=\"erreur\">Une erreur est survenue:<p/>
	    		    <b>Num&eacute;ro d'erreur :</b>" . mysqli_errno($lien) . "<br/>
	    		    <b>Description :</b>" . mysqli_error($lien) . "
	    		    </div><br/>";



            //Afficher l'interface
            include_once("affichage.php");

            //Terminer le script
            exit();
        }

        // Transmet la requéte au serveur mysqli
        $reponse = mysqli_query($lien,$requete);


        // Ajouter le message dans un fichier ics
        $event = new ICS($date_evenement . " " . $debut, $date_evenement . " " . $fin, $titre, $description, $local);

        // Enregistrer le fichier ics
        $event->save;

        //Réafficher la liste des demandes
        header("location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes");
    }
//Sinon
    else {
        //Initialiser la variable message avec une chaéne indiquant qu'il vaut compléter tous les champs
        $message = "<div class=\"erreur\" onclick=\"javascript:history.back()\"><font color=\"#cc0000\">
                        <b>Erreur d'enregistrement</b>
                        <br/>Veuillez compléter tous les champs correctement</font>
                    <br/><br/><br/><br/><br/><br/><br/><br/><br/>
                    <div class=\"retour\">
		      <img onclick=\"javascript:history.back()\" src=\"images/retour.png\">
                      <br>Retour
                    </div><br/></div>";
    }


//Retourner message
    return $message;
}

/**
 *  FONCTION POUR RéCUPéRé LE MESSAGE D'AVIS
 *
 *
 *
 *
 * */
function recuperer_avis($idmessage ="*") {

//Définir l'affichage des contacts
    global $lien, $BD, $NbDemandes, $banniere;

    $db = new Database();


//Vérifier le tri
$tridate = (isset($_REQUEST['tridate'])?" ORDER BY date_evenement ". $db->escapeString($_REQUEST['tridate']):"");    
$trititre = (isset($_REQUEST['trititre'])?" ORDER BY titre ". $db->escapeString($_REQUEST['trititre']):"");    


    
//Vérifier si le idmessage est indéfini et id est défini
if($idmessage=="*"){
    $colonnes = "tblmessages.ID,titre,description,lien,local,date_evenement,debut,fin,delaiaffichage,idenseignant,concat(prenom, ' ', nom) AS enseignant";
    $tables = "tblmessages, user";
    $conditions = "user.id=idenseignant".$tridate . $trititre;
    //Formuler la requéte pour lire tous les messages non périmés
    //$requete = "SELECT tblmessages.ID,titre,description,lien,local,date_evenement,debut,fin,delaiaffichage,idenseignant,concat(prenom, ' ', nom) AS enseignant  FROM tblmessages, user WHERE user.id=idenseignant".$tridate . $trititre;
    }
//Vérifier si l'enseignant est défini
else{
    $colonnes="tblmessages.ID,titre,description,lien,local,date_evenement,debut,fin,delaiaffichage,idenseignant, concat(prenom, ' ', nom) AS enseignant";
    $tables = "tblmessages, user";
    $conditions = "tblmessages.ID = '".$idmessage."' AND user.id=idenseignant";
    //Formuler la requéte pour lire tous les messages de l'enseignant connecté
    //$requete = "SELECT tblmessages.ID,titre,description,lien,local,date_evenement,debut,fin,delaiaffichage,idenseignant, concat(prenom, ' ', nom) AS enseignant  FROM tblmessages, user WHERE tblmessages.ID = '".$idmessage."' AND user.id=idenseignant";        
}

$reponse = $db->select($colonnes,$tables,$conditions);


//Retourner le message d'afin
    return $reponse;
}








/**
 *
 *  FONCTION POUR AFFICHER LE MESSAGE D'AFIN é L'ENTéTE
 *
 *
 *
 *
 * */
function afficher_avis_non_perime(){
    global $lien, $DB;


	// Recouvrir les détails du message d'avis
	//$requete = "SELECT * FROM tblmessages WHERE date_evenement >= '".date("Y-m-d")."' ORDER BY date_evenement DESC ";

    $clause ="date_evenement >= '".date("Y-m-d")."' ORDER BY date_evenement DESC";

    $db = new Database();
    
    $messages = $db->select('*','tblmessages',$clause,true);

           

    
    
        //Initialiser l'avis
        $avis = array();


        
        //Récupérer le message d'avis rajouter les boutons
        //while($message = mysqli_fetch_array($reponse)){
        foreach($messages as $message){
            
            //SI délaiaffichage = 0 ALORS
            if($message['delaiaffichage']==0){
                //  Ajouter message a l'avis
                $ligne = "<a href=\"?selItem=modifier_message&ID=".$message['ID']."\"  title=\"".$message['description']."\n".$message['date_evenement']."\n de ".date("H:i",  strtotime($message['debut']))." a ".date("H:i",  strtotime($message['fin']))."\">".$message['titre']."</a>\n";

                        //Ajouter les boutons
                $ligne .="<span id=\"close_icomsg\">\n

                                            <a class=\"lienics\" href=\"index.php?selItem=generer_ics&ID=".$message['ID']."\" title=\"Ajouter l'événement a un calendrier\">\n
                                                <img src=\"images/calendrier.png\" />\n
                                            </a>\n

                                            <a class=\"lienmodifiermsg\" href=\"index.php?selItem=modifier_message&ID=".$message['ID']."\" title=\"Modifier le message d'annonce\">\n
                                                <img src=\"images/b_edit.png\" />\n
                                            </a>\n

                                            <a class=\"liensupprimermsg\" onclick=\"confirmation(".$message['ID'].")\" href=\"#\" title=\"Supprimer un message d'annonce\">\n
                                                <img src=\"images/b_drop.png\" />\n
                                            </a>\n

                                        </span>\n\n\n";

                //Ajouter la ligne dans la bannière
                array_push($avis, $ligne);                
            }

            //SINON 
            else {
                //  Lire le time stampordi
                $h = time();
                //  Composer le time stampmessage de l'événement avec la date et l'heure de début
                $t = strtotime($message['date_evenement']." ".$message['debut']);
                //  Soustraire du time stampmessage avec délai d'affichage
                $t = $t - intval($message['delaiaffichage']);
                //  SI le time stampordi >= timstampmessage ALORS
                if ($h >= $t){
                            //  Ajouter message a l'avis
                            $ligne = "<a href=\"?selItem=modifier_message&ID=".$message['ID']."\"  title=\"".$message['description']."\n".$message['date_evenement']."\n de ".date("H:i",  strtotime($message['debut']))." a ".date("H:i",  strtotime($message['fin']))."\">".$message['titre']."</a>\n";

                                    //Ajouter les boutons
                            $ligne .="<span id=\"close_icomsg\">\n

                                                        <a class=\"lienics\" href=\"index.php?selItem=generer_ics&ID=".$message['ID']."\" title=\"Ajouter l'événement a un calendrier\">\n
                                                            <img src=\"images/calendrier.png\" />\n
                                                        </a>\n

                                                        <a class=\"lienmodifiermsg\" href=\"index.php?selItem=modifier_message&ID=".$message['ID']."\" title=\"Modifier le message d'annonce\">\n
                                                            <img src=\"images/b_edit.png\" />\n
                                                        </a>\n

                                                        <a class=\"liensupprimermsg\" onclick=\"confirmation(".$message['ID'].")\" href=\"#\" title=\"Supprimer un message d'annonce\">\n
                                                            <img src=\"images/b_drop.png\" />\n
                                                        </a>\n

                                                    </span>\n\n\n";

                            //Ajouter la ligne dans la bannière
                            array_push($avis, $ligne);                       
                            //  FINSI
                            }                            
            //FINSI
            }

            
        
        
        }
        
  
        
	// Retourner le message d'avis avec lien
	return $avis;
}








/**
 *
 *   AFFICHER FORMULAIRE POUR UN MESSAGE D'AVIS
 *
 * */
//Fonction pour ajouter un contact é l'aide d'un formulaire
function ajouter_message() {
// Recouvrir les variables globales
    global $LOCAUX, $menu;
    
    
    $menu = "<div id=\"menuhorizontale\"><ul>\n
	<li><a onclick=\"start_auto_refresh_list()\" href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">Lister les demandes</a></li>\n        
	<li><a onclick=\"clearInterval(objtimer);location='" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message'\" href=\"#\">Ajouter un message</a></li>\n
        <li><a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_demande\">Ajouter une demande</a></li>\n                                
	</ul>
	</div>";
    
    
    
    //Ajouter un nouveau message dans un formulaire
    $main = "<div class=\"ListeDemandes\">
	     <form id=\"FormMessage\" name=\"frmMessage\"  action=\"" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message_db\" method=\"POST\">\n
                <fieldset>
			
			<legend>D&eacute;tail du message d'avis</legend>";
                        
    
    
    //Afficher la liste des capsules/message
    $main .= afficher_liste_messages();
    
    
 


    $main.=" <table class=\"FormMessage\">
			<tr>
			    <td>
			    Titre :
			    </td>
			    <td>
			    <input type=\"text\" size=\"30\" name=\"txtTitre\">
			    </td>
			</tr>";




    // Affiche le champ pour la description zone de texte	
    $main.= "<tr>
		<td colspan=\"2\">
		    Description : <br/>
		    <textarea name=\"txtDescription\" cols=\"55\" rows=\"6\"></textarea>
		</td>

	     </tr>";

        // Afficher le champ pour l'enseignant    
    $main .= "<tr>
		<td>
		    Enseignant :
		</td>
		<td>
		    <input type=\"text\" readonly size=\"30\" name=\"txtEnseignant\" value=\"".$_SESSION['prenom']." ".$_SESSION['nom']."\">
                    <input type=\"hidden\" readonly size=\"30\" name=\"txtIdEnseignant\" value=\"".$_SESSION['id']."\">                        
		</td>
	     </tr>";
    
    
    // Afficher le champ pour le lien    
    $main .= "<tr>
		<td>
		    Lien (endroit) :
		</td>
		<td>
		    <input type=\"text\" size=\"30\" name=\"txtLien\" value=\"./images/plan-cfpmr.html\">
		</td>
	     </tr>";


    // Afficher le champ pour le local    
    $main .= "<tr>
		<td>
		    Local :
		</td>
		<td>
		    <select name=\"txtLocal\">\n
			<option value=\"A-120\">A-120</option>\n
			<option value=\"A-121\">A-121</option>\n
			<option value=\"A-122\">A-122</option>\n
			<option value=\"A-123\">A-123</option>\n
			<option value=\"A-124\">A-124</option>\n
			<option value=\"A-125\">A-125</option>\n
			<option value=\"A-126\">A-126</option>\n
			<option value=\"A-127\">A-127</option>\n
			<option value=\"A-128\">A-128</option>\n
			<option value=\"A-139\">A-139</option>\n
			<option value=\"A-133\">A-133</option>\n
			<option value=\"B-203\">B-203</option>\n
		    </select>
		</td>
	     </tr>";

    // Ajouter la date
    $main .="<tr>
		<td>
		    Date :
		</td>
		<td>
		
		    <input type=\"text\" size=\"15\" name=\"txtDate\" id=\"datepicker\">
		    
		</td>
	      </tr>";



    // Ajouter l'heure de début
    $main .="<tr>
		<td>
		    Heure de d&eacute;but :
		</td>
		<td>
		    <select name=\"txtDebut\" onchange=\"selectionner_fin()\">\n
			<option value=\"8:00\">8:00</option>\n
			<option value=\"8:15\">8:15</option>\n
			<option value=\"8:30\">8:30</option>\n
			<option value=\"8:45\">8:45</option>\n
			<option value=\"9:00\">9:00</option>\n
			<option value=\"9:15\">9:15</option>\n
			<option value=\"9:30\">9:30</option>\n
			<option value=\"9:45\">9:45</option>\n
			<option value=\"10:00\">10:00</option>\n
			<option value=\"10:15\">10:15</option>\n
			<option value=\"10:30\">10:30</option>\n
			<option value=\"10:45\">10:45</option>\n
			<option value=\"11:00\">11:00</option>\n
			<option value=\"11:15\">11:15</option>\n
			<option value=\"11:30\">11:30</option>\n
			<option value=\"11:45\">11:45</option>\n
			<option value=\"12:00\">12:00</option>\n
			<option value=\"12:15\">12:15</option>\n
			<option value=\"12:30\">12:30</option>\n
			<option value=\"12:45\">12:45</option>\n
			<option value=\"13:00\">13:00</option>\n
			<option value=\"13:15\">13:15</option>\n
			<option value=\"13:30\">13:30</option>\n
			<option value=\"13:45\">13:45</option>\n
			<option value=\"14:00\">14:00</option>\n
			<option value=\"14:15\">14:15</option>\n
			<option value=\"14:30\">14:30</option>\n
			<option value=\"14:45\">14:45</option>\n
			<option value=\"15:00\">15:00</option>\n
			<option value=\"15:15\">15:15</option>\n
		    </select>
		    <span style=\"font-size:small\">(hh:mm)</span>
		    
		</td>
	      </tr>";


    // Ajouter la durée
    $main .="<tr>
		<td>
		    Heure de fin :
		</td>
		<td>
		    <select name=\"txtFin\">\n
			<option value=\"8:00\">8:00</option>\n
			<option value=\"8:15\">8:15</option>\n
			<option value=\"8:30\">8:30</option>\n
			<option value=\"8:45\">8:45</option>\n
			<option value=\"9:00\">9:00</option>\n
			<option value=\"9:15\">9:15</option>\n
			<option value=\"9:30\">9:30</option>\n
			<option value=\"9:45\">9:45</option>\n
			<option value=\"10:00\">10:00</option>\n
			<option value=\"10:15\">10:15</option>\n
			<option value=\"10:30\">10:30</option>\n
			<option value=\"10:45\">10:45</option>\n
			<option value=\"11:00\">11:00</option>\n
			<option value=\"11:15\">11:15</option>\n
			<option value=\"11:30\">11:30</option>\n
			<option value=\"11:45\">11:45</option>\n
			<option value=\"12:00\">12:00</option>\n
			<option value=\"12:15\">12:15</option>\n
			<option value=\"12:30\">12:30</option>\n
			<option value=\"12:45\">12:45</option>\n
			<option value=\"13:00\">13:00</option>\n
			<option value=\"13:15\">13:15</option>\n
			<option value=\"13:30\">13:30</option>\n
			<option value=\"13:45\">13:45</option>\n
			<option value=\"14:00\">14:00</option>\n
			<option value=\"14:15\">14:15</option>\n
			<option value=\"14:30\">14:30</option>\n
			<option value=\"14:45\">14:45</option>\n
			<option value=\"15:00\">15:00</option>\n
			<option value=\"15:15\">15:15</option>\n
		    </select>		    
		    <span style=\"font-size:small\">(hh:mm)</span>
		    
		</td>
	      </tr>";

  
    //Ajouter la période de délai pour l'affichage 
    $main .= "<tr>
		<td>
		    Affichage :
		</td>
		<td>
                <select name=\"txtDelaiAffichage\">\n
                    <option  value=\"0\">Maintenant</option>\n                
                    <option  value=\"3600\">1h avant</option>\n
                    <option  value=\"".(3600*3)."\">3h avant</option>\n
                    <option  value=\"".(3600*24)."\">1jour avant</option>\n                        
                    <option  value=\"".((3600*24)*3)."\">3jours avant</option>\n                        
                </select>
                </td>
              </tr>";
                
    

    // Ajouter les boutons   
    $main .= "<tr>
		<td colspan=\"2\" style=\"text-align:center\">
		<br/><br/>
		<input type=\"submit\" class=\"cmdBouton\" name=\"cmdDemande\" value=\"Ajouter\" >
		<br/>
	       </tr>
	    </table>";
            

            


    $main.="<div id=\"piedFormMessage\">
                    \n
                    <div  class=\"retour\">
                    <a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes\">
                    <img src=\"images/retour.png\"/><br/>Retour</a>
                    </div>
                </div>
	    </fieldset>   
	  </form>
	  </div>";

//Retourner l'affichage du contact
    return $main;
}
















/**
 *
 *  AJOUTER LE MESSAGE D'AVIS DANS LA BASE DE DONNéES
 *
 * */
//Fonction pour enregistrer les nouvelles données du contact dans la base de données
function ajouter_message_db() {
// Recouvrir le nom de la base de données et l'identifiant de connexion
    global $lien, $BD, $message, $DELAI_AFFICHAGE;

    $db = new Database();

    // Récupérer les champs é partir du formulaire
    $titre = $db->escapeString($_REQUEST['txtTitre']);
    $description = $db->escapeString($_REQUEST['txtDescription']);
    $lienweb = $db->escapeString($_REQUEST['txtLien']);
    $local = $db->escapeString($_REQUEST['txtLocal']);
    $date_evenement = $db->escapeString ($_REQUEST['txtDate']);
    $debut = $db->escapeString($_REQUEST['txtDebut']);
    $fin = $db->escapeString($_REQUEST['txtFin']);
    $idenseignant = $db->escapeString($_REQUEST['txtIdEnseignant']);



//Si tous les champs sont complétés correctement alors
    if ($titre != "" && $description != "" && $date_evenement != "") {

       $data = [   
        'titre'=>$titre, 
        'description'=>$description,
        'lien'=>$lienweb,
        'local'=>$local,
        'date_evenement'=>$date_evenement,
        'debut'=>$debut,
        'idenseignant'=>$idenseignant,
        'fin'=>$fin
       ];        
        
        $reponse = $db->insert("tblmessages",$data);
        // Ajouter le message dans un fichier ics
        $event = new ICS($date_evenement . " " . $debut, $date_evenement . " " . $fin, $titre, $description, $local);

        // Enregistrer le fichier ics
        $event->save();        

        //Réafficher la liste des demandes
        header("location:" . $_SERVER['PHP_SELF'] . "?selItem=afficher_demandes");
    }
//Sinon
    else {
         //Initialiser la variable message avec une chaéne indiquant qu'il vaut compléter tous les champs
         $message = "<div class=\"erreur\"><h1>Une erreur est survenue:</h1>
                        <b>Num&eacute;ro d'erreur :</b> 0 <br/>
                        <b>Description :</b>
                        <br/><font color=\"#cc0000\"><b>Erreur d'enregistrement<br>Veuillez compléter tous les champs correctement</b><br/></font>
                        <br/><br/><br/><br/><br/>
                        <input type=\"button\" value=\"Retour\" onclick=\"history.back()\"></div>";
    }

//Retourner message
    return $message;
}














/**
 * 
 *    fonction pour dupliquer un enregistrement à partir d'un ID
 * 
 *   entrée : ID
 *   Sorite : null au faux si erreur
 * 
 * 
 */
function dupliquer_message($id){

    $db = new Database();
    $reponse = $db->select("*","tblmessages","'ID'=".$id,true);

    $reponse = $db->select("*","tblmessages","ID=".$id);
    //Récupérer les détails du message    
    $ligne = $reponse[0];

    $data = [
            'titre'	=> $ligne['titre'],
            'description'=> $ligne['description'],
            'lien'=> $ligne['lien'],	
            'local'	=> $ligne['local'],        
            'date_evenement'=> $ligne['date_evenement'],
            'debut'=> $ligne['debut'],
            'fin'=> $ligne['fin'],	
            'idenseignant'=> $ligne['idenseignant'],	
            'delaiaffichage'=> $ligne['delaiaffichage']	
            ];
    $reponse = $db->insert("tblmessages",$data);

}



























/**
 *
 *   SUPPRIMER UN MESSAGE D'AVIS
 *
 * */
//Fonction pour supprimer un message avis
function supprimer_message_db($id) {
// Recouvrir les valeurs de connexions
    global $lien, $BD, $titre;

//Récupérer les informations du message    
    $message = recuperer_avis($id);
    //Récupérer la ligne
    $message = $message[0];
// Récupérer les détails dans un tableau php
    //$message = mysqli_fetch_array($message);
    
// Vérifier si l'usager est le propriétaire du message
    if($_SESSION['id']!=$message['idenseignant']){

        //Affiche le message d'erreur et termine
        $main = "<div class=\"erreur\" onclick=\"javascript:history.back()\"><font color=\"#cc0000\">
                            <b>ERREUR D'ENREGISTREMENT</b></font><p/>
	    		    <b>Num&eacute;ro d'erreur :</b>222<br/><br/>
	    		    <b>Description :</b> <br/> Autorisation de modification interdite. Vous n'avez pas 
                            l'autorisation de modifier ce message car vous n'êtes sont son auteur. 
                            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	    		    <br/>
                            <div class=\"retour\">
                            <img onclick=\"javascript:history.back()\" src=\"images/retour.png\">
                            <br>Retour
                            </div><br/></div>";



            //Afficher l'interface
            include_once("affichage.php");

            //Terminer le script
            exit();
        }      
    
    $db = new Database();
    $reponse = $db->delete("tblmessages","ID=".$id." AND idenseignant=".$_SESSION['id']);
    



    //Efface les inscriptions correspondantes
    $reponse = $db->delete("tblinscriptions","idEvenement=".$id);
   
    
    
    
    //Réafficher la liste des demandes
    header("location:" . $_SERVER['PHP_SELF'] . "?selItem=ajouter_message");
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
function calculer_stats_jour() {
    // Recouvrir les valeurs de connexions
    global $lien, $BD, $titre;

    //Initialiser la durée de l'intervention
    $totalduree = (isset($totalduree)?$totalduree:0);

    $db = new Database();
    $reponse = $db->select("COUNT(*) AS nbdemande",
                           "tbldemandes",
                           "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`)) <=0 AND Plateau='" . $_SESSION['Plateau'] . "' AND Etat LIKE '%Termin%'",true);

    


        // Récupérer le nombre de demande
        $colonne = $reponse[0];
        // Récupérer le nombre de demande total
        $nbtotaldemande = $colonne['nbdemande'];
    //}




    $reponse = $db->select("TIMESTAMPDIFF(SECOND,`HeureDebut`,`HeureFin`) as duree",
                            "tbldemandes",
                            "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`) <=0)  AND TIMESTAMPDIFF(SECOND,`HeureDebut`,`HeureFin`)<>0
                            AND `Etat` LIKE '%Termin%' AND Plateau='" . $_SESSION['Plateau'] . "'");
    
        // Lire le nombre total de demande terminée
        //$nbdemandetermine = mysqli_num_rows($reponse);
        $nbdemandetermine = count($reponse);
        // Vérifier si aucune demande pour éviter division par 0
        if ($nbdemandetermine == 0)
        // Assurer moyenne = /1
            $nbdemandetermine = 1;


        // Boucler pour cumuler la durée total et la durée moyenne
        //while ($colonne = mysqli_fetch_array($reponse)) {
        foreach($reponse as $colonne){
            // Récupérer le nombre de demande total
            $totalduree += $colonne['duree'];
        }

        // Lire la durée total en seconde des demandes
        $time = $totalduree;
        // Calculer les durées en heure, minute secondes
        $seconds = $time % 60;
        $time = ($time - $seconds) / 60;
        $minutes = $time % 60;
        $hours = ($time - $minutes) / 60;
    //}


    //Ajouter les statistiques à la bande statistiques
    $stats = "<b>Statistiques</b>:&nbsp;&nbsp;&nbsp; 
			Nombre total de demandes : " . $nbtotaldemande . " &nbsp;&nbsp;&nbsp;
			Dur&eacute;e totale : " . date("H:i:s", strtotime($hours . ":" . $minutes . ":" . $seconds)) . " &nbsp;&nbsp;&nbsp  
			Dur&eacute;e moyenne par demande : " . sprintf("%0.1f", ($totalduree / $nbdemandetermine)) . "sec &nbsp;&nbsp;&nbsp";
    
    
    //Ajouter le bouton de téléchargement des donneés
    $stats .= "<span class=\"disbox\" style=\"float:right\"><a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=configurer_profil\">&nbsp;
		Profil [&DownTeeArrow;]</a></span>&nbsp;&nbsp;";
    
    
    //Ajouter le bouton de téléchargement des donneés
    $stats .= "<span class=\"disbox\" style=\"float:right\"><a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=exporter_stats\">&nbsp;
		Exporter [&boxbox;]</a></span>";
    
    //Ajouter le bouton de téléchargement des donneés
    $stats .= "<span class=\"disbox\" style=\"float:right\"><a href=\"" . $_SERVER['PHP_SELF'] . "?selItem=rapport_stats&nbjours=60\">&nbsp;
		Rapport [&DownTeeArrow;]</a></span>";
        
    

    // Retourner les statistiques du jour
    return $stats;
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

    //Initialiser dureetotal
    $totalduree = (isset($totalduree)?$totalduree:0);
    
    
    //Tableau des statistiques
    $stats = array();

    $db = new Database();
    $reponse = $db->select(
                            "COUNT(`ID`) AS nbdemande",
                            "tbldemandes",
                            "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`)) <=0 AND Plateau='" . $_SESSION['Plateau'] . "' AND Etat = 'Termine'",
                            true
    );

    
        // Récupérer le nombre de demande
        //$colonne = mysqli_fetch_array($reponse);
        // Récupérer le nombre de demande total
        $colonne = $reponse[0];
        $nbtotaldemande = $colonne['nbdemande'];
    //}


    $reponse = $db->select("TIMESTAMPDIFF(SECOND,`HeureDebut`,`HeureFin`) as duree",
                            "tbldemandes",
                            "(TO_DAYS(NOW()) - TO_DAYS(`HeureInscription`) <=0)  AND TIMESTAMPDIFF(SECOND,`HeureDebut`,`HeureFin`)<>0
                            AND `Etat` LIKE '%Termin%' AND Plateau='" . $_SESSION['Plateau'] . "'",true);

    
            foreach($reponse as $colonne){
            // Récupérer le nombre de demande total
            $totalduree += $colonne['duree'];
        }
        // Lire le nombre total de demande terminée
        //$nbdemandetermine = mysqli_num_rows($reponse);
        $nbdemandetermine = count($reponse);
        // Vérifier si aucune demande ajuste a 1 pour division par 0
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










/**
*
*   MARQUER UNE DEMANDE EN COURS
*
**/

//Fonction pour supprimer un contact dans la base de donn&eacute;es
function ajax_marquer_en_cours($id)
{
// Recouvrir les valeurs de connexions
global $lien, $BD, $titre;

$db = new Database();

$reponse = $db->select("Etat","tbldemandes","ID = $id",true);


//Récupérer les donneés
$ligne = $reponse[0];
// V&eacute;rifier l'&eacute;tat
if($ligne['Etat']=="En cours")
    // Mettre en attente
    $etat = "En attente";
else
    // Mettre en cours
    $etat = "En cours";

// Lire l'heure actuelle du syst&eacute;me
$HeureDebut = date("Y-m-d H:i:s",time());

$data = [
            'Etat'=>$etat,
            'HeureDebut' => $HeureDebut,
            'CodeUsager'=>$_SESSION["username"]                                 
        ];
$reponse = $db->update("tbldemandes",$data,"ID = $id");

    //R&eacute;afficher la liste des demandes
    die();
    
}














/***********************************************
 * 
 *   FONCTION POUR RETOUNER LE NOM DES ENSEIGNANTS 
 *   CONNECTES SUR LE PLATEAU
 * 
 */


function ajax_identifier_profs_plateau($plateau){
    global $lien, $BD;
    
    $profs = array();

    $db = new Database();

    $repons = $db->select("prenom, nom, email, id","user","status ='".$plateau."'");


    foreach($repons as $enseignant){

        array_push($profs,['prenom'=>$enseignant['prenom'], 'nom'=>$enseignant['nom'], 'email'=>$enseignant['email'],'id'=>$enseignant['id']]);
    }
        
    return $profs;        
           
}




/**
 * EVENEMENT
 * Description:
 *      Classe qui permet de créer et récupérer les détails d'un évéenement
 * 
 * Entrée(s):
 *      int $ID La clée primaire de l'événement
 * 
 * 
 * Sortie(s):
 *      objet $evenement   Détails de l'événement ou faux
 * 
 * 
 */

 class Evenement {
    public $ID;
    public $titre;
    public $description;
    public $lien;
    public $local;
    public $dateEvenement;
    public $debut;
    public $fin;
    public $idEnseignant;
    public $enseignant;
    public $delaiAffichage;

    public function __construct($ID) {
        $db = new Database();
        $result = $db->select(
            "tblmessages.ID, titre, description, lien, local, date_evenement, debut, fin, idenseignant, delaiaffichage, CONCAT(user.prenom, ' ', user.nom) AS enseignant",
            "user, tblmessages",
            "tblmessages.ID = $ID AND user.id = tblmessages.idenseignant",
            true
        );

        if ($result && !empty($result)) {
            $row = $result[0];

            $this->ID = $row['ID'];
            $this->titre = $row['titre'];
            $this->description = $row['description'];
            $this->lien = $row['lien'];
            $this->local = $row['local'];
            $this->dateEvenement = $row['date_evenement'];
            $this->debut = $row['debut'];
            $this->fin = $row['fin'];
            $this->idEnseignant = $row['idenseignant'];
            $this->enseignant = $row['enseignant'];
            $this->delaiAffichage = $row['delaiaffichage'];

            return $row;
        } else {
            return false;
        }
    }
}







    

/**
 * LISTE DES PARTICIPANTS A UNE ACTIVITÉ
 * Description:
 *      Objet pour créer une liste de participants a une activité données
 * 
 * Enrées :
 *      @property int $idEvenement Numéro de l'événement la clé primaire
 * 
 * Sorties :
 *      @property array $tabParticipants Tableau des participants
 * 
 */
class lstParticipants{
    
    var $idEvenement;
    var $tabParticipants = array();
    
    function __construct($ID) {
        global $lien;        
        $this->idEvenement = $ID;

        $db = new Database();

        $reponse = $db->select("user.id, 
                                user.nom, 
                                user.prenom, 
                                user.email, 
                                user.fiche, 
                                user.username, 
                                user.password,
                                user.niveau, 
                                tblmessages.ID, 
                                tblinscriptions.idEvenement, 
                                tblinscriptions.idParticipant",

                                "tblinscriptions,user,tblmessages",

                                "tblinscriptions.idEvenement=tblmessages.ID AND 
                                tblinscriptions.idParticipant=user.ID AND 
                                tblinscriptions.idEvenement=".$this->idEvenement,true);
        
                    foreach($reponse as $colonne){
                    //Initialiser tableau assoc pour UNE personne 
                    $tabPersonne = array('id'=>$colonne['id'],
                                         'prenom'=>$colonne['prenom'],
                                         'nom'=>$colonne['nom'],
                                         'email'=>$colonne['email'],
                                         'fiche'=>$colonne['fiche'],
                                         'username'=>$colonne['username'],
                                         'password'=>$colonne['password'],
                                         'niveau'=>$colonne['niveau']);                        
                    
                    //Ajouter la personne a la liste
                    array_push($this->tabParticipants, $tabPersonne);
                 }
            //Retourner la liste des participants
            return $this->tabParticipants;               
            }
}



/**
 *
 *
 *   CLASS GENERATEUR ICS 
 *
 *
 *
 *
 *
 * */
class ICS {

    var $start;
    var $end;
    var $description;
    var $location;
    var $data;
    var $name;
    

    function __construct($start, $end, $name, $description, $location) {
        $this->name = $name;
        $this->data = "BEGIN:VCALENDAR
PRODID:-//Google Inc//Google Calendar 70.9054//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:" . $name . "
X-WR-TIMEZONE:America/Montreal
X-WR-CALDESC:" . $description . "
BEGIN:VEVENT
DTSTART:" . date("Ymd\THis", strtotime($start)) . "
DTEND:" . date("Ymd\THis", strtotime($end)) . "
DTSTAMP:" . date("Ymd\THis", time()) . "
UID:avis@supprof.lan
SUMMARY:" . $name . "
CREATED:" . date("Ymd\THis", strtotime($start)) . "
DESCRIPTION:" . $description . "
LAST-MODIFIED:" . date("Ymd\THis", time()) . "
LOCATION:" . $location . "
SEQUENCE:0
BEGIN:VALARM
ACTION:DISPLAY
DESCRIPTION:REMINDER
TRIGGER:-PT15M
END:VALARM
STATUS:CONFIRMED
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR";
    }

    function save() {
        file_put_contents("./tmp/info.ics", $this->data);
    }

    function show() {
        //Vide le buffer avant d'envoyer
        ob_implicit_flush();
        header("Content-type:text/calendar; charset=utf-8");
        header('Content-Disposition: attachment; filename="' . "event-" . date("YmdHis") . '.ics"');
        Header('Content-Length: ' . strlen($this->data));
        Header('Connection: close');
        echo $this->data;
        
    }

}









  /**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */
/**
 * 
 * PHP EXCEL
 * Fonction pour exporter les données dans un chiffrier Excel 
 * 
 * 
 */
function exporter_stats()
{
//Globaliser les variables de connexion
//Définir l'affichage des contacts
global $lien, $BD, $NbDemandes, $banniere, $stats;
    

/** Error reporting */
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Montreal');

if (PHP_SAPI == 'cli')
	die('Ce fichier peut seulement être exécuté à partir d\'un serveur web');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/./Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($_SESSION['prenom']." ".$_SESSION['nom'])
							 ->setLastModifiedBy($_SESSION['prenom']." ".$_SESSION['nom'])
							 ->setTitle("Exportation Excel")
							 ->setSubject("Exportation Excel")
							 ->setDescription("Données de l'enseignant ".$_SESSION['prenom']." ".$_SESSION['nom'])
							 ->setKeywords("supprof")
							 ->setCategory("Resultats");

$valeur = $_SESSION["prenom"]." ".$_SESSION['nom'];
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', 'NoFiche')
            ->setCellValue('C1', 'NomEleve')
            ->setCellValue('D1', 'Local')
            ->setCellValue('E1', 'Poste')
            ->setCellValue('F1', 'HeureDebut')
            ->setCellValue('G1', 'HeureFin')
            ->setCellValue('H1', 'Etat')
            ->setCellValue('I1', 'IP')
            ->setCellValue('J1', 'HeureInscription')
            ->setCellValue('K1', 'Cours')
            ->setCellValue('L1', 'Bloc');


    $db = new Database();
    $reponse = $db->select("ID, NoFiche, NomEleve, Local, Poste, HeureDebut, HeureFin, Etat, Plateau, IP, HeureInscription, Cours, Bloc",
                            "tbldemandes",
                            "HeureInscription >= '".date("Y-m-d")."' 
                             AND Plateau = '" . $_SESSION['Plateau'] . "' 
                             ORDER BY ID ASC",true);


            //Initialiser le pointeur de ligne Excel
            $pointeurligne=2;
            
            //Boucler pour chaque réseultat
            //while($colonne = mysqli_fetch_array($reponse)){
            foreach($reponse as $colonne){                                
                    // Miscellaneous glyphs, UTF-8
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$pointeurligne, $colonne['ID'])
                                ->setCellValue('B'.$pointeurligne, $colonne['NoFiche'])
                                ->setCellValue('C'.$pointeurligne, $colonne['NomEleve'])                            
                                ->setCellValue('D'.$pointeurligne, $colonne['Local'])
                                ->setCellValue('E'.$pointeurligne, $colonne['Poste'])
                                ->setCellValue('F'.$pointeurligne, $colonne['HeureDebut'])
                                ->setCellValue('G'.$pointeurligne, $colonne['HeureFin'])
                                ->setCellValue('H'.$pointeurligne, $colonne['Etat'])
                                ->setCellValue('I'.$pointeurligne, $colonne['IP'])
                                ->setCellValue('J'.$pointeurligne, $colonne['HeureInscription'])
                                ->setCellValue('K'.$pointeurligne, $colonne['Cours'])
                                ->setCellValue('L'.$pointeurligne, $colonne['Bloc']);

                    //Déplacer pointeur prochaine linge
                    $pointeurligne +=1;
                    }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Compilation des demandes');


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $filePath = '/tmp/' . rand(0, getrandmax()) . rand(0, getrandmax()) . ".xlsx";

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.basename($filePath).'');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            //$objWriter->save('php://output');
            
               $objWriter->save($filePath);
               readfile($filePath);
               unlink($filePath);            
            
            exit;
           //}

    }

    
    

    
    
    
    

  /**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */
/**
 * 
 * PHP EXCEL
 * Fonction pour exporter les données dans un chiffrier Excel 
 * 
 * 
 */
function rapport_stats()
{    

//Globaliser les variables de connexion
//Définir l'affichage des contacts
global $lien, $BD, $NbDemandes, $banniere, $stats;
    

/** Error reporting */
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Montreal');

if (PHP_SAPI == 'cli')
	die('Ce fichier peut seulement être exécuté à partir d\'un serveur web');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/./Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator($_SESSION['prenom']." ".$_SESSION['nom'])
							 ->setLastModifiedBy($_SESSION['prenom']." ".$_SESSION['nom'])
							 ->setTitle("Exportation Excel")
							 ->setSubject("Exportation Excel")
							 ->setDescription("Données de l'enseignant ".$_SESSION['prenom']." ".$_SESSION['nom'])
							 ->setKeywords("supprof")
							 ->setCategory("Resultats");

$valeur = $_SESSION["prenom"]." ".$_SESSION['nom'];
// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'NoFiche')
            ->setCellValue('B1', 'NomEleve')
            ->setCellValue('C1', 'LOG')
            ->setCellValue('D1', 'MAT')
            ->setCellValue('E1', 'SER');


    
//Sélectionner la base de données de liens
//    mysqli_select_db($lien,$BD);


//Récupérer le paramètre d'interval en jours
    if(isset($_REQUEST['nbjours']))
        $nbjours = $_REQUEST['nbjours'];
    else
        $nbjours = 30;


    $db = new Database ();
    $reponse = $db->select("tbldemandes.NoFiche,
                            tbldemandes.NomEleve,
                            COUNT(CASE WHEN tbldemandes.Plateau='LOG' THEN 1 ELSE NULL END) AS LOG, 
                            COUNT(CASE WHEN tbldemandes.Plateau='MAT' THEN 1 ELSE NULL END) AS MAT, 
                            COUNT(CASE WHEN tbldemandes.Plateau='SER' THEN 1 ELSE NULL END) AS SER",
                            
                            "tbldemandes",
                            
                            "tbldemandes.HeureDebut <= CURDATE() AND HeureDebut >= DATE_SUB(CURDATE(),INTERVAL ".$nbjours." DAY) 
                             GROUP BY NomEleve",true);


            //Initialiser le pointeur de ligne Excel
            $pointeurligne=2;
            
            //Boucler pour chaque réseultat
            //while($colonne = mysqli_fetch_array($reponse)){
            foreach($reponse as $colonne){

                                
                    // Miscellaneous glyphs, UTF-8
                    $objPHPExcel->setActiveSheetIndex(0)
                                ->setCellValue('A'.$pointeurligne, $colonne['NoFiche'])
                                ->setCellValue('B'.$pointeurligne, $colonne['NomEleve'])
                                ->setCellValue('C'.$pointeurligne, $colonne['LOG'])                            
                                ->setCellValue('D'.$pointeurligne, $colonne['MAT'])
                                ->setCellValue('E'.$pointeurligne, $colonne['SER']);

                    //Déplacer pointeur prochaine linge
                    $pointeurligne +=1;
                    }

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Compilation des demandes');


            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);
            $filePath = '/tmp/' . rand(0, getrandmax()) . rand(0, getrandmax()) . ".xlsx";

            // Redirect output to a client’s web browser (Excel2007)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.basename($filePath).'');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            //$objWriter->save('php://output');
            
               $objWriter->save($filePath);
               readfile($filePath);
               unlink($filePath);            
            
            exit;
            //}

    
}
    
    
    
/************************************************************
 * 
 * 
 *    G E R E R     L E S      I N S C R I P T I O N
 * 
 * Description :  Cette fonction affiche la liste des participants
 *                inscrits dans un événement/capsule spécifique 
 * 
 * ID = Nurméro de la capsule/evenement
 * action = ajax_inscrire_participant
 * action = ajax_desinscrire_participant
 * idparticipant = Numéro du participan
 * 
 * 
 */    
    
  function gerer_inscriptions()  
  {
      //Récupérer les variables 
      global $lien;


      $db = new Database();
   
      //Récupérer le ID de la capsule
      $ID = $db->escapeString(isset($_REQUEST['ID'])?$_REQUEST['ID']:0); //idevenment
      $action = $db->escapeString(isset($_REQUEST['action'])?$_REQUEST['action']:""); //ajout ou supp
      $fiche = $db->escapeString(isset($_REQUEST['fiche'])?$_REQUEST['fiche']:0); //fiche eleve
      $idparticipant = $db->escapeString(isset($_REQUEST['idparticipant'])?$_REQUEST['idparticipant']:0);
      
      if(isset($_REQUEST["fiche"]) && $_REQUEST["fiche"]!="" || isset($_REQUEST["idparticipant"]) && $_REQUEST["idparticipant"]!=""){

            //Récupére le id du participant
            $reponse = $db->select("id","user","fiche=".$fiche);
            if(isset($reponse[0])){
                $idparticipant = $reponse[0]["id"];
                }        

            //traiter les actions ajouter et 
            switch($action){
                case 'ajax_inscrire_participant':       $data = ["idParticipant"=>$idparticipant,"idEvenement"=>$ID];
                                                        //Formuler la requête pour inscription
                                                        $reponse = $db->insert("tblinscriptions",$data);
                                                        //$requete = "INSERT INTO tblinscriptions (idParticipant, idEvenement) SELECT id, $ID FROM user WHERE fiche=$fiche";
                                                        //mysqli_query($lien, $requete);                                                                                                            
                                                        break;
                                                    
                                                    
                case 'ajax_desinscrire_participant':  //Formuler la requête de suppression
                                                        $reponse = $db->delete("tblinscriptions","idParticipant=$idparticipant AND idEvenement=$ID");
                                                        //$requete = "DELETE FROM tblinscriptions WHERE idParticipant=$idparticipant AND idEvenement=$ID";
                                                        //Transmettre la requête
                                                        //mysqli_query($lien, $requete);
                                                        break;
                                                    

                case 'ajax_inviter_participants':     //Créer la liste des destinataires
                                                        $lstDestinataires =array();
                                                        $lstDestinataires = new lstParticipants($ID);
                                                        $lstEmail = array();
                                                        $personne = array();
                                                        foreach ($lstDestinataires->tabParticipants as $personne){  
                                                            if($personne['email']!="")
                                                                $lstEmail[$personne['email']]=$personne['prenom']." ".$personne['nom'];
                                                        }                                              
                                                        
                                                        //Créer le fichier ics pour l'attachement
                                                        $evenement = new evenement($ID);
                                                        
                                                    
                                                        //Créer le fichier ICS avec l'événement
                                                        $ics = new ICS($evenement->date_evenement." ".$evenement->debut,
                                                                        $evenement->date_evenement." ".$evenement->fin,
                                                                        $evenement->titre,
                                                                        str_replace('\r','',$evenement->description.'\n\nEnseignant : '. $evenement->enseignant.'\n\nVous avez été inscrits à cette événement. Si vous ne pouvez pas y participer, veuillez contacter le responsable\n\nMerci !'),
                                                                        $evenement->local);
            
                                                        //Enregistrer le fichier
                                                        $ics->save();                                                


                                                        //Ajouter le bouton de retour
                                                        echo "<div class=\"retour\" style=\"position: absolute;bottom:20px;right: 20px;>
                                                                <a href=\"#\" onclick=\"window.close()\">
                                                                <img src=\"images/retour.png\"><br>RETOUR</a>
                                                                </div>";                                                
                                                        die();
                                                        break;
                                                    
            }
      }

      //Initialiser une variable pour la liste des usagers a inscrire
      $usersliste="";
      //Formuler la requête pour créer une liste des participants

      $reponse = $db->select("fiche, nom, prenom","user","1 ORDER BY id DESC LIMIT 300",true);

      // while($colonne= mysqli_fetch_assoc($reponse)){
      foreach($reponse as $colonne){
                 $usersliste .= "<option value=\"".$colonne['fiche']."\">".$colonne['nom'].", ".$colonne['prenom']."</option>\n";
           
       }

      //Afficher le formulaire pour l'autocomplétion   
      echo "<html>";
      echo "<head>";
      echo "<title>Liste d'inscription</title>";
      echo "<link rel=\"stylesheet\" href=\"./styles.css\"/>";
      echo "<script src=\"./script.js\"></script>";
      echo "</head>";
      echo "<body>";
      echo "<table border=\"1\">";
      echo "<thead style=\"background-color:silver;font-weight:bold;text-transform:uppercase;text-align:center\">";
      echo "<td>Participants <br/>";
      echo "<form method=\"post\" action=\"?selItem=gerer_inscriptions&ID=$ID&action=ajax_inscrire_participant\"><input list=\"participants\" name=\"fiche\">";
      echo "<datalist id=\"participants\">";
      echo $usersliste;
      echo "</datalist>";
      echo "<input type=\"image\" src=\"images/b_adduser.png\"  title=\"Inscrire un participant dans l'activite\"/>";
      echo "</form>";
      echo "</td>";
      echo "<td></td>";
      echo "</thead>";
       
      $reponse = $db->select("user.id, 
                            user.nom, 
                            user.prenom, 
                            user.email, 
                            tblmessages.ID, 
                            tblinscriptions.idEvenement, 
                            tblinscriptions.idParticipant",

                            "tblinscriptions,user,tblmessages",

                            "tblinscriptions.idEvenement=tblmessages.ID AND 
                            tblinscriptions.idParticipant=user.ID AND 
                            tblinscriptions.idEvenement=".$ID,true);

      $tmplist="";
      $nbparticipant=0;
      //while($colonne= mysqli_fetch_assoc($reponse)){
      foreach($reponse as $colonne){
          $nbparticipant++;
          $tmplist.= "<tr><td>".$colonne["nom"].", ".$colonne['prenom']."</td>"
                  . "<td><a href=\"?selItem=gerer_inscriptions&ID=$ID&action=ajax_desinscrire_participant&idparticipant=".$colonne['idParticipant']."\"\"><img src=\"images/b_drop.png\"/></a></td></tr>";
      }


      $tmplist .= "<tr><td colspan=\"2\">".
                  "<a class=\"listeInscription\" title=\"Imprimer la liste d'inscriptions de l'activité\" href=\"#\" onclick=\"imprimer_inscriptions(".$ID.")\" >". 
                  "<img src=\"images/imprimer.png\" class=\"IconeImprimer\" >".
                  "<br/>Imprimer la liste</a></td></tr></a>";

      
      echo $tmplist;
      
      echo "<div class=\"retour\" style=\"position: absolute;bottom:20px;right: 20px;>
		<a href=\"#\" onclick=\"window.close()\">
		<img src=\"images/retour.png\"><br>RETOUR</a>
		</div>";
      
      echo "</body>";
      echo "</html>";

      //Arrêter le script et faire l'affichage dans la fenêtre
      die();
  }
    
    
    
    
    
    
/**
 * 
 *    I M P R I M E R   I N S C R I P T I O N  
 * 
 *     P     D      F
 * 
 * 
 */    


function imprimer_inscriptions()
{
define("NBLIGNEPARPAGE",25);  
global $lien,$BD;
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
//Définir le nombre de ligne par page pour les tableaux html


// Include the main TCPDF library (search for installation path).
require_once('Classes/tcpdf/tcpdf.php');

$db = new Database();
$reponse = $db->select("tblmessages.*, user.id, CONCAT(user.prenom, ' ' , user.nom) AS enseignant",
                       "tblmessages, user",
                       "tblmessages.ID='".mysqli_real_escape_string($lien,$_REQUEST['ID'])."' AND user.id = idenseignant",true);

//Récupérer les détails du message
$message = $reponse[0];


// Extend the TCPDF class to create custom Header and Footer
class tcpdfEX extends TCPDF {
        //Propriété titre de l'entete
        var $titre;
        
        public function setTitre($titre){
            $this->titre = $titre;
        }
        
        //Page header
	public function Header() {
                //Récupérer les détails de l'événement
                global $message;
		// Logo
		$image_file = 'images/logo_supprof.png';
		$this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 16);
		// Title
		$this->Cell(0, 15, 'Liste d\'inscriptions ('.$this->titre.')', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}


// create new PDF document
$pdf = new tcpdfEX(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


//Définir le titre du PDF
$pdf->setTitre($message["titre"]);


// set document information
$pdf->SetCreator("suPProf");
$pdf->SetAuthor('André Duchesne');
$pdf->SetTitle($message["titre"]);
$pdf->SetSubject($message["titre"]);
$pdf->SetKeywords('inscriptions');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/fra.php')) {
	require_once(dirname(__FILE__).'/lang/fra.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));





// Set some content to print
$html = "<div style=\"text-align:center\">        
<h1> ".$message["titre"]." </h1><br>
<h6>Date : ".$message["date_evenement"]." de ".$message["debut"]." à ".$message["fin"]." </h6>       
Enseignant : ".  $message["enseignant"]."
</div>        
<table border=\"1\">
        <tr style=\"background-color:lightgray\">
            <td rowspan=\"2\">Nom</td>
            <td colspan=\"2\" style=\"text-align:center\">Signatures</td>
        </tr>
        <tr style=\"background-color:lightgray;text-align:center\">
            <td>Entrée</td>
            <td>Sortie</td>
        </tr>        
";



$reponse = $db->select("tblinscriptions.idEvenement,tblinscriptions.idParticipant,user.prenom,user.nom",
                        "tblinscriptions, user",
                        "id=idParticipant AND idEvenement ='".$message["ID"]."'",true);
$nbligne=1;
//while ($participant = mysqli_fetch_array($reponse)){    
foreach($reponse as $participant){
//Lire les noms des participants
$html .= "<tr><td> ".$participant['prenom']." ".$participant['nom']."</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
//Ajouter nbligne
$nbligne ++;
}

//Complété le nombre de ligne vide à 22
for($i=$nbligne;$i<=NBLIGNEPARPAGE;$i++){
    $html .= "<tr><td></td><td></td><td></td></tr>";
}

$html .= "</table>";

//Affiche le nombre de participant
$html .= "<br/>Nombre de participant(s) : ".count($reponse);

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('inscription.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

}
?>
