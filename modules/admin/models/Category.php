<?php
namespace app\modules\admin\models;
use Yii;
class Category extends \yii\db\ActiveRecord
{
    public static function tableName(){
        return 'category';
    }

    public function getCategory(){
        return $this->hasOne(Category::className(), ['id'=>'parent_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['name', 'keywords', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '№ категории',
            'parent_id' => 'Родительская кат',
            'name' => 'Наименование',
            'keywords' => 'Ключ слова',
            'description' => 'Мета-описание',
        ];
    }
}
