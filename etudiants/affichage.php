<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html lang="fr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
     

        <title id="titre"><?php echo " (" . (isset($NbDemandes)?$NbDemandes:"?") . ") - suPProf - " . $PLATEAU . " " ?> </title>

        <link href="styles.css" rel="stylesheet" type="text/css">
        <link type="text/css" href="./css/jquery.simplebanner.css" rel="stylesheet" />  


        <script type="text/javascript" src="./js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="./js/jquery.simplebanner.js"></script>        

        <script>
            /**
             * Initialisation de la banière
             * @param {type} param
             */

            $(document).ready(function () {
            /**********************************************************
             * 
             *  INITIALISATION DE LA BANIÈRE
             * 
             * 
             *********************************************************/
                $('.simpleBanner').simplebanner()              

            //Lire l'option choisi
            var selItem = "<?= $_REQUEST['selItem'] ?>";
            
            //Vérifier si l'event source est démarré et affichage de liste sélectionné
            if(typeof(EventSource) !== "undefined" && selItem=="afficher_demandes" || selItem == "") {
                /**********************************************************
                 * 
                 *  INITIALISATION DU GESTIONNAIRE DE MESSAGE DE BASE
                 * 
                 * 
                 *********************************************************/ 
                var source = new EventSource("../srvsntevt/liste_demandes.php?PLATEAU=<?=$PLATEAU?>");
                
                
                    //Listenner de base pour l'affichage des demandes
                    source.onmessage = function(event) {                        
                        //Ajouter ici le code pour afficher les demandes dans le tableau en javascript (sembpajax afffiche demande)
                        sseAfficherDemandes(event.data);
                    };
                    
                    //Listenner pour les messages d'entête de page (capsule de formation)
                    source.addEventListener('banner',function(event){
                        //Afficher la banniere annonce
                        sseAfficherBanniere(event);
                    },false);
                    
                    //Affichage des enseignants du plateau
                    source.addEventListener('profs',function(event){
                        //Afficher la banniere annonce
                        sseAfficherProfs(event);
                    },false);                    
                   
               
            } 
            
            
            }
                

    
           );
            
            





            
            
        </script>


    </head>

    <body style="<?php echo (isset($stylefond)?$stylefond:"") ?>">

        <div id="page"> 

            <?php
            //Initialiser le tableau des messages
            $avis = (isset($avis)?$avis:array());            
            // Vérifie si un message d'avis a été ajouté
            if (count($avis) > 0) {
                ?>
                <!--Affichage du message-->

                <div id="siteWpr">
                    <div class="simpleBanner">
                        <div class="bannerListWpr">
                            <ul class="bannerList" id="bannerList">
                                
                                <?php foreach ($avis as $message): ?> 
                                    <?php echo "<li>".$message."</li>" ?>
                                <?php endforeach; ?>
                                
                            </ul>
                        </div>
                        <div class="bannerControlsWpr bannerControlsPrev"><div class="bannerControls"></div></div>
                        <div class="bannerIndicators" id="bannerIndicators"><ul></ul></div>
                        <div class="bannerControlsWpr bannerControlsNext"><div class="bannerControls"></div></div>
                    </div>
                </div>


                <?php
            }
            ?>


            <!--Affichage de la banniere-->
            <div id="NbDemandes"><?php echo (isset($banniere)?$banniere:"") ?></div>

        <?php echo (isset($menu )?$menu :"")?>   

             <!--Affichage de la banniere-->
            <div id="profonline"><?php echo (isset($profs)?$profs:"") ?></div>

            <!--Affichage de la rubrique sélectionnée-->
            <div class="main">

                <!--Titre de la rubrique-->            
                <div style="text-align:center"> <?php echo (isset($titre)?$titre:"") ?></div>

                <!--Affichage principal-->
                <?php echo (isset($main)?$main:"") ?>      

            </div>


            <!-- Affichage de message de réussite de connexion -->
<?php echo (isset($erreur)?$erreur:"") ?>
<script language="javascript" src="script.js"></script>
    </body>
</html>
