<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tblblocs;

/**
 * TblblocsSearch represents the model behind the search form about `app\models\Tblblocs`.
 */
class TblblocsSearch extends Tblblocs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idtblblocs', 'numeroBloc'], 'integer'],
            [['sujet', 'plateau', 'cours', 'url'], 'safe'],
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
        $query = Tblblocs::find();

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
            'idtblblocs' => $this->idtblblocs,
            'numeroBloc' => $this->numeroBloc,
        ]);

        $query->andFilterWhere(['like', 'sujet', $this->sujet])
            ->andFilterWhere(['like', 'plateau', $this->plateau])
            ->andFilterWhere(['like', 'cours', $this->cours])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
