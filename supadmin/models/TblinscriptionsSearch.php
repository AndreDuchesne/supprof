<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tblinscriptions;

/**
 * TblinscriptionsSearch represents the model behind the search form about `app\models\Tblinscriptions`.
 */
class TblinscriptionsSearch extends Tblinscriptions
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idinscription', 'idParticipant', 'idEvenement'], 'integer'],
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
        $query = Tblinscriptions::find();

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
            'idinscription' => $this->idinscription,
            'idParticipant' => $this->idParticipant,
            'idEvenement' => $this->idEvenement,
        ]);

        return $dataProvider;
    }
}
