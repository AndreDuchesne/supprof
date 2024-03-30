<?php

namespace app\models;



class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);        
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * Trouver un usager pas son courriel
     * @param string $email chaine qui représente le courriel
     * @return User Retourne les détails de l'usager ou null
     */
    public static function findByEmail($email){
        //Retrouver l'usager par son email
        return User::find()->where(['email'=>$email])->one();
    } 
    
    /**
     * Fonction pour encrypter le password pour les nouvelles inscriptions
     * 
     */
    public function beforeSave($insert) {
        if($insert){
            //Encryption du mot de passe
            $this->pass = Yii::$app->getSecurity()->generatePasswordHash($this->pass);
        }
        
        parent::beforeSave($insert);
    }
    
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::find()->where(['username'=>$username])->one();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {      
       /* 
        echo "<pre>";
        print_r($_REQUEST);
        echo "<br/><hr><br>";
        echo "<br/>password = ".$password;
        echo "<br/>Le hash du user = ".$this->hash;
        echo "<br/>Yii Hash = ".\Yii::$app->getSecurity()->generatePasswordHash($password);
      
        echo "</pre>";
        die();
        */
        
        return \Yii::$app->getSecurity()->validatePassword($password, $this->hash);
    }
}
