<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tblplateaux;

/**
 * TblplateauxSearch represents the model behind the search form about `app\models\Tblplateaux`.
 */
class TblplateauxSearch extends Tblplateaux
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtblplateaux'], 'integer'],
            [['plateau', 'nomplateau', 'plan', 'locaux', 'description'], 'safe'],
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
        $query = Tblplateaux::find();

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
            'idtblplateaux' => $this->idtblplateaux,
        ]);

        $query->andFilterWhere(['like', 'plateau', $this->plateau])
            ->andFilterWhere(['like', 'nomplateau', $this->nomplateau])
            ->andFilterWhere(['like', 'plan', $this->plan])
            ->andFilterWhere(['like', 'locaux', $this->locaux])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
