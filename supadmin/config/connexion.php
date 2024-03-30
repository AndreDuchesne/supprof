<?php
// Démarrer l'utilisation des variables
session_start();

define("BD_SERVEUR","localhost");                   // Adresse du serveur SQL
define("BD_USAGER","root");                          // Nom d'usager sur le serveur SQL
define("BD_MOT_PASSE" , "mysecret");                               // Mot de passe sur le serveur SQL
define("BD" ,"dbsupprof");                                 // Nom de la base de données
define("HTTP_HOST","localhost");                         // Adresse du serveur Web
define("DOSSIER_APP" , "/opt/lampp/supprof");              // Dossier racine de l'application
define("BD_PORT",'3306');                              //Port par défaut



//  Ubundu Server VM 
static $BD_SERVEUR = BD_SERVEUR;                        // Adresse du serveur SQL
static $BD_USAGER = BD_USAGER;                          // Nom d'usager sur le serveur SQL
static $BD_MOT_PASSE = BD_MOT_PASSE;                               // Mot de passe sur le serveur SQL
static $BD =BD;                                 // Nom de la base de données
static $HTTP_HOST = HTTP_HOST;                         // Adresse du serveur Web
static $DOSSIER_APP = DOSSIER_APP;              // Dossier racine de l'application
static $BD_PORT = BD_PORT;                              //Port par défaut




//Initialiser plateau
$_REQUEST['lstPlateau']=(isset($_REQUEST['lstPlateau'])?$_REQUEST['lstPlateau']:"");
$_REQUEST['Niveau']=(isset($_REQUEST['Niveau'])?$_REQUEST['Niveau']:"");

/**
 * 
 * 
 * VARIABLES ET CONSTANTES DE PARAMÈTRES DE L'APPLICATION SUPPROF
 *
 *
 * */

$DELAI_AFFICHAGE = 10000;    // Délai 2 sec de rafraichissement d'affichage
$PLATEAU = $_REQUEST['lstPlateau'];  // Plateau par défaut
$NIVEAU = $_REQUEST['Niveau'];      //Définir le niveau 
      



if($_REQUEST['lstPlateau']!=""){
    //Récupérer la liste des locaux du plateau
    $db = new Database();
    $reponse = $db->select("locaux","tblplateaux","plateau='".$_REQUEST['lstPlateau']."'");
    //Vérifier si une réponse est obtenue
    if(count($reponse)!=0){
        //Initialiser les variables
        $LOCAUX = $reponse[0]['locaux'];  //Liste des locaux du plateau
        $_SESSION['Locaux']=$LOCAUX;    //Initialiser les locaux dans la session
        $_SESSION['Plateau']=$PLATEAU;  //Initialiser le plateau dans la session         
        $_SESSION['Niveau']=$_REQUEST['Niveau'];
    }
    else{

        die("Erreur d'initialisation des paramètres du plateau : ". $_REQUEST["lstPlateau"]);
    }
}


/***********************************************************************
 * 
 *    REINITIALISATION DES PARAMÈTRES D'EXÉCUTION DE SESSION
 * 
 */

 if(isset($_SESSION['Plateau']))
        $PLATEAU = $_SESSION['Plateau'];  //Initaliser le plateau par défaut
 if(isset($_SESSION['Locaux']))
        $LOCAUX = $_SESSION['Locaux'];  // initialiser les locaus à partir de la session
 if(isset($_SESSION['Niveau']))
        $NIVEAU = $_SESSION['Niveau'];   //Initialiser le niveau d'accèes avec la session






//Établir la connexion avec le serveur mysqli
//$lien = mysql_connect($BD_SERVEUR, $BD_USAGER, $BD_MOT_PASSE);
$lien = new mysqli(BD_SERVEUR, BD_USAGER, BD_MOT_PASSE);

// Sélectionner la base de données
//mysqli_selectdb($BD, $lien);
mysqli_select_db($lien, $BD);


// Si une erreur de connexion alors  
if (!$lien) {

    //Affiche le message d'erreur et termine
    $erreur = "<div class=\"erreur\">Une erreur est survenue:<p/>
                <b>Numéro d'erreur :</b>" . mysqli_errno($lien) . "<br/>
                <b>Description :</b>" . mysqli_error($lien) . "<br/>
                </div>";

    //Afficher l'interface
    include_once("affichage.php");

    //Terminer le script
    exit();
}


/**
 * 
 * 
 *   CLASSE D'ABSTRACTION POUR ACCÉDER LA BD AVEC MEMCACHE
 * 
 * 
 * 
 */



class Database {
    private $host = BD_SERVEUR;
    private $username = BD_USAGER;
    private $password = BD_MOT_PASSE;
    private $database = BD;
    private $port = BD_PORT;
    private $mysqli; 

    private $memcached;

    public function __construct() {
        $this->memcached = new Memcached();
        $this->memcached->addServer('localhost', 11211);        
        if(!$this->memcached){
            die("Erreur d'acces a memcached");
            }

        // Initialiser la connexion lors de la création de l'objet
        $this->initializeConnection();
    }


    //Méthode pour initier la conenexion Mysql
    private function initializeConnection(){
        $this->mysqli = new mysqli($this->host, $this->username, $this->password, $this->database, $this->port);
        if($this->mysqli->error){
            die('Erreur de conneixon MySQLi : ' . $this->mysqli->connect_error);
        }

    }


    public function getMemcached() {
        return $this->memcached;
    }

    public function escapeString($value){
        return mysqli_real_escape_string($this->mysqli,$value);
    }


    private function query($sql, $useCache = false) {


        $cacheKey = md5($sql);
        $cacheIdKey = $cacheKey . '_id';

        // Récupérer l'identifiant actuel
        $currentId = $this->memcached->get($cacheIdKey);



        // Exécuter la requête SQL
        $result = $this->mysqli->query($sql);

        // Vérifier les erreurs de requête
        if (!$result) {
            session_destroy();
            die('Erreur SQL: ' . $this->mysqli->error);
        }

        // Récupérer les résultats dans un tableau si c'est une requête select
        if(str_contains(strtolower($sql),'select')){
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
            }
        //vider le résultat après récupération
        $result->free();
        }


        // Calculer un nouvel identifiant basé sur le résultat
        if(isset($data))
            $newId = md5(serialize($data));

        // Comparer avec l'ancien identifiant
        if ($useCache && $currentId !== false && $currentId === $newId) {
            // Retourner le résultat en cache
            return $this->memcached->get($cacheKey);
        }

        // Mettre en cache le résultat avec le nouvel identifiant
        if ($useCache) {
            $this->memcached->set($cacheKey, $data);
            $this->memcached->set($cacheIdKey, $newId);
        }

        return (isset($data)?$data:false);
    }





    public function getMysqlLinkObject(){
        return $this->mysqli;
    }


    public function select($columns, $table, $conditions = '', $useCache = false) {
        $sql = "SELECT $columns FROM $table";

        if (!empty($conditions)) {
            $sql .= " WHERE $conditions";
        }

        return $this->query($sql, $useCache);
    }






    public function insert($table, $data) {
        $columns = implode(',', array_keys($data));
        $values = "'" . implode("','", array_values($data)) . "'";

        $sql = "INSERT INTO $table ($columns) VALUES ($values)";

        return $this->query($sql);
    }







    public function update($table, $data, $conditions) {

        $setClause = '';
        foreach ($data as $column => $value) {
            $setClause .= "`$column`='$value',";
        }
        $setClause = rtrim($setClause, ',');

        $sql = "UPDATE $table SET $setClause WHERE $conditions";

        return $this->query($sql,false);
    }





    public function delete($table, $conditions) {
        $sql = "DELETE FROM $table WHERE $conditions";

        return $this->query($sql);
    }


    // Méthode pour fermer la connexion MySQLi
    public function closeConnection() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }

    // Méthode appelée à la destruction de l'objet
    public function __destruct() {
        $this->closeConnection();
    }
}
?>
