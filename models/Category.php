<?php
namespace app\models;
use yii\db\ActiveRecord;

class Category extends ActiveRecord{

    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'rico\yii2images\behaviors\ImageBehave',
            ]
        ];
    }

    //определение имени таблицы
    public static function tableName(){
        return 'category';
    }

    //описание связи таблицы category с таблицей product
    public function getProducts(){
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }
}