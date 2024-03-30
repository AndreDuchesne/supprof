<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblinscriptions".
 *
 * @property integer $idinscription
 * @property integer $idParticipant
 * @property integer $idEvenement
 */
class Tblinscriptions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblinscriptions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idParticipant', 'idEvenement'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idinscription' => Yii::t('app', 'Idinscription'),
            'idParticipant' => Yii::t('app', 'Id Participant'),
            'idEvenement' => Yii::t('app', 'Id Evenement'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblinscriptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblinscriptionsQuery(get_called_class());
    }
    


    public function getMessages(){
        return $this->hasOne(Tblmessages::className(),['ID'=>'idEvenement']);
    }
    
    public function getUsers(){
        return $this->hasOne(User::className(), ['id'=>'idParticipant']);
    }

}
