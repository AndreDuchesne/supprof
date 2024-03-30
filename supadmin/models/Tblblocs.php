<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblblocs".
 *
 * @property integer $idtblblocs
 * @property integer $numeroBloc
 * @property string $sujet
 * @property string $plateau
 * @property string $cours
 * @property string $url
 */
class Tblblocs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tblblocs';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numeroBloc'], 'integer'],
            [['sujet', 'url'], 'string', 'max' => 100],
            [['plateau'], 'string', 'max' => 3],
            [['cours'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idtblblocs' => Yii::t('app', 'Idtblblocs'),
            'numeroBloc' => Yii::t('app', 'Numero Bloc'),
            'sujet' => Yii::t('app', 'Sujet'),
            'plateau' => Yii::t('app', 'Plateau'),
            'cours' => Yii::t('app', 'Cours'),
            'url' => Yii::t('app', 'Url'),
        ];
    }

    /**
     * @inheritdoc
     * @return TblblocsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblblocsQuery(get_called_class());
    }
}
