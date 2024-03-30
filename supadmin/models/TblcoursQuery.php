<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tblcours]].
 *
 * @see Tblcours
 */
class TblcoursQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Tblcours[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Tblcours|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
