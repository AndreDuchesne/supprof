<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tblblocs]].
 *
 * @see Tblblocs
 */
class TblblocsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tblblocs[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tblblocs|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}