<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="fr">
<head>
    
    <title id="titre"><?php echo " (".(isset($NbDemandes)?$NbDemandes:"?").") - suPProf - Enseignant - ".$PLATEAU." " ?> </title>

	
    <meta http-equiv="Content-Type" content="text/html">
    <meta charset="utf-8"/>

    <!-- script de base de l'application et css de base de l'application -->
    <script language="javascript" src="./script.js"></script>
    <link href="./styles.css" rel="stylesheet" type="text/css">
	
        
        <!--Ajouter les référence pour jquery-->
	<link type="text/css" href="./css/smoothness/jquery-ui-1.8.17.custom.css" rel="stylesheet" />
        <link type="text/css" href="./css/jquery.simplebanner.css" rel="stylesheet" />               
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
        
	<script type="text/javascript" src="./js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="./jquery/ui/jquery.ui.datepicker-fr.js"></script>	

        <script type="text/javascript" src="./js/jquery.simplebanner.js"></script>        
        
        <script>
            
        $(document).ready(function(){
        /**********************************************************
         * 
         *  INITIALISATION DE LA SOURCE D'EVENEMENT SERVEUR
         * 
         * 
         *********************************************************/            
        $('.simpleBanner').simplebanner();
                
        /**********************************************************
         * 
         *  INITIALISATION DE LA SOURCE D'EVENEMENT SERVEUR
         * 
         * 
         *********************************************************/
        //Lire l'option choisi
        var selItem = "<?= $_REQUEST['selItem'] ?>";
        if(selItem == "afficher_demandes" || selItem == "") {
            	start_auto_refresh_list();
        	} 
	else 	{
            	stop_auto_refresh_list();
        	}                
        });    
        
        
        
        
        
        $(function() {
                $( "#datepicker" ).datepicker($.datepicker.regional['fr']);
                $( "#datepicker" ).datepicker();		
        });


            
            /**
            * 
            * Fonction sse pour afficher les messages de bannière
            * 
            * @param {type} delai
            * @returns {undefined}
            * 
            * 
            */
           function sseAfficherProfs(event)
           {

               //Récupérer l'objet json de la banniere
               obj = JSON.parse(event.data);  


               //chaine
               var chaine='<b>Enseignants: </b>';

               //initialiser les noms d'enseignants
               obj.forEach(function(item){
               chaine += '<a href="mailto:' + item.email + '">';
               chaine += item.prenom + ' ';
               chaine += item.nom + ' ';
               chaine += '</a>&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;';
               });
               //on affiche les profs    
               //document.getElementById('profonline').innerHTML = chaine;
               document.getElementById('profonline').innerHTML = chaine;
           }













/**
 * Fonction pour vérifier la validation du email dans profil
 * @returns {json}
 */
function validateEmail(email) { 
    //Initialiser expression
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    //Validation avec expression régulière
    return re.test(email);
}

function validate(){
  //reset flag
  var flagmail = false;
  var flagconfirm = false;
  
    
 
  //Vérification email efface message init
  $("#resultemail").text("");
  //Lire le email a partir du champ
  var email = $("#txtEmail").val();
  //Vérfier si le email est ok
  if (validateEmail(email)) {
      //afficher ok sur champ
    $("#resultemail").text(" ok");
    $("#resultemail").css("color", "green");  
    flagmail=true;
  } else {
    $("#resultemail").text(" adresse non valide ");
    $("#resultemail").css("color", "red");
    flagmail=false;
  }
  
  
  
  //Vérification confirmation mot de passe message ini
  $("#resultconfirm").text("");
  //Lire le email a partir du champ
  var password = $("#txtPass").val();
  var confirmation = $("#txtConfirm").val();

  //Vérfier si confirme = password
  if (password == confirmation) {
      //afficher ok sur champ
    $("#resultconfirm").text(" ok");
    $("#resultconfirm").css("color", "green");
    flagconfirm=true;

  } else {
    $("#resultconfirm").text(" confirmation non valide ");
    $("#resultconfirm").css("color", "red");
    flagconfirm=false;
  }
    
    
  if(flagmail==true && flagconfirm==true)
    {        
    //Transmettre formulaire    
    $('#frmConfigurerProfil').attr('action', "?selItem=configurer_profil_db").submit();
    return false;
    } 
  else
    {   
    return true;
    }
  
  
}



        </script>

        
</head>

<body style="<?php echo (isset($stylefond)?$stylefond:"") ?>">
<div id="page"> 
    	
	<?php
	// Vérifie si l'enseignant est connecté avant d'afficher le message d'entéte
	if(isset($_SESSION['id']))
	    {
            //Initialiser le tableau des messages
            $avis = (isset($avis)?$avis:array());
            
	    // Vérifie si un message d'avis a été ajouté
	    if(count($avis)>0)
		{
		?>
                
        <div id="siteWpr">
			<div class="simpleBanner">
				<div class="bannerListWpr">
					<ul class="bannerList">
                                            <?php foreach ($avis as $message):?> 
                                                <li><?php echo $message ?></li>
                                            <?php endforeach; ?>
					</ul>
				</div>
				<div class="bannerControlsWpr bannerControlsPrev"><div class="bannerControls"></div></div>
				<div class="bannerIndicators"><ul></ul></div>
				<div class="bannerControlsWpr bannerControlsNext"><div class="bannerControls"></div></div>
			</div>
		</div>
		
	    <?php
		}
	    }
	
	?>
	
	<!--Affichage de la banniere-->
	<div id="NbDemandes"><?php echo (isset($banniere)?$banniere:"") ?></div>

	<?php echo (isset($menu)?$menu:"")?>   
        
	<!-- 	Afficher les statistiques journaliére -->
	<div id="stats"><?php echo (isset($stats)?$stats:"")?></div>
        <!-- 	Afficher les enseignants du plateau -->
        <div id="profonline"><?php echo (isset($profs)?$profs:"")?></div>
        <!--Affichage de la rubrique sélectionnée-->
        <div class="main">

            <!--Titre de la rubrique-->            
            <div style="text-align:center"> <?php echo (isset($titre)?$titre:"") ?></div>
            
            <?php if ($_REQUEST['selItem']=="" || $_REQUEST['selItem']=="ajax_afficher_demandes" ):?>
                    titire
            <!--Affichage du plan de local -->
                    <div id="plan" style="background:url('images/plan_loc_<?php echo strtolower($_SESSION['Plateau'])?>.svg');background-repeat:no-repeat;" >
                    
                    </div>  
            
                    
	            <!--Affichage de la liste-->
	            <div id="listedemandes">
                        <form name="frmDemandes" method="post" action="<?php echo $_SERVER['PHP_SELF']?>?selItem=terminer_demande">
                            <table border="0" class="ListeDemandes">
                                
                                <thead class="LigneTitre">
                                    <tr>
                                        <td class="lienref">Ref.</td>
                                        <td class="Nom">Nom </td>
                                        <td class="Poste">Poste</td>
                                        <td class="Operations">Op&eacute;rations</td>			
                                    </tr>
                                </thead>
                                
                                <tbody id="coreLstDemandes">                                    
                                    <?php echo (isset($listedemandes)?$listedemandes:"") ?>
                                </tbody>
                                
                            </table>
                        </form>
                    </div>
                    
              <?php else :?>
                    <?php echo (isset($main)?$main:"") ?>
              <?php endif;?>
	    <br/>

    <!-- Affichage de message de réussite de connexion -->
    <div id="errmsg">
	<?php echo (isset($erreur)?$erreur:"") ?>
    </div>            
            
            
	    <div id="pied">
	    	<!--Affichage de la boite de déconnexion-->
                <?php echo (isset($_SESSION['disbox'])?$_SESSION['disbox']:"")?>   
                
                 <!-- Gestion des couleurs personnalisées -->
                <div style="text-align: right; clear:both;">
                    <button onclick="$('#CustomColorPannel').slideToggle(600)">Personnaliser les couleurs</button></p>
                    <div id="CustomColorPannel" style="display:none; padding: 15">
                        <label for="cmdmyColor" style="display:inline-block; width:200px;">Ma couleur </label> <input oninput="setCustomColors()" type='color' id='cmdmyColor'><br>
                        <label for="cmdothersColor" style="display:inline-block; width:200px;">Couleur des autres </label> <input oninput="setCustomColors()" type='color' id='cmdothersColor'><br>
                        <button onclick="resetCustomColors()">Reset</button>
                    </div>
                </div>
                
              
                <script>
                    
                    //Résupération du code usager du prof
                    var login_username = "<?php echo @$_SESSION['username']; ?>";
                                       
                    document.getElementById('cmdmyColor').value = localStorage.getItem('myColor')||'#66aa66';
                    document.getElementById('cmdothersColor').value = localStorage.getItem('othersColor')||'#66aa66';
                    setCustomColors();
                    
                    
                    function setCustomColors(){
                        localStorage.setItem("myColor", document.getElementById('cmdmyColor').value)||'#66aa66';
                        localStorage.setItem("othersColor", document.getElementById('cmdothersColor').value||'#66aa66');
                        
                        var rootCSS = document.querySelector(':root');
                        rootCSS.style.setProperty('--myColor', localStorage.getItem('myColor')||'#66aa66');
                        rootCSS.style.setProperty('--othersColor', localStorage.getItem('othersColor')||'#66aa66');
                    }
                    
                    function resetCustomColors(){
                        document.getElementById('cmdmyColor').value = '#66aa66';
                        document.getElementById('cmdothersColor').value = '#66aa66';
                        setCustomColors();
                    }
                    
                </script>
                <style>
                    tr.EnCours.<?php echo $_SESSION['username']; ?>{
                        background-color:var(--myColor);
                    }

                    /* couleur de la demande en cours */
                    .EnCours{
                        background-color:var(--othersColor);
                    }
                    
                    #CustomColorPannel {
                        display:none;
                        padding:15px;
                        border-radius: 5px;
                        background-color:#999;
                        float:right;
                    }
                </style>
                <!-- Gestion des couleurs des demandes FIN -->
                
            </div>
        
        </div>

</body>
</html>
