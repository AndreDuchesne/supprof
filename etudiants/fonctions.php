<?php

				/******************************************
				 *					  *
				 *       LIBRAIRIE DES FONCTIONS          *
				 *  					  *
				 ******************************************/





/**
*
*  FONTION PRINCIPALE POUR AFFICHER LA LISTE DES DEMANDES
*
**/

//Fonction pour afficher la liste des contacts
function afficher_demandes()
{

//Définir l'affichage des contacts
global $lien, $BD, $PLATEAU,$NbDemandes, $banniere;


//Lire la date et l'heure actuelle
$maintenant = date("Y-m-d",time());


$clause ="HeureInscription >='$maintenant' 
          AND (BINARY tbldemandes.cours = BINARY tblcours.numeroCompetence) 
          AND (Etat ='En attente' OR Etat ='En cours')
          AND tbldemandes.Plateau = '$PLATEAU'  
          ORDER BY ID ASC";

$db = new Database();
$reponse = $db->select('ID,NomEleve,Local,Poste,TypeDemande,HeureDebut,Etat,Cours,Bloc,url,titreCompetence',
                        'tbldemandes, tblcours',
                        $clause,true);



	$nbligne = count($reponse);

	// Afficher le nombre de demande en cours dans la baniére
	$banniere = $PLATEAU."(".$nbligne.")";
	
	// Afficher le nombre de demande dans le titre de page
	$NbDemandes = $nbligne;
        
    //Initialiser la variable main
    $main = (isset($main )?$main :"");

	
	//Débuter la création du formulaire
	$main .= "<form name=\"frmDemandes\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."?selItem=terminer_demande\">\n";
	
	//Débuter l'affichage du tableau des demandes
	$main .= "<table border=\"0\" id=\"ListeDemandes\"  class=\"ListeDemandes\">";
	
	// Affiche la ligne titre du tableau
	$main .=   "<tr class=\"LigneTitre\">
		        <td class=\"ID\">ID</td>
		        <td class=\"Nom\">Nom </td>
		        <td class=\"Local\">Local</td>
		        <td class=\"Poste\">Poste</td>
		        <td class=\"Operations\">Annuler</td>			
		    </tr>";
	
	$main .= "<tbody id=\"coreLstDemandes\">";
        

	//Boucler pour chaque ligne allant de 1 jusqu'é nombre de ligne
	//for($i=1;$i<=$nbligne;$i++)
    foreach ($reponse as $ligne) 
		{

		//Récupérer UNE ligne d'information dans un tableau associatif
		//$ligne = mysqli_fetch_array($reponse);
		
		
		//Récupérer le id de la demande
		$ID = $ligne['ID'];
                

		
		//Récupérer le nom de l'éléve
		$NomEleve = htmlentities($ligne['NomEleve']);
		
		//Récupérer le local
		$Local = $ligne['Local'];

		//Récupérer le numéro du poste
		$Poste = $ligne['Poste'];

        //Récupérer le type de demande
        $TypeDemande = $ligne['TypeDemande'];

		
		/**************************************************************
		 * Débuter affichage d'une demande
		 *
		 ***/

		//Modificateur de style en fonction de l'état et du type de demande
        // Enlever le style par défaut
        $styledemande = "class=\"";			
        //En cours, En attente
        if($ligne['Etat']=="En cours")
		    {
			// Effacer le style pour les demandes en cours
			$styledemande .= "EnCours";
		    }
		elseif($ligne['Etat']=="En attente")
		    {
			// Appliquer un style pour la demande en attente
			$styledemande .= "EnAttente";
		    }
      //Refermer la classe de style
      $styledemande .= "\"";





		
		// Ajouter le style de de rangée
		$main .= "\n<tr $styledemande>\n";
			
			
		// Réduire l'affichage é 2 digit
		$IDDIGIT = "X".substr($ID,-2);			
		
			//Afficher le numéro de la demande
			$main .= "<td class=\"ID\">$IDDIGIT</td>\n";
			
			//Afficher le nom de l'éléve
			$main .= "<td class=\"Nom detaildemande\">".$NomEleve."<span class=\"".$TypeDemande."\">".$TypeDemande."</span></td>\n";
			
			//Afficher le numéro de local
			$main .= "<td class=\"Local\">$Local</td>\n";
			
			//Afficher le numéro de la demande
			$main .= "<td class=\"Poste\">$Poste</td>\n";

			//Afficher les icénes pour la gestion des demandes
			$main .= "<td class=\"Operations\">";
			
			// Afficher deux bouton sur la premiére ligne 

			    $main .= "<a href=\"index.php?selItem=supprimer_demande&ID=".$ID."\">
					<img title=\"Annuler une demande\" class=\"IconeSupprimer\" src=\"images/supprimer.png\"/>
				      </a>";

			$main .= "</td>";
			
		//Afficher la fin du bloc lien
		$main .="\n</tr>\n";		
		/**
		 * Terminer l'affichage de la liste de liens
		 *
		 **************************************************************/
		

		}
                //REfermer le core de la liste
                $main .= "</tbody>";
                
		//Refermer le tableau de la liste
		$main .= "</table>";
		
	//Refermer le formulaire
	$main .= "</form>\n";
        
        

	//} #fin du for



//Retourner le tableau des contacts
return $main;
}



/**
 * Définir le style de fond pour chaque plateau
 */

function definir_stylefond(){
    //Récupérer le style de fond du plateau sélectionné
    $db = new Database();
    $reponse = $db->select("stylefond","tblplateaux","plateau='".$_SESSION['Plateau']."'",true);
    return $reponse[0]['stylefond'];
}



/**
*
*   AFFICHER FORMULAIRE POUR UNE NOUVELLE DEMANDE
*
**/

//Fonction pour ajouter un contact é l'aide d'un formulaire
function ajouter_demande()
{
        // Recouvrir les variables globales
        global $lien, $PLATEAU,$NbDemandes;

    $NbDemandes = "?";
                
    //Ajouter une nouvelle demande dans un formulaire
    $main = "<div class=\"ListeDemandes\">
				<img src=\"images/checklist.png\" id=\"cheklistEleve\">
				<form  id=\"formDemandeEleve\" name=\"frmAjouter\"  action=\"".$_SERVER['PHP_SELF']."?selItem=ajouter_demande_db\" method=\"POST\">\n
					<fieldset>
					<legend>D&eacute;tail de la demande</legend>";

    $clause = "plateau = '".$PLATEAU."' ORDER BY numeroCompetence";
    $db = new Database();
    $lignes = $db->select('*','tblcours',$clause,true);
    

	
    // Affiche la liste pour les cours du plateau		
    $main.= "<div>
		<select onfocus=\"ajaxSetLstBloc(this.value)\" onchange=\"ajaxSetLstBloc(this.value)\" name=\"lstCours\">";
       

    //Ajouter une demande vide
    $main .= "	<option value=\"\">S&eacute;lectionner le cours</option>\n";
    
    // Boucler pour chaque locaux
    foreach($lignes as $cours)
                // Ajouter le local é la liste
        	$main .= "	<option value=\"".$cours['numeroCompetence']."\">(".$cours['numeroCompetence'].")  ".$cours['titreCompetence']."</option>\n";
    
    // Finir la liste de locaux
	$main .= "</select>
	         </div>";
    
    
        
		
        
        
        // Affiche la liste pour les cours du plateau		
    $main.= "<div>
		<select name=\"lstBloc\"  id=\"lstBloc\">";
       

                // Ajouter le local é la liste
        	$main .= "	<option value=\"\">S&eacute;lectionner le sujet</option>\n";
    
                // Finir la liste de locaux
    $main .=    "</select>
	         </div>";        

    
    
    //Récupérer la liste des locaux du plateau
    $db = new Database();
    $reponse = $db->select("locaux","tblplateaux","plateau='".$PLATEAU."'",true);
    $tablocaux = explode(",",$reponse[0]['locaux']); 
        
        
    

    // Affiche la liste pour le type de demande	
    $main.= "<div><select name=\"lstTypeDemande\">";
        $main .= "	<option value=\"\">Sélectionner le type de demande</option>\n";
	    $main .= "	<option value=\"Validation\">Validation</option>\n";
	    $main .= "	<option value=\"Explication\">Explication</option>\n";        
	    $main .= "</select>";
	$main .=" </div>";




    
    // Affiche la liste pour la note		
    $main.= "<div>
		<select name=\"lstLocal\">";
       $main .= "	<option value=\"\">Numéro du local</option>\n";
    // Boucler pour chaque locaux
    foreach($tablocaux as $local)
	// Ajouter le local é la liste
	$main .= "	<option value=\"$local\">$local</option>\n";

    // Finir la liste de locaux
	$main .= "</select>";


	$main .=" </div>";





    // Lien sur le plan de classe
    //   $main .= "<a style=\"font-size:10pt\" href=\"./images/plan_".strtolower($PLATEAU).".html\" target=\"_blank\">Plan de classe(s)</a>";            

    
      //Affiche le plan de la classe en svg
        $main .= "Plan : <a href=\"#\" onclick=\"afficher_plan()\">&nbsp;&nbsp;&#x2714;&nbsp;&nbsp;</a>  <a href=\"#\" onclick=\"cacher_plan()\"> &nbsp;&nbsp;&#x2716;&nbsp;&nbsp; </a>
                  <div id=\"plan\" style=\"display: none;\" class=\"svg-view\">
				    <iframe id=\"planloc\" name=\"planloc\" onload=\"initIframe()\" width=510 height=500  frameborder=0  marginwidth=0 marginheight=0 scrolling=no allowtransparency=\"false\" style=\"background:#ffffff;\" srcdoc='".htmlspecialchars(afficher_plan($_SESSION['Plateau']))."'>
                    </iframe> 
			      </div>";
        



			    
    $main .= "<div>";
		
		// boucle pour afficher une liste de numéro de poste	    
		$main .= "<select id=\"txtPoste\" name=\"txtPoste\">";
		//Ajouter le poste enseignant à la liste de sélection
		$main .= 	"<option value=\"0\">Poste Enseignant</option>";                

        $clause = "plateau = '".$PLATEAU."' ORDER BY nom, numero+0 ASC";
        $db = new Database();
        $reponse = $db->select('nom, numero','tblpostes',$clause,true);

        foreach($reponse as $poste){

			$main .= 	"<option value=\"".$poste['numero']."\">".$poste['nom']." ".$poste['numero']."</option>";
		}
		$main .=	"</select> ";
    $main .="</div>";
	
	
  $main .="<div>
				<input type=\"password\"  placeholder=\"Numéro de fiche\" size=\"15\" name=\"txtNoFiche\" id=\"txtNoFiche\" onblur=\"ajaxChkFiche(document.getElementById('txtNoFiche').value)\">
			</div>";
       
	// Affiche un champ pour les questions		
    $main.= "<div>
                    <input list=\"lstquestions\"   type=\"text\" name=\"question\" size=\"50\" id=\"question\" placeholder=\"Précision sur votre question (optionnel)\" maxlength=\"50\"/>     
                    <datalist id=\"lstquestions\">
                       <option value=\"INSCRIPTION \">
                       <option value=\"Question sur \">
                       <option value=\"Explication sur \">
                       <option value=\"Correction de \">
                       <option value=\"V&eacute;rification \">
                     </datalist>             

	         </div>";     
    
    
    

$main   .=      "<div>
		<input type=\"submit\" name=\"cmdDemande\" value=\"Ajouter\" >
		</div>
		
		<div  class=\"retour\">
			<a href=\"".$_SERVER['PHP_SELF']."?selItem=afficher_demandes\">Retour</a>
		</div>
	    </fieldset>   
	  </form>
	  
	  <div style=\"clear:both; font-size:12px;margin-left:45px;\">
				<a href=\"https://csdddemo.sharepoint.com/:w:/s/courssi-5385/EfiDWoGRIoZJhmd1-A9hgfYBi6IaYZ0LbQ0s4j9eq9b_HQ?e=9gV3Tu&download=1\">
					Télécharger cette liste de vérification
				</a>
			</div>
	  
	  </div>";
        
//Retourner l'affichage du contact
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
*  AJOUTER LE LIEN DANS LA BASE DE DONNéES
*
**/

//Fonction pour enregistrer les nouvelles données du contact dans la base de données
function ajouter_demande_db()
{
// Recouvrir le nom de la base de données et l'identifiant de connexion
global $lien, $BD, $message, $DELAI_AFFICHAGE,$PLATEAU;

//Instance mysqli
$db=new Database();

// Récupérer les champs é partir du formulaire
//$Nofiche = mysql_real_escape_string($_REQUEST['txtNoFiche']);
$Nofiche = $db->escapeString(isset($_REQUEST['txtNoFiche'])?$_REQUEST['txtNoFiche']:"");
$Local=$db->escapeString(isset($_REQUEST['lstLocal'])?$_REQUEST['lstLocal']:"");
$Poste=$db->escapeString(isset($_REQUEST['txtPoste'])?$_REQUEST['txtPoste']:"");
$Cours = $db->escapeString(isset($_REQUEST['lstCours'])?$_REQUEST['lstCours']:"");
$TypeDemande = $db->escapeString(isset($_REQUEST['lstTypeDemande'])?$_REQUEST['lstTypeDemande']:"");

if(isset($_REQUEST["lstBloc"]) && strlen($_REQUEST["lstBloc"])>=3){
//Découper le numéro de bloc et l'url
$tab = explode(";", $_REQUEST['lstBloc']);
$Bloc = $tab[0];
$url = $tab[1];
$idtblblocs = $tab[2];
}

$Question = $db->escapeString(strip_tags(addslashes(isset($_REQUEST['question'])?$_REQUEST['question']:"")));
$HeureInscription = date("Y-m-d H:i:s",time());
$HeureDebut = "0000-00-00 00:00:00";
$HeureFin = "0000-00-00 00:00:00";
$IP = $_SERVER['REMOTE_ADDR'];

//Formuler la requéte pour récupérer le nom de l'usager
$clause = "fiche='".$Nofiche."' AND niveau='etudiant'";


//Retrouver l'usager
$reponse = $db->select('prenom, nom','user',$clause,true);



if(isset($reponse[0])){
//Récupérer la ligne
$ligne = $reponse[0];
}else{
    	    //Affiche le message d'erreur et termine
            $message =  "<div class=\"erreur\">Une erreur est survenue:<p/>
            <b>Num&eacute;ro d'erreur :</b>18<br/>
            <b>Description :</b> Num&eacute;ro de fiche ou nom d'usager invalid. Veuillez saisir toutes les informations correctement
            </div><br/>
            <div  class=\"retour\">
                <img onclick=\"javascript:history.back()\" src=\"images/retour.png\"/><br/>Retour
            </div>";
            return $message;
}



//Vérifier si le tableau contient la clé 0

// Coller le prénom et le nom pour faire le nom complet
$Nom = $ligne['prenom'];
$Prenom = $ligne['nom'];
$NomComplet = $Prenom . " " . $Nom;

//Filtrage
$NomComplet = mysqli_real_escape_string($lien,$NomComplet);
//$filtrelocal = "/^[a-zA-Z]{1}-[0-9]{3}$/i"; //filtre expression régulière pour le local ex: A-121
$filtrenumeric = "/^[0-9]{0,3}$/i";   //filtre expression régulière pour le poste ou bloc 0 à 999







//Vérifie si le prénom ou le nom est valid
if(strlen($NomComplet)>1)
{
 if(preg_match($filtrenumeric,$Poste)!=1 /*|| preg_match($filtrelocal,$Local)!=1*/)
                {
                //Affiche le message d'erreur et termine
                $message =  "<div class=\"erreur\">Une erreur est survenue:<p/>
                        <b>Num&eacute;ro d'erreur :</b>9<br/>
                        <b>Description :</b> Vous devez s&eacute;lectionner un num&eacute;ro de poste et de local valide. 
                                                Utiliser le plan classe pour s&eacute;lectionner votre poste ainsi que le local. 
                        </div><br/>
                        <div  class=\"retour\">
                          <img onclick=\"javascript:history.back()\" src=\"images/retour.png\"/><br/>Retour
                        </div>
                        ";

                }
elseif($Cours=="" || $Bloc == "" || preg_match($filtrenumeric,$Bloc)!=1)
		{

                //Affiche le message d'erreur et termine
                $message =  "<div class=\"erreur\">Une erreur est survenue:<p/>
                                <b>Num&eacute;ro d'erreur :</b>27<br/>
                                <b>Description :</b> Vous devez s&eacute;lectionner le cours que le num&eacute;ro du bloc du sujet de votre question.
                                </div><br/>
                                <div  class=\"retour\">
                                  <img onclick=\"javascript:history.back()\" src=\"images/retour.png\"/><br/>Retour
                                </div>
                                ";	
		} 
                
			
	
    //Si tous les champs sont complétés correctement alors
    elseif(/*preg_match($filtrelocal,$Local) &&*/ preg_match($filtrenumeric,$Poste) && preg_match($filtrenumeric,$Bloc) && $Nofiche !="" && $Cours != "")
        {

            
    //Initialiser la requête
        $data = [
            'NomEleve'=>$NomComplet,
            'NoFiche'=>$Nofiche,
            'Local'=>$Local,
            'Poste'=>$Poste,
            'TypeDemande'=>$TypeDemande,
            'Cours'=>$Cours,
            'Bloc' => $Bloc,
            'Question' => $Question, 
            'url' => $url,
            'idtblblocs' => $idtblblocs,
            'HeureInscription'=>$HeureInscription,
            'HeureDebut'=>$HeureDebut,
            'HeureFin'=>$HeureFin,
            'CodeUsager'=>'',
            'Etat'=>'En attente',
            'Plateau'=>$PLATEAU,
            'IP'=>$IP
        ];
        //Inscrire la demande
        $reponse = $db->insert('tbldemandes',$data);


       
        //Réafficher la liste des demandes
        header("Location:".$_SERVER['PHP_SELF']."?selItem=afficher_demandes");
        
        }   


}

else{


    //Affiche le message d'erreur et termine
    $message =  "<div class=\"erreur\">Une erreur est survenue:<p/>
                    <b>Num&eacute;ro d'erreur :</b>18<br/><br/>
                    <b>Description :</b> <br/> Num&eacute;ro de fiche ou nom d'usager invalid. Veuillez saisir toutes les informations correctement
                    <br/>Le prénom ou le nom d'usager n'a pas été initialisé dans le système.</div><br/>
                    <div  class=\"retour\">
                        <img onclick=\"javascript:history.back()\" src=\"images/retour.png\"/><br/>Retour
                    </div>";
        
}


//Retourner message
return $message;
}



























/****
 *
 *  FONCTION POUR CONFIRMER TOUS LES LIENS SéLECTIONNéS
 *
 ***********/

function supprimer_demande($id)
{
//Recouvrir variable de connexion
global $lien,$DB;
       
$clause = "ID = $id";
$db = new Database();
$reponse = $db->select('*','tbldemandes',$clause,true);


//Débuter l'affichage du formulaire de confirmation
$liste = "\n<form name=\"frmSupprimer\" action=\"".$_SERVER['PHP_SELF']."?selItem=supprimer_demande_db\" method=\"post\">";
	    
//Affiche le tableau des données
$liste .= "\n<table class=\"ListeDemandes\" border=0 align=\"center\" >
	    \n<tr class=\"LigneTitre\" style=\"background-color:#444444;color:white\">
		<td class=\"ID\">ID</td>
		<td class=\"Nom\">Nom</td>
		<td class=\"Local\">Local</td>
		<td class=\"Poste\">Poste</td>
	    </tr>";

    //$ligne = mysqli_fetch_array($reponse);
    //Récupérer la ligne
    $ligne = $reponse[0];
    
    //Afficher une ligne de données
    $liste .= "\n<tr>
		    <td>
			".$ligne['ID'].")
		    </td>
		    
		    <td>
			".$ligne['NomEleve']."
		    </td>
		    
		    <td>
			".$ligne['Local']."
		    </td>
		    
		    <td>
			".$ligne['Poste']."
		    </td>
		    
		</tr>";    



$liste .= "\n</table>
		<div class=\"erreur\" style=\"width:100%;font-size:24pt\">
		<h3> Attention ! </h3><br/>
		Pour supprimer une demande, vous devez entrer votre num&eacute;ro de fiche ou votre code enseignant.<br/>
		<br/>
		</div>
		<input type=\"password\" name=\"txtCodeUsager\"/>\n
		<input type=\"hidden\" name=\"ID\" value=\"".$ligne['ID']."\"/>\n
		\n<input type=\"submit\" name=\"cmdSupprime\" value=\"SUPPRIMER\"/>
		<div  class=\"retour\">
		<a href=\"".$_SERVER['PHP_SELF']."?selItem=afficher_demandes\">
		<img src=\"images/retour.png\"/><br/>Retour</a>
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
function supprimer_demande_db($id,$CodeUsager)
{
// Recouvrir les valeurs de connexions
global $lien, $BD, $titre;

$clause = "ID = $id";
$db = new Database();
$reponse = $db->select(' CodeUsager, NoFiche','tbldemandes',$clause,true);


$ligne = $reponse[0];

// Vérifier l'état
if($ligne['NoFiche']==$CodeUsager)
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





function recuperer_avis($id){
    
//Définir l'affichage des contacts
global $lien, $BD, $NbDemandes, $banniere;

$db = new Database();
$reponse = $db->select("ID,titre,description,lien,local,date_evenement,debut,fin",
                        "tblmessages",
                        "ID=".$id." ORDER BY date_evenement DESC",
                        true);

$tableau = $reponse;

//Retourner le message d'afin
return $tableau;
}







/**
 *
 *  FONCTION POUR AFFICHER LE MESSAGE D'ENTETE
 *
 *
 *
 *
 * */
function afficher_avis_non_perime(){
    global $lien, $DB;
    

	// Recouvrir les détails du message d'avis
	$requete = "SELECT * FROM tblmessages WHERE date_evenement >= '".date("Y-m-d")."' ORDER BY date_evenement DESC ";

    $clause = "date_evenement >= '".date("Y-m-d")."' ORDER BY date_evenement DESC ";

    $db = new Database();

    $messages = $db->select('*','tblmessages',$clause,true);

    
        //Initialiser l'avis
        $avis = array();
        
        //Récupérer le message d'avis rajouter les boutons
       // while($message = mysqli_fetch_array($reponse)){
      foreach($messages as $message){
        
            //SI délaiaffichage = 0 ALORS
            if(intval($message['delaiaffichage'])==0){
                // Affiche formulaire de confirmation de l'inscription
                $ligne = "<a href=\"?selItem=afficher_inscription&ID=".$message['ID']."\"  title=\"".$message['description']."\n".$message['date_evenement']."\n de ".date("H:i",  strtotime($message['debut']))." a ".date("H:i",  strtotime($message['fin']))."\">".$message['titre']."</a>\n";
                //Ajouter la ligne dans la bannière
                array_push($avis, $ligne);
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
                    // Affiche formulaire de confirmation de l'inscription
                    $ligne = "<a href=\"?selItem=afficher_inscription&ID=".$message['ID']."\"  title=\"".$message['description']."\n".$message['date_evenement']."\n de ".date("H:i",  strtotime($message['debut']))." a ".date("H:i",  strtotime($message['fin']))."\">".$message['titre']."</a>\n";
                    //Ajouter la ligne dans la bannière
                    array_push($avis, $ligne);                    
                }
            }
        
        }
 
	// Retourner le message d'avis avec lien
	return $avis;
}








/***
 * 
 *  FONCTION POUR AFFICHE LA CONFIRMATION DE L'INSCRIPTION
 *  À L'ÉVÉNEMENT
 * 
 * 
 */
function afficher_inscription(){
//Initialiser les varaibles de session et de conexion    
global $lien, $BD, $NbDemandes, $banniere;

$db = new Database();
$reponse = $db->select("tblmessages.ID,titre,description,lien,local,date_evenement,debut,fin, CONCAT(user.prenom,' ',user.nom) AS enseignant",
                        "tblmessages, user",
                        "idenseignant = user.id AND tblmessages.ID=".$_REQUEST["ID"],
                        true);


       // Récupérer le titre du message dans un tableau
        $tableau = $reponse[0];
         //Ajouter une nouvelle demande dans un formulaire
        $main = "<div class=\"divDetailEvent\">
                 <form name=\"frmDetailEvent\"  action=\"".$_SERVER['PHP_SELF']."\" method=\"POST\">\n
                  
                    <fieldset>
                            <legend>D&eacute;tail de l'&eacute;v&eacute;nement</legend>
                            <table class=\"frmDetailEvent tblDetailEvent\">";

        
        
        
        // Affiche la liste pour les cours du plateau		
        $main.= "<tr>
                    <td>
                        Titre :
                    </td>

                    <td class=\"frmDetailEvent titre\">
                        ".$tableau['titre']."
                        <input type=\"hidden\" name=\"idEvenement\" readonly value=\"".$tableau['ID']."\"/>
                        <input type=\"hidden\" name=\"selItem\" readonly value=\"\" />
                    </td>
                 </tr>";
                    



        // Affiche un champ pour les détails de l'événement
        $main.= "<tr>
                    <td colspan=2>
                        Description :<br/>
                        
                        <div class=\"frmDetailEvent description\">
                        ".stripslashes($tableau['description'])."
                        </div>
                        
                     </td>
                </tr> 

                <tr class=\"frmDetailEvent enseignant label\">
                   <td>
                       Enseignant :
                   </td>
                   <td class=\"frmDetailEvent enseignant value\">
                       ".  $tableau['enseignant']."
                   </td>
                 </tr>

                <tr class=\"frmDetailEvent dateevent label\">
                   <td>
                       Date :
                   </td>
                   <td class=\"frmDetailEvent dateevent value\">
                       ".$tableau['date_evenement']."&nbsp;&nbsp;&nbsp;<span class=\"frmDetailEvent dateevent label\">  de &nbsp;</span>".date("H:i",  strtotime($tableau['debut'])). "<span class=\"frmDetailEvent dateevent label\">&nbsp; &agrave; &nbsp;</span>".date("H:i",strtotime($tableau['fin'])) ." 
                           <a class=\"lienics\" href=\"index.php?selItem=generer_ics&idEvenement=".$tableau['ID']."\" title=\"Ajouter cet événement a votre agenda\">
			    <img src=\"images/calendrier.png\">
			</a>
                   </td>
                 </tr>

                <tr class=\"frmDetailEvent endroit label\">
                   <td>
                       Endroit :
                   </td>
                   <td class=\"frmDetailEvent endroit value\">
                       ".$tableau['local']." 
                   </td>
                 </tr>";

    if(strlen($tableau['lien'])>=6){
            $main .="<tr class=\"frmDetailEvent lien label\">                
                        <td>
                            Lien/R&eacute;f. :
                        </td>

                         <td class=\"frmDetailEvent lien value\">
                             <span>".$tableau['lien']." &nbsp;&nbsp;&nbsp;</span>
                             <br/>   
                             <span >                        
                                 <a class=\"frmDetailEvent lien logo\" href=\"".$tableau['lien']."\"  target=\"_new\" title=\"Lien référence complémentaire sur l'événement\">
                                     <img src=\"images/url.png\">
                                 </a>   
                             </span>
                         </td>
                        </tr>";
                    }
                    
            $main.="<tr><td colspan=2>
                        <spcan class=\"frmDetailEvent avisinscription label\">Inscription :</span><br/>";
                
                        //Vérification du message de l'opération complétée  
                        switch (isset($_REQUEST['operation'])?$_REQUEST['operation']:""){
                            case 'suppression':
                                                    $main .= "<div class=\"frmDetailEvent avisSuppression value\">
                                                                L'inscription &eacute;t&eacute; supprim&eacute;e                                                                
                                                                </div>";                                
                                                                break;
                            case 'ajout':
                                                    $main .="<div class=\"frmDetailEvent avisAjout value\">
                                                                L'inscription a &eacute;t&eacute; compl&eacute;t&eacute;e                                                               
                                                                </div>";
                                                                break;

                            case 'erreurfiche':
                                                    $main .="<div class=\"frmDetailEvent avisErreur value\">
                                                                <b>Erreur !</b>
                                                                <p>Vous devez entrer votre numéro de fiche pour vous inscrire a cet événement</p>                                                                 
                                                                </div>";
                                                                break;                                                                



                            default :
                                                    $main .= "<div class=\"frmDetailEvent avisInscription value\">
                                                                Pour vous inscrire ou supprimer votre inscription &agrave; cet &eacute;v&eacute;nement veuillez inscrire 
                                                                votre num&eacute;ro de fiche et appuyer sur le bouton 
                                                                \"Enregistrer\". Pour ajouter l' &eacute;v&eacute;nement &agrave; votre 
                                                                agenda, apuyez sur le calendrier.                                                                
                                                                </div>";
                            }




                        
                        
            $main.="</td>
                </tr> 


                </tr>
                <tr>
                   <td>
                       Fiche :
                   </td>
                   <td>
                       <input type=\"password\" size=\"15\" name=\"txtNoFiche\">
                   </td>
                 </tr>
                 

                 <tr>
                   <td colspan=\"2\">
                   <br/><br/>
                   <input type=\"button\" onclick=\"ajouter_inscription(document.frmDetailEvent.idEvenement.value,document.frmDetailEvent.txtNoFiche.value)\" class=\"frmDetailEvent cmdEnregistrer\" name=\"cmdEnregistrer\" value=\"Enregistrer\" >
                   <input type=\"button\" onclick=\"supprimer_inscription(document.frmDetailEvent.idEvenement.value,document.frmDetailEvent.txtNoFiche.value)\" class=\"frmDetailEvent cmdSupprimer\" name=\"cmdSupprimer\" value=\"Supprimer\" >
                   <br/>
                  </tr>
               </table>
                   \n
                   <div  class=\"retour\">
                   <a href=\"".$_SERVER['PHP_SELF']."?selItem=afficher_demandes\">
                   <img src=\"images/retour.png\"/><br/>Retour</a>
                   </div>
               </fieldset>   
             </form>
             </div>";
        
 
        return $main;
	//}
          
}










/**
 * 
 *   FONCTION POUR AJOUTER UNE INSCRIPTION A UN EVENEMENT
 * 
 */
function ajouter_inscription_db()
{
// Recouvrir le nom de la base de données et l'identifiant de connexion
global $lien, $BD;

//instance mysqli
$db = new Database();
// Récupérer les champs a partir du formulaire
$txtNoFiche = $db->escapeString($_REQUEST['txtNoFiche']);
$idEvenement=$db->escapeString($_REQUEST['idEvenement']);
//Récupérer le id de l'usager
$reponse = $db->select("*","user","fiche='".$txtNoFiche."'");
//Vérifie si numéro de fiche est valide si non erreur
if(count($reponse)==0){
        //Réafficher le formulaire avec une erreur no fiche
        header("Location:".$_SERVER['PHP_SELF']."?selItem=afficher_inscription&operation=erreurfiche&ID=".$idEvenement);    
}

//Récupérer infor usager
$usager = $reponse[0];
//Inscrire l'événement
$data = array(
            'idParticipant'=>$usager["id"],
            'idEvenement'=>$idEvenement
                );
$reponse = $db->insert("tblinscriptions",$data);

//Réafficher la liste des demandes
header("Location:".$_SERVER['PHP_SELF']."?selItem=afficher_inscription&operation=ajout&ID=".$idEvenement);

}






/***
 *   FONCTION POUR SUPPRIMER UNE INSCRIPTION 
 *   A UN EVENEMENT
 * 
 * 
 * 
 * 
 * 
 */
function supprimer_inscription_db(){
// Recouvrir le nom de la base de données et l'identifiant de connexion
global $lien, $BD;

//instance mysqli
$db = new Database();
// Récupérer les champs a partir du formulaire
$txtNoFiche = $db->escapeString($_REQUEST['txtNoFiche']);
$idEvenement=$db->escapeString($_REQUEST['idEvenement']);
//Récupérer le id de l'usager
$reponse = $db->select("*","user","fiche='".$txtNoFiche."'");
//Vérifie si numéro de fiche est valide si non erreur
if(count($reponse)==0){
        //Réafficher le formulaire avec une erreur no fiche
        header("Location:".$_SERVER['PHP_SELF']."?selItem=afficher_inscription&operation=erreurfiche&ID=".$idEvenement);    
}

//Récupérer le id de l'usager
$usager = $reponse[0];
$reponse = $db->delete("tblinscriptions","idParticipant = '".$usager['id']."' AND idEvenement='".$idEvenement."'");

//Réafficher la liste des demandes
header("Location:".$_SERVER['PHP_SELF']."?selItem=afficher_inscription&operation=suppression&ID=".$idEvenement);        

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
UID:avis@suivi.lan
CREATED:" . date("Ymd\THis", strtotime($start)) . "
DESCRIPTION:" . $description . "
LAST-MODIFIED:" . date("Ymd\THis", time()) . "
LOCATION:" . $location . "
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY:" . $name . "
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR";
    }

    function save() {
        file_put_contents("../info.ics", $this->data);
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











/************************************************
 * 
 * 
 *  A J A X   setLstBloc
 * 
 * 
 ************************************************/
function ajaxSetLstBloc($cours)
{
    
    
    //Définir l'affichage des contacts
    global $lien, $BD, $PLATEAU;


    $db = new Database();
    $reponse = $db->select("*",
                           "tblblocs",
                           "cours = '".$cours."' AND plateau = '".$PLATEAU."' ORDER BY numeroBloc ASC");



        $tableau = array();
    
        //while($item = mysqli_fetch_array($reponse))
        foreach($reponse as $item)
                {   
                //$item['sujet'] = $item['sujet'];
                //$item[2] = $item[2];  
                array_push($tableau, $item);            
                }

        //print_r($tableau);        
        echo json_encode($tableau);
        die();
        //}    
    
    
    
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
    global $lien, $BD, $NbDemandes, $banniere, $stats ,$PLATEAU; 
  

    
//Définir un datapacketb les données de la demande
    $rows = array();


//Lire la date et l'heure actuelle
    $maintenant = date("Y-m-d", time());

$db = new Database();
$reponse = $db->select("ID,NomEleve,Local,Poste,TypeDemande,HeureDebut,Etat,Cours,Bloc,url,titreCompetence",
                       "tbldemandes, tblcours",
                       "HeureInscription >='".$maintenant."' 
                       AND (BINARY tbldemandes.cours = BINARY tblcours.numeroCompetence) 
                       AND (Etat ='En attente' OR Etat ='En cours') 
                       AND tbldemandes.Plateau = '$PLATEAU' 
                       ORDER BY ID ASC",
                       true); 

               
        
        //Lire le nombre de ligne obtenues dans la réponse du serveur mysqli 
        $NbDemandes = count($reponse);
        
        //Boucler pour chaque ligne allant de 1 jusqu'é nombre de ligne
        //for ($i = 1; $i <= $NbDemandes; $i++) {
        foreach($reponse as $ligne){              
            //Récupérer UNE ligne d'information dans un tableau associatif
            //$ligne = mysqli_fetch_assoc($reponse);


            // Récupérer le numéro de ID
            $ID = $ligne['ID'];   
            
            // Récupérer le numéro de fiche de l'éléve
            $Fiche = $ligne['NoFiche'];

            //Récupérer le nom de l'éléve
            $NomEleve = $ligne['NomEleve'];
            
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

            /*$TitreCompetence = $ligne['titreCompetence'];

            //Récupérer le numéro du bloc 
            $Bloc = $ligne['Bloc'];
            
            //Récupérer l'url
            $url = $ligne['url'];*/
                          
            //Créer une ligne vide pour cumuler tableau
            $row = array();
            
            //Ajouter la ligne de tableau scalaire      
            array_push($row, $ID, $Fiche,  $NomEleve,$NbDemandeEleve,$Local,$Poste,$Etat,$Cours,$TitreCompetence,$url,$Bloc,$TypeDemande);            
                        
            //Ajouter la ligne dans le tableau
            array_push($rows, $row);           
        }
    
    //Créer le datapacket pour transmettre à la fonction javascript
    $bloc['demandes']=$rows;
    $bloc['NbDemande']=$NbDemandes;
    $bloc['plateau']=$PLATEAU;
    

            
    //Retourcer le packet de donnée
    die (json_encode($bloc));
    //}
}





/**
*
*  VÉRIFIER NUMÉRO DE FICHE VALIDE
*
**/

//Fonction pour enregistrer les nouvelles données du contact dans la base de données
function ajaxChkFiche($fiche)
{
//Empèecher l'affichage des erreur    
ini_set("display_errors", 0);

// Recouvrir le nom de la base de données et l'identifiant de connexion
global  $BD, $lien;

//initialiser réponse a null
$tmp = null;

// Récupérer les champs é partir du formulaire
$db = new Database();
$reponse = $db->select("prenom, nom",
                       "user",
                       "fiche='".$db->escapeString($_REQUEST['fiche'])."' AND niveau='etudiant'",true);


if(count($reponse)!=0){
    $tmp = true;
    }
else{
    $tmp = false;
}

// Retourner la réponse à la fonction ajax
die($tmp);
    
}


?>
