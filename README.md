# suPProf
 Ce dépôt contient le code source de l'application suPProf. Cette application permet de gérer les demandes d'assistance faites aux enseignants.
 Elle a été conçues pour être utilisée sur un réseau local protégé afin de supporter l'enseignement individualisé en laboratoire.  
 La base de donnnées a été initialisée avec certaines informations du programme Soutien Informatique 5385. 
 
<h2>Installation</h2>
En résumé l'installation conciste a monter un serveur Ubuntu minimum avec un accès SSH, installer la plateforme Xampp, télécharger et extraire le projet suPProf dans le dossier principale du serveur web et finalement importer la base de données. 
Nom d'utilisateurs par défaut inscrit dans la base de données<br/>
user:etudiant<br/>
pass:etudiant<br/><br/>

user:enseignant<br/>
pass:enseignant<br/><br/>

user:admin@admin.ca<br/>
pass:admin<br/><br/><br/>

Pour plus d'information sur la mise en place du système voir le [guide de mise en place du serveur](docs/admins/Guide-de-mise-en-place-du-serveur-Ubuntu-pour-suPProf.pdf)<br/><br/>


<h2>Configuration de la connexion</h2>
Les fichiers de configuration de la connexion à la base de données devront être ajustés a votre environnement serveur<br/>
[connexion.php](supadmin/config/connexion.php)<br/>
[db.php](supadmin/config/db.php)<br/><br/>

<h2>Guide d'utilisation</h2>
Le dossier documentation contient également les guides d'utilisation pour les trois rôles de base :<br/>

[Guide Étudiant](./docs/eleves/supprof-etudiants.pdf) <br/>
[Guide Enseignant](./docs/enseignants/supprof-enseignants.pdf)<br/>
[Guide Admin](./docs/admins/supprof-administrateur.pdf)<br/>

<h2>Contribution</h2>

N'hésitez-pas a contribuer en toute liberté et de la façon qui vous convient le mieux. Si vous souhaitez contribuer, vous pouvez suivre ces étapes qui sont généralement :

1. **Fork** du projet sur GitHub.
2. Créez une branche pour votre contribution (`git checkout -b nom-de-votre-branche`).
3. Effectuez les modifications ou ajouts nécessaires.
4. Testez vos modifications.
5. Committez vos changements (`git commit -am 'Ajout de fonctionnalité X'`).
6. Pusher vers la branche (`git push origin nom-de-votre-branche`).
7. Soumettez une **pull request** avec une description détaillée de vos modifications.

Merci pour votre intérêt à améliorer ce projet!


<h2>Avertissement</h2> 

**Erreurs et Limitations :** Ce code peut contenir des erreurs importantes de conception, de sécurité et de codage. Il est recommandé de procéder à une révision approfondie avant toute utilisation en production.

**Licence :** Ce projet est distribué avec les licences déjà incluses pour les composants open source utilisés. Aucune autre licence n'est fournie avec ce projet.

**Exclusion de garantie :** Ce projet est partagé sans aucune garantie de fonctionnement. L'auteur ne prend aucune responsabilité pour les dommages pouvant résulter de l'utilisation de ce projet.

## Contact

Pour toute question ou commentaire, n'hésitez pas à me contacter à [André Duchesne](mailto://andre.duchesne@cssdd.gouv.qc.ca).


