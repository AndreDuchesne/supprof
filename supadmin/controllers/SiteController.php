<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use app\models\Tblusagers;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;



class SiteController extends Controller
{

    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout','gestion','stats'],
                'rules' => [
                    [
                        'actions' => ['logout','gestion','stats'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    
    public function actionGestion()
    {        
    //Vérifie si l'usager est connecté
    if(!Yii::$app->user->isGuest){
        $niveau =Tblusagers::findOne(Yii::$app->user->getId())->niveau;
        if(isset(Yii::$app->user) && $niveau != 'admin'){
            Yii::$app->session->setFlash('ErreurNiveau','Vous n\'avez pas l\'autorisation');
            return $this->goHome();
            }
        }
    return $this->render('gestion');
    }

    

    
    public function actionStats()            
    {
        
        if(!Yii::$app->user->isGuest){
            $niveau =Tblusagers::findOne(Yii::$app->user->getId())->niveau;
            if(isset(Yii::$app->user) && $niveau != 'admin'){
                Yii::$app->session->setFlash('ErreurNiveau','Vous n\'avez pas l\'autorisation');
                return $this->goHome();
                }
            }
        

        /*  STATISTIQUES DES PLATEAUX */
        
        //Initialiser un tableau de stats vide
        $plateaux = array();
        $competences = array();
        $blocs = array();
        $rowplateaux =  array();
        //Formuler la requête pour les demandes par plateau
        $q = "select  plateau, count(ID) as nbdemande from tbldemandes group by plateau";
        //Créer la commande Yii pour db
        $cmd = Yii::$app->db->createCommand($q);
        //Transmettre et récupérer résultat
        $resultat = $cmd->query();
        
        foreach($resultat as $row){
            //Ajouter la ligne de stats
            $rowplateaux['name']=$row['plateau'];
            $rowplateaux['y']=(int)$row['nbdemande'];
            $rowplateaux['drilldown']=$row['plateau'];
            array_push($plateaux,$rowplateaux);
            //Ajouter la liste des competences dans le drilldown
            array_push($competences,$this->drilldown($row['plateau']));            
        }
        
        
        
        //Formuler une requête pour obtenir tous les numéro de compétence groupé par plateau
        $q = "select numeroCompetence from tblcours order by plateau";
        //Créer la commande Yii pour db
        $cmd = Yii::$app->db->createCommand($q);
        //Transmettre et récupérer résultat
        $resultat = $cmd->query();
        //Initialiser le compteur d'indice talbeau
        $i=0;
        //Faire une boucle pour consigner les stats des blocs de tous les modules
        foreach ($resultat as $row){
            //Cumuler les résultat dans blocs
            $blocs[$i]=  $this->drilldown_blocs($row['numeroCompetence']);
            //Ajouter le bloc dans le drilldow du pie
            array_push($competences,$blocs[$i]);
            //Prochain cours
            $i++;
        }
        
        
        
        //Transférer le résultat sur stat à l'aide d'un array        
        return $this->render('stats',['plateaux'=>  $plateaux,
                                      'competences'=>  $competences
            
                ]);
 
    }    
    
    
    
    
    
    
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    
    
    
    
    
    
    
        /*
         * 
         * FONCTION POUR CRÉER LE DRILLDOWN DES COMPTENCES PAR PLATEAU
         * 
         * 
         * 
         */
        
        public function drilldown($plateau){
        /* STATISTIQUES DES COMPETENCES */
        
        //Initialiser le tableau des comptences
        $rowcompetences =  array('name','id','data'=>array());
        //Initialiser le tableau des blocs de cours
        $rowblocs = array();
        //Formuler la requête pour les demandes par plateau
        $q = "select count(ID) as nbdemande, Plateau, Cours from tbldemandes where Plateau='$plateau' group by Plateau, Cours order by Plateau, Cours ASC;";
        //Créer la commande Yii pour db
        $cmd = Yii::$app->db->createCommand($q);
        //Transmettre et récupérer résultat
        $resultat = $cmd->query();     
                
        //Ajouter le nom dans l'entete du drilldown
        $rowcompetences['name']=$plateau;
        $rowcompetences['id']=$plateau;  
              
        foreach($resultat as $row){
            //Ajouter la ligne de stats            
            array_push($rowcompetences['data'], ['name'=>$row['Cours'],'y'=>(int)$row['nbdemande'],'drilldown'=>$row['Cours']]);
            //array_push($rowblocs, $this->drilldown_blocs($row['Cours']));
            }
      
         //Ajouter les deux tableaux dans une meme variable
        
        //Retourner le tout
        return $rowcompetences;
        }
        
        
        
     
        
        
        
        /*****************************************************************
         * 
         * 
         * 
         *   FONCTION POUR CRÉER LE DRILLDOWN DES BLOCS PAR COMPETENCE
         * 
         * 
         * 
         * 
         */
        public function drilldown_blocs($cours){
        
        global $competences;

        //Initialiser le tableau des comptences
        $rowcours =  array('name'=>$cours,'id'=>$competences,'data'=>array());                
        //Formuler la requête pour les demandes par plateau
        $q = "select count(ID) as nbdemande, Bloc from tbldemandes where Cours='".$cours."' group by Bloc;";                
        //Créer la commande Yii pour db
        $cmd = Yii::$app->db->createCommand($q);
        //Transmettre et récupérer résultat
        $resultat = $cmd->query();     
        //Boucler pour récupérer les statistique de chaque bloc
        foreach($resultat as $row){
            //Ajouter la ligne de stats            
            array_push($rowcours['data'], ['name'=>'Bloc '.$row['Bloc'],'y'=>(int)$row['nbdemande']]);
            }
        //Ajouter le nom du cours
        $rowcours['name']=$cours;
        //Ajouter le id du cours
        $rowcours['id']=$cours;  
        //Retourner le résultat du drilldown des blocs
        return $rowcours;
        }
        
        
}
