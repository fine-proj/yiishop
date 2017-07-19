<?php
namespace app\models;
use yii\db\ActiveRecord;

class Category extends ActiveRecord{

    //определение имени таблицы
    public static function tableName(){
        return 'category';
    }

    //описание связи таблицы category с таблицей product
    public function getProducts(){
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }
}