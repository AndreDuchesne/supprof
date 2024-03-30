<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblmessages".
 *
 * @property integer $ID
 * @property string $titre
 * @property string $description
 * @property string $lien
 * @property string $local
 * @property string $date_evenement
 * @property string $debut
 * @property string $fin
 * @property integer $idenseignant
 */
class Tblmessages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblmessages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['date_evenement', 'debut', 'fin'], 'safe'],
            [['idenseignant'], 'integer'],
            [['titre', 'lien'], 'string', 'max' => 512],
            [['local'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'titre' => Yii::t('app', 'Titre'),
            'description' => Yii::t('app', 'Description'),
            'lien' => Yii::t('app', 'Lien'),
            'local' => Yii::t('app', 'Local'),
            'date_evenement' => Yii::t('app', 'Date Evenement'),
            'debut' => Yii::t('app', 'Debut'),
            'fin' => Yii::t('app', 'Fin'),
            'idenseignant' => Yii::t('app', 'Idenseignant'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblmessagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblmessagesQuery(get_called_class());
    }
}
