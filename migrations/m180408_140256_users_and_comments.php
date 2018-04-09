<?php

use yii\db\Migration;

/**
 * Class m180408_140256_users_and_comments
 */
class m180408_140256_users_and_comments extends Migration
{
    protected $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'balance' => $this->decimal(10, 2)->notNull(),
        ], $this->tableOptions);

        $this->createTable('comments', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'text' => $this->text()->notNull(),
        ], $this->tableOptions);

        $this->addForeignKey('fk_comments_user_id_user_id', 'comments', 'user_id', 'users', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
        $this->dropTable('comments');
    }
}
