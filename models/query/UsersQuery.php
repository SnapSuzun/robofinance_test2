<?php

namespace app\models\query;


use yii\base\InvalidArgumentException;
use app\models\Users;

/**
 * Class UsersQuery
 * @package app\models\query
 */
class UsersQuery extends Users
{
    /**
     * Поиск пользователя по его id
     * @param int $id
     * @param bool $lockForUpdate
     * @return static
     */
    public static function findUserById(int $id, $lockForUpdate = false)
    {
        $model = $lockForUpdate ? static::findByIdForUpdate($id) : static::findOne($id);
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
        $sql = "SELECT * FROM " . static::tableName() . " WHERE id = {$id} FOR UPDATE";
        $model = static::findBySql($sql)->one();

        return $model;
    }
}