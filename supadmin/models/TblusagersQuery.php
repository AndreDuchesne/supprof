<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tblusagers]].
 *
 * @see user
 */
class TblusagersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tblusagers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tblusagers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}