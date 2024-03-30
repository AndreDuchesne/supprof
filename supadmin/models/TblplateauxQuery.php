<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tblplateaux]].
 *
 * @see Tblplateaux
 */
class TblplateauxQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tblplateaux[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tblplateaux|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}