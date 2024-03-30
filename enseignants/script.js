function selectionner_fin()
{
    document.frmMessage.txtFin.value = document.frmMessage.txtDebut.value;
    alert(document.frmDemandes.txtDebut.value);
}



function marquer_en_cours(id)
{

    var frmaction = 'index.php?selItem=marquer_en_cours&ID=' + id;
    document.frmDemandes.action = frmaction;
    document.frmDemandes.submit();
}



function setpostecouleur(posteid,couleur){ 
    //Vérifier si poste 0 = salle evaluation
    if(posteid!=0){
        var svg = document.getElementById("planloc");
        if(svg != null){
            var svgDoc = svg.contentDocument;
            var poste = svgDoc.getElementById('poste'+posteid); 
            if(poste != null)
                poste.setAttributeNS(null,'style','opacity:0.5;fill:solid;stroke-width:3;stroke-dasharray:solid;stroke-opacity:1')
                poste.setAttributeNS(null,'fill',couleur);    
            }
        else{
            document.getElementById("stats").innerHTML = '<span style=\"background-color:yellow;color:red\"><b>Erreur :</b> Le DOM n\'est plus synchronisé avec AJAX. Appuyez sur le bouton Lister les demandes</span>';
        }
    }
            
                
        
}


function dupliquer_message(ID){
    var choix = confirm('Voulez-vous vraiment créer une copie de cet événement ' + ID + '?');
    if(choix==true){
        var frmaction = 'index.php?selItem=dupliquer_message&ID='+ID;
        document.frmMessage.action = frmaction;
        document.frmMessage.submit();        
    }
        
}


function confirmation(ID) {
    choix = confirm('Voulez-vous vraiment vraiment SUPPRIMER l\'annonce ?');
    if (choix == true) {
        location = 'index.php?selItem=supprimer_message&ID='+ID;
    }
}




function confirmer_supprimer_demande(ID) {
    choix = confirm('Voulez-vous vraiment vraiment SUPPRIMER la demande de support  #' + ID + ' ?');
    if (choix == true) {
        location = 'index.php?selItem=supprimer_demande_db&ID='+ID;
    }
}

function confirmer_terminer_demande(ID, username) {
    
      
    if (!confirm('Voulez-vous vraiment vraiment marquer la demande #' + ID + ' TERMINER ?'))
        return;
    
        location = 'index.php?selItem=terminer_demande_db&ID='+ID;
    
}


/**
 * 
 * FONCTION POUR DÉFINIR LA LISTE DES BLOCS DU COURS SÉLECTIONNÉ
 * 
 * @param {type} cours
 * @returns {undefined}
 * 
 * 
 */

function ajaxSetLstBloc(cours)
{
    var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
       if (xhttp.readyState == 4 && xhttp.status == 200) {
         var tableau = xhttp.responseText;         
         var lstOptions ='';
         
         obj = JSON.parse(tableau);
         
         
         for(i=0;i < obj.length;i++){            
            lstOptions += '<option value="' + obj[i]['numeroBloc'] + '">' + obj[i]['numeroBloc'] + ') ' + obj[i]['sujet'] + '</option>';
            }
       document.getElementById("lstBloc").innerHTML = lstOptions;
       }
     }
     xhttp.open("GET", "index.php?selItem=ajaxSetLstBloc&cours=" + cours, true);
     xhttp.send();    
    
}





/**
 * 
 * FONCTION POUR marque la demande en cours
 * 
 * @param {type} cours
 * @returns {undefined}
 * 
 * 
 */


function ajax_marquer_en_cours(id, username, etat)
{
   
   var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
       if (xhttp.readyState == 4 && xhttp.status == 200) {
           var msg = xhttp.responseText;
           if(msg!='')
               document.getElementById("errmsg").innerHTML = msg;
           else
           {
               document.getElementById("errmsg").innerHTML = '';
               ajax_afficher_demandes();
           }
       }
       
     }
     xhttp.open("GET", "index.php?selItem=ajax_marquer_en_cours&ID=" + id, true);
     xhttp.send();               
}




/**
 * Fonction pour actualiser la liste des demandes
 * @returns {json}
 */
//Variable globale pour le timer
var objtimer;

function start_auto_refresh_list(){
    objtimer = setInterval(ajax_afficher_demandes,1500);   
}


/**
 * Fonction pour arrêter l'actualisation de la liste
 * @returns {json}
 */
function stop_auto_refresh_list(){
    clearInterval(objtimer);    
}

/**
 * 
 * Fonction pour ouvrir une nouvelle fenêtre et 
 * générer le pdf
 * 
 * @returns {json}
 * 
 */
function imprimer_inscriptions(ID)
{
    //Ouvrir une nouvelle fenêtre
    window.open('?selItem=imprimer_inscriptions&ID='+ID,"win","menubar=no, status=no, scrollbars=no, menubar=no, width=640, height=400");
}



/**
 * 
 * Fonction pour gérer les inscriptions dans une nouvelle fenêtre et 
 * voir la liste des inscriptions
 * 
 * @returns {json}
 * 
 */
function gerer_inscriptions(ID)
{
    //Ouvrir une nouvelle fenêtre
    window.open('?selItem=gerer_inscriptions&ID='+ID,"win","menubar=no, status=no, scrollbars=no, menubar=no, width=640, height=400");
}





/**
 * 
 * FONCTION POUR RÉCUPÉRER LA LISTE DES DEMANDES EN COURS SUR UN PLATEAU 
 * 
 * @param {sring} plateau
 * @returns {json} 
 * 
 * 
 */

function ajax_afficher_demandes()
{
    
    var d = new Date();    
   
    var xhttp = new XMLHttpRequest();
        
      
    xhttp.onreadystatechange = function() {

        if (xhttp.readyState == 4 && xhttp.status == 200) {
         var tableau = xhttp.responseText;         
         

         
         var lstDemandes ='';

           
            
         obj = JSON.parse(tableau);         



        
         var strEtat;
         
         for(i=0;i < obj['demandes'].length;i++){ 
             
            if(obj.demandes[i][6] == "En cours"){
                strEtat = "EnCours";
                setpostecouleur(obj.demandes[i][5],'green');
            }
            else{
                strEtat ='';
                setpostecouleur(obj.demandes[i][5],'transparent');
            }
            
            //découper 15 caractères du sujet
            var sujet = obj.demandes[i][11].substring(0,25); 
            
            lstDemandes +=  '<tr class="'+ strEtat + ' '+ obj.demandes[i][12] + '"> ' + 
                            '<td><a href="' + obj.demandes[i][10] + '" target="moodle">' +
                            '<img title="Ouvrir un URL" class="IconeURL" src="images/url.png">' +
                            '</a></td>' +                    
                            '<td class="Nom" onclick="javascript:ajax_marquer_en_cours(' + obj.demandes[i][0]+ ',\''+ obj.demandes[i][12]+ '\',\'' + strEtat + '\')">' + 
                             obj.demandes[i][2] + ' <span class="nbdemande">' + obj.demandes[i][3] + '</span>' + ' <span class="' + obj.demandes[i][13] + '">' + obj.demandes[i][13] + '</span>' +
                             '<br/><font size="-1">(' + obj.demandes[i][7] + ') #' + obj.demandes[i][8] + ' ' + sujet + '<br>' + obj.demandes[i][9] +'</font>' +
                            '</td> <td class="Poste"><font size="3"><b>'+ obj.demandes[i][5] + '<br>' + obj.demandes[i][4] +'</font></b></td>' +
                            '<td class="Operations" >' +                            
                            '<a href="#" onclick="confirmer_terminer_demande(' + obj.demandes[i][0] + ',\''+obj.demandes[i][12] + '\')">' +
                            '<img title="Marquer une demande termin&eacutee" class="IconeTerminer" src="images/terminer.png">' +
                            '</a>&nbsp;&nbsp;' +
                            '<a href="#" onclick="confirmer_supprimer_demande('+obj.demandes[i][0] +')">' +
                            '<img title="Annuler une demande" class="IconeSupprimer" src="images/supprimer.png">' +
                            '</a>' +
                            '</td> </tr>';
            }
       //Afficher la liste des demandes
       document.getElementById("coreLstDemandes").innerHTML = lstDemandes;


       //Construire la chaine pour les statistiques
       var strstats = '<div id="stats"><b>Statistiques</b>:&nbsp;&nbsp;&nbsp;' + 
			'Nombre total de demandes : '+ obj.stats[0] + '&nbsp;&nbsp;&nbsp;' +
			'Dur&eacutee totale : ' + obj.stats[1] + ' &nbsp;&nbsp;&nbsp; ' + 
			'Dur&eacutee moyenne par demande : ' + obj.stats[2] + 'sec &nbsp;&nbsp;&nbsp;<span class="disbox" style="float:right">' +                        
                        '<a href="?selItem=rapport_stats&nbjours=60" title="Statistique demandes par élèves vers chiffrier ">Rapport [&DownTeeArrow;]</a>&nbsp;' +
                        '<a href="?selItem=exporter_stats" title="Exporter les donnees vers chiffrier ">Exporter [&boxbox;]</a>&nbsp;' +
                        '<a href="?selItem=configurer_profil" title="Configurer les paramètres du compte">Profil [&commat;]</a>&nbsp;' +                        
                        '</span></div>';
       
        //Afficher les statistiques
       document.getElementById("stats").innerHTML = strstats;        
       
       var strprofs = "<b>Enseignants : </b>";
       
       for(i=0;i<obj.profs.length;i++){
           strprofs += "<a href='mailto:" + obj.profs[i].email  + "'> ";
           strprofs +=  obj.profs[i].prenom + " ";
           strprofs += obj.profs[i].nom + "&nbsp;"; 
           strprofs += "<a href=\"?selItem=terminer_session_prof&ID=" + obj.profs[i].id + "\">&nbsp[X]&nbsp</a>/&nbsp;&nbsp;&nbsp;";
       }
       
       document.getElementById("profonline").innerHTML = strprofs;
       
       //Afficher le nombre de demande dans l'entête
       document.getElementById("NbDemandes").innerHTML = obj.plateau +'(' + obj.NbDemande + ')';
       
       //Ajouter le nombre de demande dans le titre
       document.getElementById("titre").innerHTML =  '(' + obj.NbDemande + ') - suPProf - Enseignant - ';
       
       }
     }
     xhttp.open("GET", "index.php?selItem=ajax_afficher_demandes", true);
     xhttp.send();    
    
}









/**
 * 
 * Fonction sse pour afficher la liste des demandes
 * 
 * @param {type} delai
 * @returns {undefined}
 * 
 * 
 */
function sseAfficherDemandesEnseignants(tableau)
{
       
         var lstDemandes ='';
         
         obj = JSON.parse(tableau);  


         
        
         var strEtat;
         
         for(i=0;i < obj['demandes'].length;i++){ 
             
            if(obj.demandes[i][6] == "En cours"){
                strEtat = "EnCours";
                setpostecouleur(obj.demandes[i][5],'green');
            }
            else{
                strEtat ='';
                setpostecouleur(obj.demandes[i][5],'transparent');
            }
            
            lstDemandes +=  '<tr class="'+ strEtat + '"> ' + 
                            '<td><a href="' + obj.demandes[i][10] + '" target="moodle">' +
                            '<img title="Ouvrir un URL" class="IconeURL" src="images/url.png">' +
                            '</a></td>' +                    
                            '<td class="Nom" onclick="javascript:ajax_marquer_en_cours(' + obj.demandes[i][0]+ ')">' + 
                             obj.demandes[i][2] + ' <span class="nbdemande">' + obj.demandes[i][13] + '</span>' + ' <span class="' + obj.demandes[i][13] + '">' + obj.demandes[i][13] + '</span>' +
                             '<br/><font size="-1">(' + obj.demandes[i][7] + ')  #' + obj.demandes[i][8] +' ' + obj.demandes[i][9] + 'xxxxx</font>' +
                            '</td> <td class="Poste"><font size="3"><b>'+ obj.demandes[i][5] + '<br>' + obj.demandes[i][4] +'</font></b></td>' +
                            '<td class="Operations" >' +                            
                            '<a href="#" onclick="confirmer_terminer_demande(' + obj.demandes[i][0] + ')">' +
                            '<img title="Marquer une demande termin&eacutee" class="IconeTerminer" src="images/terminer.png">' +
                            '</a>&nbsp;&nbsp;' +
                            '<a href="#" onclick="confirmer_supprimer_demande('+obj.demandes[i][0] +')">' +
                            '<img title="Annuler une demande" class="IconeSupprimer" src="images/supprimer.png">' +
                            '</a>' +
                            '</td> </tr>';
            }
       //Afficher la liste des demandes
       document.getElementById("coreLstDemandes").innerHTML = lstDemandes;
       

       //Construire la chaine pour les statistiques
       var strstats = '<div id="stats"><b>Statistiques</b>:&nbsp;&nbsp;&nbsp;' + 
			'Nombre total de demandes : '+ obj.stats[0] + '&nbsp;&nbsp;&nbsp;' +
			'Dur&eacutee totale : ' + obj.stats[1] + ' &nbsp;&nbsp;&nbsp; ' + 
			'Dur&eacutee moyenne par demande : ' + obj.stats[2] + 'sec &nbsp;&nbsp;&nbsp;<span class="disbox" style="float:right">' +                        
                        '<a href="?selItem=rapport_stats&nbjours=60" title="Rapport demandes par élève vers chiffrier ">Rapport [&DownTeeArrow;]</a>&nbsp;' +                        
                        '<a href="?selItem=exporter_stats" title="Exporter les donnees vers chiffrier ">Exporter [&boxbox;]</a>&nbsp;' +
                        '<a href="?selItem=configurer_profil" title="Configurer les paramètres du compte">Profil [&commat;]</a>&nbsp;' +                        
                        '</span></div>';
       
        //Afficher les statistiques
       document.getElementById("stats").innerHTML = strstats;        
       
       
       
       var strprofs = "<b>Enseignants : </b>";
       //Récupérer les enseignants du plateau
       for(i=0;i<=obj.profs.length;i++){
           strprofs += obj.profs[i].prenom + " ";
           strprofs += obj.profs[i].nom + " ";
           strprofs += obj.profs[i].email + " ";
       }
       
       document.getElementById("profonline").innerHTML = strprofs;
       
             
       
       //Afficher le nombre de demande dans l'entête
       document.getElementById("NbDemandes").innerHTML = obj.plateau +'(' + obj.NbDemande + ')';
       
       //Ajouter le nombre de demande dans le titre
       document.getElementById("titre").innerHTML =  '(' + obj.NbDemande + ') - suPProf - Enseignant - ';
       
}
     

















function validate_username(){
    var username = document.getElementById('txtUser').value;
    if(username.length<=4)
        document.getElementById('resultusername').innerHTML='Erreur ! Nom d\'usager trop court';
    else
        document.getElementById('resultusername').innerHTML='';
}

function validate_confirm(){
    var pass = document.getElementById('txtPass').value;
    var confirm =  document.getElementById('txtConfirm').value;
    if(pass != confirm)
        document.getElementById('resultconfirm').innerHTML = 'Erreur !  Veuillez confirmer le mot pass';
    else
        document.getElementById('resultconfirm').innerHTML = '';    
}

function validate_pass(){
    var pass = document.getElementById('txtPass').value;
    if(pass.length <= 4)
        document.getElementById('resultpass').innerHTML = 'Erreur !  Veuillez choisir un mot de passe d\'au moins 4 caractères';
    else
        document.getElementById('resultpass').innerHTML = '';    
}

function validate_email(){
    var email = document.getElementById('txtEmail').value;
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if(!re.test(email))
        document.getElementById('resultemail').innerHTML = 'Erreur ! Adresse courriel invalid';
    else
        document.getElementById('resultemail').innerHTML = '';
    
}
