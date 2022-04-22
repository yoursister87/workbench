<?php

namespace app\models;

use Yii;
class Supplier extends \yii\db\ActiveRecord
{
    /**
     */
    public static function tableName()
    {
        return 'supplier';
    }

    public function rules()
    {
        return [
            [['t_status'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 3],
            [['code'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            't_status' => 'T Status',
        ];
    }


}
