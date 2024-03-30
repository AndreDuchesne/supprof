<?php

namespace app\controllers;

use app\models\Tblusagers;
use app\models\TblusagersSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TblusagersController implements the CRUD actions for Tblusagers model.
 */
class TblusagersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Tblusagers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TblusagersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tblusagers model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tblusagers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tblusagers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Tblusagers model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {               
            //Corriger la date avec celle du jour
            $t = strtotime($model->datecreation); //Lire la date de création
            $d = getdate($t); //Transformer en date array
            if(!checkdate($d['mon'], $d['mday'], $d['year'])) //Vérifier date valide
                    $model->datecreation = date('Y-m-d H:i:s'); //Corriger avec la date du jour                        
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tblusagers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    
    
    
     /**
     * Deletes an existing Tblusagers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMultipleDelete()
    {
        $pk = Yii::$app->request->post('row_id');
        
        foreach ($pk as $key => $value) 
        {
            $sql = "DELETE FROM user WHERE id = $value";
            $query = Yii::$app->db->createCommand($sql)->execute();
        }

        return $this->redirect(['index']);

    }
    
    
    
     /**
     * 
      *    METHODE pour l'inportation des données usagers
      * 
      * 
      * 
      * 
     */
    public function actionImport()
    {
        
        
        //echo "<pre>";
        //print_r($_REQUEST);
        //print_r($_FILES);
        //echo "</pre>";
        //die();
        
        //Initialiser le pointeur de ligne du fichier csv
        $row = 1;
        //Vérifier si le nom de fichier est valide
        if(!isset($_FILES['fichiercsv']['tmp_name'])){
                        //Définir et affiche le message d'erreur
                        Yii::$app->session->setFlash('error',"<b>Erreur d'importation</b><br/>"
                                                            . "Vous devez sélectionner un fichier a importer avant d'appuyer sur le bouton d'importation<br/>"
                                                    );
                        return $this->actionIndex(); 
            }
        if($_FILES['fichiercsv'][type]!='text/csv'){
                        //Définir et affiche le message d'erreur
                        Yii::$app->session->setFlash('error',"<b>Erreur d'importation</b><br/>"
                                                            . "Vous devez sélectionner un fichier de données (.csv) séparé par des points-virgules<br/>"
                                                            . "Les champs doivent être ordonnés comme suit: "
                                                            . "[Fiche], [Nom], [Prénom], [Nom d'usager], [Mot de passe], [Niveau], [Email]");
                                
                        return $this->actionIndex(); 
            }                       
                        
        //Vérifier si ouverture de fichier est OK
        if (($handle = fopen($_FILES['fichiercsv']['tmp_name'], "r")) !== FALSE) {
            //Boucler tant qu'il y a des lignes de caractère
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                //Compter le nombre de champs
                $num = count($data);
                //Vérifier si le nombre de champ correspond à 8
                if($num != 7){
                        //Définir et affiche le message d'erreur
                        Yii::$app->session->setFlash('error',"<b>Erreur d'importation</b><br/>"
                                                            . "Le nombre de champs ne correspond pas. <br/> "
                                                            . "Les champs doivent être ordonnés comme suit: "
                                                            . "[Fiche], [Nom], [Prénom], [Nom d'usager], [Mot de passe], [Niveau], [Email]");
                        return $this->actionIndex();                        
                    }
                //Avancer à la prochaine ligne
                $row++;
                //Boucler pour récupérer chaque colonne
                    //Ajouter la colonne dans la base de donnée
                    try{
                        //Formuler la requête d'insertion
                        $q = "INSERT INTO user (fiche, nom, prenom, username, password, niveau, email, datecreation) "
                           . "VALUES (:fiche, :nom, :prenom, :username, :password, :niveau, :email, :datecreation)";
                        //Créer la commande SQL
                        $cmd = Yii::$app->db->createCommand($q);
                        //Lier les données
                        $cmd->bindValues([':fiche'=>$data[0],
                                          ':nom'=>$data[1],  
                                          ':prenom'=>$data[2],
                                          ':username'=>$data[3],  
                                          ':password'=>$data[4],  
                                          ':niveau'=>$data[5],  
                                          ':email'=>$data[6], 
                                          ':datecreation'=>date('Y-m-d h:i:s'),  
                                        ]);
                        //Transmettre la requête
                        $cmd->execute(); 
                        }
                    catch (\yii\base\Exception $e){
                        //Définir le message flash en erreur
                        Yii::$app->session->setFlash('error',$e);
                        //Retourner l'affichage sur la page index
                         return $this->actionIndex();                         
                                
                        }
                    
                
            }            
            //Refermer le fichier csv
            fclose($handle);
            //Définir le message d'importation réussit
            Yii::$app->session->setFlash('success','<b>Importation réussit</b> ' . ($row-1) .' ligne(s) ont été inscrite(s)');
            //Retourner l'affichage sur la page index
            return $this->actionIndex();             
        }
    }
    
        
     /**
     * Deletes an existing Tblusagers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMultipleExport()
    {
        $pk = Yii::$app->request->post('row_id');
        
       
        $users = Tblusagers::findAll($pk);

        //Créer une instance de PHPExcel
        $objPHPExcel = new \PHPExcel();
        
        $objPHPExcel->setActiveSheetIndex(0); 
        $rowCount=1;
                
        //Faire la ligne titre
        $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount,'fiche');
        $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount,'nom');
        $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount,'prenom');
        $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount,'username');
        $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount,'password');
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount,'niveau');
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount,'email');
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount,'datecreation');        
        
        $rowCount=2;
        foreach($users as $user){
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $user->fiche);
            $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $user->nom);
            $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $user->prenom);
            $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $user->username);
            $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, '********');
            $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $user->niveau);            
            $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $user->email);
            $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $user->datecreation);
            $rowCount++;
            } 

        
        $filename = $user->fiche . time();
        
            
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->setOffice2003Compatibility(true);
        
        
        
        
        //$objWriter->save('php://output');
        $objWriter->save(Url::to('../tmp/')."$filename.xlsx");
        
       
        $this->redirect(Url::to('../tmp/')."$filename.xlsx");
        
        
        //return $this->redirect(['index']);
        
        

        
        //Récupérer toute les données des usagers
        //Exporter vers excell
        
        /*
        foreach ($pk as $key => $value) 
        {
            $sql = "DELETE FROM user WHERE id = $value";
            $query = Yii::$app->db->createCommand($sql)->execute();
        }
         

        return $this->redirect(['index']);
        */
    }
    
    
    /**
     * Finds the Tblusagers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tblusagers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tblusagers::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
