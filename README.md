# suPProf
 Ce dépôt contient le code source de l'application suPProf. Cette application permet de gérer les demandes d'assistance faites aux enseignants.
 Elle a été conçues pour être utilisée sur un réseau local protégé afin de supporter l'enseignement individualisé en laboratoire.  
 La base de donnnées a été initialisée avec certaines informations du programme Soutien Informatique 5385. 
 
##Installation
En résumé l'installation conciste a monter un serveur Ubuntu minimum avec un accès SSH, installer la plateforme Xampp, télécharger et extraire le projet suPProf dans le dossier principale du serveur web et finalement importer la base de données. 
Nom d'utilisateurs par défaut inscrit dans la base de données<br/>
user:etudiant<br/>
pass:etudiant<br/><br/>

user:enseignant<br/>
pass:enseignant<br/><br/>

user:admin@admin.ca<br/>
pass:admin<br/><br/><br/>

Pour plus d'information sur la mise en place du système voir le [guide de mise en place du serveur](docs/admins/Guide-de-mise-en-place-du-serveur-Ubuntu-pour-suPProf.pdf)<br/><br/>


##Configuration de la connexion
Les fichiers de configuration de la connexion à la base de données devront être ajustés a votre environnement serveur<br/>
/supprof/supadmin/config/[connexion.php](supadmin/config/connexion.php)<br/>
/supprof/supadmin/config/[db.php](supadmin/config/db.php)<br/><br/>

##Guide d'utilisation
Le dossier documentation contient également les guides d'utilisation pour les trois rôles de base :<br/>

[Guide Étudiant](./docs/eleves/supprof-etudiants.pdf) <br/>
[Guide Enseignant](./docs/enseignants/supprof-enseignants.pdf)<br/>
[Guide Admin](./docs/admins/supprof-administrateur.pdf)<br/>

## Contribution

[Informations sur la manière de contribuer au projet]

## Avertissement

**Erreurs et Limitations :** Ce code peut contenir des erreurs importantes de conception, de sécurité et de codage. Il est recommandé de procéder à une révision approfondie avant toute utilisation en production.

**Licence :** Ce projet est distribué avec les licences déjà incluses pour les composants open source utilisés. Aucune autre licence n'est fournie avec ce projet.

**Exclusion de garantie :** Ce projet est partagé sans aucune garantie de fonctionnement. L'auteur ne prend aucune responsabilité pour les dommages pouvant résulter de l'utilisation de ce projet.

## Contact

Pour toute question ou commentaire, n'hésitez pas à nous contacter à [adresse e-mail ou autre moyen de contact].


