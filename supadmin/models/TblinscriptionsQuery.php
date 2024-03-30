<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tblinscriptions]].
 *
 * @see Tblinscriptions
 */
class TblinscriptionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tblinscriptions[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tblinscriptions|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}