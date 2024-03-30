<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tblcours".
 *
 * @property int $idtblCompetences
 * @property string|null $titreCompetence
 * @property string|null $numeroCompetence
 * @property string|null $plateau
 */
class Tblcours extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tblcours';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['titreCompetence', 'numeroCompetence'], 'string', 'max' => 45],
            [['plateau'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idtblCompetences' => 'Idtbl Competences',
            'titreCompetence' => 'Titre Competence',
            'numeroCompetence' => 'Numero Competence',
            'plateau' => 'Plateau',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TblcoursQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TblcoursQuery(get_called_class());
    }
}
