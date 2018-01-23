<?php

namespace app\modules\admin\models;

use Yii;


class Category extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'category';
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['name', 'keywords', 'description'], 'string', 'max' => 255],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => '№ категориии',
            'parent_id' => 'Родительская категория',
            'name' => 'Нaзвание',
            'keywords' => 'Ключевые слова',
            'description' => 'Мета описание',
        ];
    }
}
