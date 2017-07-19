<?php
namespace app\components;
use yii\base\Widget;
use app\models\Category;
use Yii;

class MenuWidget extends Widget{
    public $model;
    public $tpl;
    public $data;
    public $tree;
    public $menuHtml;

    public function init(){
        parent::init();
        //определение какой вид использовать для отображения категорий
        if($this->tpl === null){
            $this->tpl = 'menu';
        }
        $this->tpl .= '.php';
    }

    //метод вывода визуализации виджета в страницу
    public function run(){

       if($this->tpl == 'menu.php') {
           //get cache
           //получаем кэш-переменную 'menu'
           $menu = Yii::$app->cache->get('menu');
           //если она не пуста, то возвращаем ее значение - ранее сформированный
           //html-код списка категорий
           if ($menu) return $menu;
       }

        //иначе получаем из БД все категории товаров, формируем из них дерево и html-код
        $this->data = Category::find()->indexBy('id')->asArray()->all();
        $this->tree = $this->getTree();
        $this->menuHtml = $this->getMenuHtml($this->tree);
        if($this->tpl == 'menu.php') {
            //set cache
            //и записываем этот html-код в кэш в переменную 'menu'
            Yii::$app->cache->set('menu', $this->menuHtml, 60);
            //echo '<pre>'. print_r($this->menuHtml). '</pre>';
        }

        return $this->menuHtml;
    }

    //преобразование массива в дерево
    protected function getTree(){
        $tree = [];
        foreach($this->data as  $id => &$node){
            if(!$node['parent_id']){
                $tree[$id] = &$node;
            }
            else
                $this->data[$node['parent_id']]['childs'][$node['id']] = &$node;
        }
        return $tree;
    }

    //формирование html-кода по каждому элементу массива $tree
    //и запись результирующего html-кода в переменную str
    protected function getMenuHtml($tree, $tab = ''){
        $str = '';
        foreach($tree as $category){
            $str .= $this->catToTemplate($category, $tab);
        }
        return $str;
    }

    //принимает очередную категорию и помещает ее в вид-шаблон - в menu.php или select.php
    //при этом, чтобы вывод не осуществлялся в браузер, используется буферизация ob_start()
    //после чего этот буферизированный вывод возвращается методом ob_get_clean()
    protected function catToTemplate($category, $tab){
        ob_start();
        include __DIR__.'/menu_tpl/'.$this->tpl;
        return ob_get_clean();
    }
}