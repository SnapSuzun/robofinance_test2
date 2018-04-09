<?php

namespace app\commands;


use app\models\Users;
use yii\base\InvalidArgumentException;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Операции по переводу средств
 * Class MoneySenderController
 * @package app\commands
 */
class MoneyTransferController extends Controller
{
    /**
     * Перевод средств между пользователями
     * @param int $fromUserId
     * @param int $toUserId
     * @param float $amount
     * @throws \Throwable
     */
    public function actionTransferBetweenUsers(int $fromUserId, int $toUserId, float $amount)
    {
        try {
            Users::transferMoneyBetweenUsers($fromUserId, $toUserId, $amount);
        } catch (\Throwable $e) {
            if ($e instanceof \ErrorException || $e instanceof InvalidArgumentException) {
                Console::error($e->getMessage());
                return;
            } else {
                throw $e;
            }
        }
        Console::stdout('The money was successfully transferred.');
    }
}