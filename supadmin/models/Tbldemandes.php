<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbldemandes".
 *
 * @property integer $ID
 * @property string $NomEleve
 * @property string $NoFiche
 * @property string $Local
 * @property integer $Poste
 * @property string $HeureDebut
 * @property string $HeureFin
 * @property string $CodeUsager
 * @property string $Etat
 * @property string $TypeDemande
 * @property string $Plateau
 * @property string $IP
 * @property string $HeureInscription
 * @property string $Cours
 * @property string $Bloc
 * @property string $Question
 * @property string $url
 */
class Tbldemandes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbldemandes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['NomEleve', 'NoFiche', 'Local', 'Poste', 'HeureDebut', 'HeureFin', 'Plateau', 'IP', 'HeureInscription'], 'required'],
            [['Poste'], 'integer'],
            [['HeureDebut', 'HeureFin', 'HeureInscription'], 'safe'],
            [['Etat', 'Plateau','TypeDemande'], 'string'],
            [['NomEleve', 'IP', 'Question'], 'string', 'max' => 50],
            [['NoFiche', 'Cours', 'Bloc'], 'string', 'max' => 10],
            [['Local', 'CodeUsager'], 'string', 'max' => 15],
            [['url'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'NomEleve' => Yii::t('app', 'Nom Eleve'),
            'NoFiche' => Yii::t('app', 'No Fiche'),
            'Local' => Yii::t('app', 'Local'),
            'Poste' => Yii::t('app', 'Poste'),
            'HeureDebut' => Yii::t('app', 'Heure Debut'),
            'HeureFin' => Yii::t('app', 'Heure Fin'),
            'CodeUsager' => Yii::t('app', 'Code Usager'),
            'Etat' => Yii::t('app', 'Etat'),
            'TypeDemande' => Yii::t('app', 'Type'),
            'Plateau' => Yii::t('app', 'Plateau'),
            'IP' => Yii::t('app', 'Ip'),
            'HeureInscription' => Yii::t('app', 'Heure Inscription'),
            'Cours' => Yii::t('app', 'Cours'),
            'Bloc' => Yii::t('app', 'Bloc'),
            'Question' => Yii::t('app', 'Question'),
            'url' => Yii::t('app', 'Url'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblcoursQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblcoursQuery(get_called_class());
    }
}
