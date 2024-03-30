<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tblmessages;

/**
 * TblmessagesSearch represents the model behind the search form about `app\models\Tblmessages`.
 */
class TblmessagesSearch extends Tblmessages
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'idenseignant'], 'integer'],
            [['titre', 'description', 'lien', 'local', 'date_evenement', 'debut', 'fin'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Tblmessages::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'ID' => $this->ID,
            'date_evenement' => $this->date_evenement,
            'debut' => $this->debut,
            'fin' => $this->fin,
            'idenseignant' => $this->idenseignant,
        ]);

        $query->andFilterWhere(['like', 'titre', $this->titre])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'lien', $this->lien])
            ->andFilterWhere(['like', 'local', $this->local]);

        return $dataProvider;
    }
}
