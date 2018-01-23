<?php

namespace app\modules\admin\models;

use Yii;


class Order extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'order';
    }

    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::className(),['order_id'=>'id']);
    }

    public function rules()
    {
        return [
            [['created_id', 'update_id', 'qty', 'sum', 'name', 'email', 'phones', 'address'], 'required'],
            [['created_id', 'update_id'], 'safe'],
            [['qty'], 'integer'],
            [['sum'], 'number'],
            [['status'], 'string'],
            [['name', 'email', 'phones', 'address'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'Номер закааз',
            'created_id' => 'Дата создания',
            'update_id' => 'Дата изменения',
            'qty' => 'Количество',
            'sum' => 'Сумма',
            'status' => 'Статус',
            'name' => 'имя',
            'email' => 'Email',
            'phones' => 'Телефон',
            'address' => 'Адрес',
        ];
    }
}
