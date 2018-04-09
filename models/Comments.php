<?php

namespace app\models;


use yii\db\ActiveRecord;

/**
 * Class Comments
 * @package app\models
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $text
 */
class Comments extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%comments}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['user_id', 'text'], 'required'],
            ['user_id', 'integer'],
            ['text', 'string'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'user_id' => \Yii::t('common', '# of user'),
            'text' => \Yii::t('common', 'Comment'),
        ];
    }
}