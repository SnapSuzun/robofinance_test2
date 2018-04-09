<?php

namespace app\models;


use app\components\db\ActiveRecord;
use app\models\query\UsersQuery;
use yii\base\InvalidArgumentException;

/**
 * Class Users
 * @package app\models
 *
 * @property integer $id
 * @property string $name
 * @property float $balance
 */
class Users extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @return UsersQuery
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'balance'], 'required'],
            ['name', 'string', 'max' => 255],
            ['balance', 'number'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('app', 'Username'),
            'balance' => \Yii::t('app', 'Balance'),
        ];
    }

    /**
     * Перевод средств между пользователями
     * @param integer $fromUserId
     * @param integer $toUserId
     * @param float $amount
     * @throws \Throwable
     */
    public static function transferMoneyBetweenUsers(int $fromUserId, int $toUserId, float $amount)
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException(\Yii::t('app', 'Amount must be positive.'));
        }
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $fromUserId = UsersQuery::findUserById($fromUserId, true);
            $toUserId = UsersQuery::findUserById($toUserId, true);

            if (!$fromUserId->canWriteOffMoney($amount)) {
                throw new \ErrorException(\Yii::t('app', 'User #{id} - {name} does not have enough funds to perform the transfer operation.', [
                    'id' => $fromUserId->id,
                    'name' => $fromUserId->name
                ]));
            }

            $fromUserId->balance -= $amount;
            $toUserId->balance += $amount;

            if (!$fromUserId->save()) {
                throw new \ErrorException(\Yii::t('app', 'An error occurred while saving data of user #{id} - {name}: {error}', [
                    'id' => $fromUserId->id,
                    'name' => $fromUserId->name,
                    'error' => $fromUserId->getFirstErrorAsString()
                ]));
            }

            if (!$toUserId->save()) {
                throw new \ErrorException(\Yii::t('app', 'An error occurred while saving data of user #{id} - {name}: {error}', [
                    'id' => $toUserId->id,
                    'name' => $toUserId->name,
                    'error' => $toUserId->getFirstErrorAsString()
                ]));
            }

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * Проверка на возможность списания средств со счета
     * @param float $amount
     * @return bool
     */
    public function canWriteOffMoney(float $amount)
    {
        return ($this->balance - $amount) >= 0;
    }
}