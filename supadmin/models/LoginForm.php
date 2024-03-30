<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Tblusagers;
/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $username;
    public $password;
    public $hash;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['password','email'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['email','email'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Nom d\'usager'),
            'email' => Yii::t('app', 'Courriel'),
            'rememberMe' => Yii::t('app', 'Se rappeler'),
            'password' => Yii::t('app', 'Mot de passe'),
        ];
    }    
    
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {

        if (!$this->hasErrors()) {
            $user = $this->getUser();                
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, "Nom d'usager ou mot de passe invalide.");
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser());
        }
        else{
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
