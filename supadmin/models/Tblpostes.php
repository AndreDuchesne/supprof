<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblpostes".
 *
 * @property integer $idtblpostes
 * @property string $numero
 * @property string $nom
 * @property string $description
 * @property string $plateau
 */
class Tblpostes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblpostes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numero', 'nom', 'description'], 'string', 'max' => 45],
            [['plateau'], 'string', 'max' => 4]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idtblpostes' => Yii::t('app', 'Idtblpostes'),
            'numero' => Yii::t('app', 'Numero'),
            'nom' => Yii::t('app', 'Nom'),
            'description' => Yii::t('app', 'Description'),
            'plateau' => Yii::t('app', 'Plateau'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblpostesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblpostesQuery(get_called_class());
    }
}
