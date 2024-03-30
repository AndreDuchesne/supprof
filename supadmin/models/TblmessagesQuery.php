<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Tblmessages]].
 *
 * @see Tblmessages
 */
class TblmessagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tblmessages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tblmessages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}