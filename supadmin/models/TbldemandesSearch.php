<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tbldemandes;

/**
 * TbldemandesSearch represents the model behind the search form about `app\models\Tbldemandes`.
 */
class TbldemandesSearch extends Tbldemandes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ID', 'Poste'], 'integer'],
            [['NomEleve', 'NoFiche', 'Local', 'HeureDebut', 'HeureFin', 'CodeUsager', 'Etat', 'Plateau', 'IP', 'HeureInscription', 'Cours', 'Bloc', 'Question', 'url'], 'safe'],
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
        $query = Tbldemandes::find();

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
            'Poste' => $this->Poste,
            'HeureDebut' => $this->HeureDebut,
            'HeureFin' => $this->HeureFin,
            'HeureInscription' => $this->HeureInscription,
        ]);

        $query->andFilterWhere(['like', 'NomEleve', $this->NomEleve])
            ->andFilterWhere(['like', 'NoFiche', $this->NoFiche])
            ->andFilterWhere(['like', 'Local', $this->Local])
            ->andFilterWhere(['like', 'CodeUsager', $this->CodeUsager])
            ->andFilterWhere(['like', 'Etat', $this->Etat])
            ->andFilterWhere(['like', 'Plateau', $this->Plateau])
            ->andFilterWhere(['like', 'IP', $this->IP])
            ->andFilterWhere(['like', 'Cours', $this->Cours])
            ->andFilterWhere(['like', 'Bloc', $this->Bloc])
            ->andFilterWhere(['like', 'Question', $this->Question])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
