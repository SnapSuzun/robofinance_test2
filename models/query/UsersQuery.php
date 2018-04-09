<?php

namespace app\models\query;


use app\models\Users;
use yii\base\InvalidArgumentException;
use yii\db\ActiveQuery;

/**
 * Class UsersQuery
 * @package app\models\query
 *
 * @see app\models\Users
 */
class UsersQuery extends ActiveQuery
{
    /**
     * Поиск пользователя по его id
     * @param int $id
     * @param bool $lockForUpdate
     * @return static
     */
    public static function findUserById(int $id, $lockForUpdate = false)
    {
        $model = $lockForUpdate ? static::findByIdForUpdate($id) : Users::findOne($id);
        if (!$model) {
            throw new InvalidArgumentException(\Yii::t('app', 'User with id #{id} was not found.', ['id' => $id]));
        }
        return $model;
    }

    /**
     * @param int $id
     * @return UsersQuery|array|null|\yii\db\ActiveRecord
     */
    public static function findByIdForUpdate(int $id)
    {
        $sql = "SELECT * FROM " . Users::tableName() . " WHERE id = {$id} FOR UPDATE";
        $model = Users::findBySql($sql)->one();

        return $model;
    }
}