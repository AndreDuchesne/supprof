<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblplateaux".
 *
 * @property integer $idtblplateaux
 * @property string $plateau
 * @property string $nomplateau
 * @property resource $plan
 * @property string $locaux
 * @property string $description
 * @property string $stylefond
 */
class Tblplateaux extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblplateaux';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['plan'],'string','max'=>4294967295],
            [['stylefond'],'string','max'=>65535],
            [['plateau'], 'string', 'max' => 4],
            [['nomplateau'], 'string', 'max' => 45],
            [['locaux'], 'string', 'max' => 100],
            [['plateau'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idtblplateaux' => Yii::t('app', 'Idtblplateaux'),
            'plateau' => Yii::t('app', 'Plateau'),
            'nomplateau' => Yii::t('app', 'Nomplateau'),
            'plan' => Yii::t('app', 'Plan'),
            'locaux' => Yii::t('app', 'Locaux'),
            'description' => Yii::t('app', 'Description'),
            'stylefond' => Yii::t('app', 'stylefond'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblplateauxQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblplateauxQuery(get_called_class());
    }
}
