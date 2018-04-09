<?php

namespace app\components\db;

/**
 * Class ActiveRecord
 * @package components\db
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * @param null $attribute
     * @return array
     */
    public function getErrorsAsArray($attribute = null)
    {
        $result = [];

        $errorsAttributes = $this->getErrors($attribute);

        foreach ($errorsAttributes as $errorsAttribute) {
            foreach ($errorsAttribute as $error) {
                $result[] = $error;
            }
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getFirstErrorAsString()
    {
        $errors = $this->getErrorsAsArray();

        return array_shift($errors);
    }
}