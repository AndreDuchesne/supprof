<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblusagers".
 *
 * @property integer $id
 * @property string $fiche
 * @property string $nom
 * @property string $prenom
 * @property string $username
 * @property string $password
 * @property string $email 
 * @property string $niveau
 * @property string $datecreation 
 * @property string $accesssToken Description
 * @property string $authKey Description
 * @property string $hash Description
 * @property string $status
 */
class Tblusagers extends \yii\db\ActiveRecord
{

           
    //Champ de confirmation du mot de passe
    public $password_repeat;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['niveau'], 'string'],
            [['status'], 'string'],
            [['email'],'email'],
            [['datecreation'],'date','format'=>'yyyy-M-s H:m:s'],
            [['fiche'], 'string', 'max' => 10],
            [['nom', 'prenom', 'username', 'password','password_repeat'], 'string', 'max' => 45],
            [['accessToken', 'hash', 'authKey'], 'string', 'max' => 100],
            [['fiche'], 'unique'],
            [['password'],'compare'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'id'),
            'fiche' => Yii::t('app', 'Fiche'),
            'nom' => Yii::t('app', 'Nom'),
            'prenom' => Yii::t('app', 'Prenom'),
            'username' => Yii::t('app', 'username'),
            'password' => Yii::t('app', 'password'),
            'email'=>Yii::t('app','email'),
            'datecreation'=>Yii::t('app','datecreation'),
            'niveau' => Yii::t('app', 'Niveau'),
            'hash' => Yii::t('app', 'Mot de passe'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblusagersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblusagersQuery(get_called_class());
    }
    
    
    
    
     /**
     * Fonction pour encrypter le password pour les nouvelles inscriptions
     * 
     */
    public function beforeSave($insert) {
        //Créer le hash password pour admin
        $this->hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        //Vérifier si nouveau compte
        
        //Définir la date de création
        return true;

    }
    
    /**
     * Fonction pour retrouver le niveau de l'usager
     * 
     */
    public function getNiveau($id){
        return $this->findOne($id)::niveau or null;
    }
    
    
    
    
    
}
