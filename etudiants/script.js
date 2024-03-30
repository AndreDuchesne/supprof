/**
* INITIALISATION DE LA VARIABLE GLOBALE D'AUTOREFRESH LIST
* @param {type} param
* 
* 
*/



//Initialiser la variable pour le refresh
var interval;
var lastchecksum;




/**
 * 
 * 
 * Gestionnaire pour récupérer le click sur le plan svg
 * 
 * 
 **/
//Initialiser l'écouteur
function initIframe() {
    const iframe = document.getElementById("planloc");
    const iframeDocument = iframe.contentDocument;
  
    iframeDocument.addEventListener("click", handleClick);
  }
//Récupérer le click et positionner la liste
  function handleClick(event) {
    //Récupérer le id de l'objet cliqué
    const element = event.target;
    const elementId = element.getAttribute("id");
    //Pointer la liste du formulaire
    const liste = document.getElementById('txtPoste');
    // Extraire le numéro de l'ID
    const numeroDePoste = elementId.match(/\d+/)[0];
    //Positionner la liste
    liste.value = numeroDePoste;    
    //refermer le plan après sélection
    cacher_plan();
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
function sseAfficherDemandes(tableau)
{
        var lstDemandes ='';
         
         obj = JSON.parse(tableau);         

         console.log(obj);
        
       
         var strEtat;
         
         for(i=0;i < obj['demandes'].length;i++){ 
             
            if(obj.demandes[i][6] == "En cours")
                strEtat = "EnCours";
            else
                strEtat ='';
            
            //lire le numéro de ID
            var strID = obj.demandes[i][0];
            //Découper les deux derniers chiffre
            var strID = 'X' + strID.substr((strID.length-2),2);
            
            lstDemandes +=  '<tr class="'+ strEtat + '"> ' + 
                               '<td class="ID">' + 
                                 strID +                              
                                '</td>'+
                                
                                '<td class="Nom detaildemande">' + 
                                 obj.demandes[i][2] + 
                                 '<span class="' + obj.demandes[i][7] + '">' + obj.demandes[i][7] + '</span>' +
                                '</td>'+
                            
                                '<td class="Poste">'+
                                 obj.demandes[i][4] + 
                                '</td>'+
                            
                                '<td class="Local"> ' +
                                obj.demandes[i][5] +
                                '</td>' +
                            
                                '<td class="Operations" >' +
                                '<a href="#" onclick="confirmer_supprimer_demande('+obj.demandes[i][0] +')">' +
                                '<img title="Annuler une demande" class="IconeSupprimer" src="images/supprimer.png">' +
                                '</a>' +
                                '</td> </tr>';
            }
       //Afficher la liste des demandes
       document.getElementById("coreLstDemandes").innerHTML = lstDemandes;
       
       
       //Afficher le nombre de demande dans l'entête
       document.getElementById("NbDemandes").innerHTML = obj.plateau +'(' + obj.NbDemande + ')';
       
       //Ajouter le nombre de demande dans le titre
       document.getElementById("titre").innerHTML =  '(' + obj.NbDemande + ') - suPProf - ' + obj.plateau + ' - ';
       
       }
      



/**
 * 
 * Fonction sse pour afficher les messages de bannière
 * 
 * @param {type} delai
 * @returns {undefined}
 * 
 * 
 */
function sseAfficherBanniere(event)
{
//Initialiser les variables de la baniere    
var banniere ='';
var listelements ='';

//Récupérer l'objet json de la banniere
obj = JSON.parse(event.data);  


//Vérifier si le message d'entête a changé a l'aide du checksum banniere
if(obj[(obj.length)-1].checksum!=lastchecksum){
    //update lastchecksum de la banniere
    lastchecksum = obj[(obj.length)-1].checksum;
    //Bouler pour chaque objet banniere
    for(i=0;i<(obj.length)-1;i++){
        //affiche la baniere dans l'entête
        banniere += "<li><a href=\"?selItem=afficher_inscription&ID=" + obj[i].ID + "\"  title=\"" + obj[i].description + "\n" + obj[i].date_evenement + "\n de " + obj[i].debut + " a " + obj[i].fin + "\">" + obj[i].message + "</a></li>\n";
        listelements +="<li class=\"bannerIndicator\"></li>";
        }

    //Afficher la banniere
    document.getElementById("bannerList").innerHTML = banniere;
    document.getElementById("bannerIndicators").innerHTML = "<ul>" + listelements + "</ul>";    
    }
}
      


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
 *   ACTIVER AUTOREFRESH
 * 
 * @param {type} id
 * @returns {undefined}
 * 
 * 
 */
function activer_autorefresh(delai){
    // Définir la fonction et l'intervalle d'exécution
    interval = setInterval(function(){ajaxlisterDemandes()},delai);
}




/**
 *    DESACTIVER AUTOREFRESH
 * 
 * @param {type} id
 * @returns {undefined}
 * 
 * 
 */
function desactiver_autorefresh(){
    //Désactiver la fonction d'intervalle
    clearInterval(interval);
    //Vider la variable 
    interval=null;    
}




function marquer_en_cours(id)
    {
    var frmaction = 'http://suivi.lan/suprof/index.php?selItem=marquer_en_cours&ID='+id;
    document.frmDemandes.action=frmaction;
    document.frmDemandes.submit();
    }




/**
 * 
 * FONCTION POUR SUPPRIMER UNE INSCRIPTION AVEC PARAMETRES
 * 
 * @param {INTERGER} ID
 * @returns {INTEGER} frm
 * 
 * 
 */
function supprimer_inscription(idEvenement,txtNoFiche)
{
document.frmDetailEvent.idEvenement.value=idEvenement;
document.frmDetailEvent.txtNoFiche.value=txtNoFiche;
document.frmDetailEvent.selItem.value="supprimer_inscription_db";
document.frmDetailEvent.submit();
}


/**
 * 
 * FONCTION POUR AJOUTER UNE INSCRIPTION AVEC PARAMETRES
 * 
 * @param {INTERGER} ID
 * @returns {INTEGER} frm
 * 
 * 
 */
function ajouter_inscription(idEvenement,txtNoFiche)
{
document.frmDetailEvent.idEvenement.value=idEvenement;
document.frmDetailEvent.txtNoFiche.value=txtNoFiche;
document.frmDetailEvent.selItem.value="ajouter_inscription_db";
document.frmDetailEvent.submit();
}



/**
 * CONFIRMER SUPPRESSION D'UNE DEMANDE
 * @param {type} cours
 * @returns {undefined}
 * 
 * 
 * 
 */
function confirmer_supprimer_demande(ID){
    location='index.php?selItem=supprimer_demande&ID=' + ID;
}





/*******************************************************************************************************/
/*******************************************************************************************************/
/*******************************************************************************************************/
/*******************************************************************************************************/



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
            lstOptions += '<option value="' + obj[i]['numeroBloc'] + ';' + obj[i]['url'] + ';' + obj[i]['idtblblocs'] + '">' + obj[i]['numeroBloc'] + ') ' + obj[i]['sujet'] + '</option>';
            }
       document.getElementById("lstBloc").innerHTML = lstOptions;
       }
     }
     xhttp.open("GET", "index.php?selItem=ajaxSetLstBloc&cours=" + cours, true);
     xhttp.send();    
    
}


/**
 * 
 * FONCTION POUR VALIDER LE NUMÉRO DE FICHER ÉLÈVE
 * 
 * @param {type} cours
 * @returns {undefined}
 * 
 * 
 */

function ajaxChkFiche(fiche)
{
    var xhttp = new XMLHttpRequest();
     xhttp.onreadystatechange = function() {
       if (xhttp.readyState == 4 && xhttp.status == 200) {
         var reponse = xhttp.responseText;  
         if(reponse!=true){
             alert('Erreur numero de fiche invalide ');
             document.getElementById("fiche").style.backgroundColor="rgba(255,0,0,0.2)";
            }
         else{
            document.getElementById("fiche").style.backgroundColor="transparent";
             
         }
       }
     }
     xhttp.open("GET", "index.php?selItem=ajaxChkFiche&fiche=" + fiche, true);
     xhttp.send();        
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

function ajaxlisterDemandes()
{
    

   
    var xhttp = new XMLHttpRequest();
        
     xhttp.onreadystatechange = function() {
       if (xhttp.readyState == 4 && xhttp.status == 200) {
         var tableau = xhttp.responseText;         
         
         var lstDemandes ='';
         
         obj = JSON.parse(tableau);         

         
    console.log(obj);
        
       
         var strEtat;
         
         for(i=0;i < obj['demandes'].length;i++) { 

            //Définir l'état             
            if(obj.demandes[i][6] == "En cours")
                strEtat = "EnCours";
            else
                strEtat ='';

            


            
            //lire le numéro de ID
            var strID = obj.demandes[i][0];
            //Découper les deux derniers chiffre
            var strID = 'X' + strID.substr((strID.length-2),2);
            
            lstDemandes +=  '<tr class="'+ strEtat + '"> ' + 
                               '<td class="ID">' + 
                                 strID +                              
                                '</td>'+
                                
                                '<td class="Nom detaildemande">' + 
                                 obj.demandes[i][2] +
                                '<span class="'+ obj.demandes[i][7] + '">'+ obj.demandes[i][7] + '</span>' + 
                                '</td>'+
                            
                                '<td class="Poste">' +
                                 obj.demandes[i][5] + 
                                '</td>'+
                            
                                '<td class="Local"> ' +
                                obj.demandes[i][4] +
                                '</td>' +
                            
                                '<td class="Operations" >' +
                                '<a href="#" onclick="confirmer_supprimer_demande('+obj.demandes[i][0] +')">' +
                                '<img title="Annuler une demande" class="IconeSupprimer" src="images/supprimer.png">' +
                                '</a>' +
                                '</td> </tr>';
            }
       //Afficher la liste des demandes
       document.getElementById("coreLstDemandes").innerHTML = lstDemandes;
       
       
       //Afficher le nombre de demande dans l'entête
       document.getElementById("NbDemandes").innerHTML = obj.plateau +'(' + obj.NbDemande + ')';
       
       //Ajouter le nombre de demande dans le titre
       document.getElementById("titre").innerHTML =  '(' + obj.NbDemande + ') - suPProf - ' + obj.plateau + ' - ';
       
       }
     }
     xhttp.open("GET", "index.php?selItem=ajax_afficher_demandes", true);
     xhttp.send();    
    
}

/*
*
*
*    Fonction pour récupérer le numéro du poste et le transférer 
*    sur le formulaire
*
*
*/


window.onload = function() {
    // Récupérer tous les postes sur le plan SVG
    var postes = document.querySelectorAll('.poste');

    // Boucler à travers tous les postes pour ajouter des gestionnaires d'événements
    postes.forEach(function(poste) {
        poste.addEventListener('click', function() {
            // Récupérer le numéro du poste à partir de l'attribut "data-numero-poste"
            var numeroPoste = poste.getAttribute('data-numero-poste');

            // Fermer la fenêtre du plan (remplacez "window.close()" par le code réel pour fermer la fenêtre)
            window.close();

            // Remplir le champ du formulaire avec le numéro du poste
            document.getElementById('champNumeroPoste').value = numeroPoste;
        });
    });
};


/**
 * Fonction pour la gestion de l'affichage du plan
 * 
 * 
 */
function afficher_plan(){
    document.getElementById('plan').style.display='block';
}

function cacher_plan(){
    document.getElementById('plan').style.display='none';
}


