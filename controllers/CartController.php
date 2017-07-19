<?php
namespace app\controllers;
use app\models\Product;
use app\models\Cart;
use app\models\Order;
use app\models\OrderItems;
use Yii;
class CartController extends  AppController{
    public function actionAdd(){
        $id = Yii::$app->request->get('id');
        //получаем от пользователя кол-во товара
        //и обязательно приводим это значение к целому, т.к. пользователь мог ввести сюда что угодно
        $qty = (int)Yii::$app->request->get('qty');
        //проверяем, что это непустая переменная
        //если она пустая то запишем кол-во 1, иначе задаим то значение, кот. указал пользователь
        $qty = !$qty ? 1 : $qty;
        $product = Product::findOne($id);
        if(empty($product))
            return false;

        $session = Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->addToCart($product, $qty);
        if(!Yii::$app->request->isAjax)
        {
            //если запрос обычный, возвращаем пользователя на страницу, с которой он отправлял запрос
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderPartial('cart-model', compact('session'));
    }

    public function actionClear(){
        $session = Yii::$app->session;
        $session->open();
        //удаление общего кол-ва товаров, общей суммы и массива с товарами из сессии
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        $session->remove('cart');
        $this->layout = false;
        return $this->render('cart-model', compact('session'));
    }

    public function actionDelItem(){
        $id = Yii::$app->request->get('id');
        $session = Yii::$app->session;
        $session->open();
        //вызов метода recalc модели Cart для удаления товара из корзины (из сессии)
        $cart = new Cart();
        $cart->recalc($id);
        $this->layout = false;
        return $this->render('cart-model', compact('session'));
    }

    public function  actionShow(){
        $session = Yii::$app->session;
        $session->open();
        $this->layout = false;
        return $this->render('cart-model', compact('session'));
    }

    public function actionView(){
        $session = Yii::$app->session;
        $session->open();
        //для формы оформления заказа создаем модель Order
        $order = new Order();
        if($order->load(Yii::$app->request->post())){
            $order->qty = $session['cart.qty'];
            $order->sum = $session['cart.sum'];
            //сохраняем заказ
            if($order->save()){
                //если заказ сохранен, сохраняем товары заказа в таблицу order_items
                $this->saveOrderItems($session['cart'], $order->id);
                //если заказ сохранен, выводим сообщение об успехе операции
                Yii::$app->session->setFlash('success', 'Ваш заказа принят. Менеджер свяжется с Вами');

                //отправка клиенту на почту списка товаров из его заказа
                Yii::$app->mailer->compose('order', compact('session'))
                    ->setFrom(['yiitest@meta.ua' => 'shop.com'])
                    ->setTo($order->email)
                    ->setSubject('Заказ с сайта')
                    ->send();

                //очистка корзины после формирования заказа в БД
                $session->remove('cart.qty');
                $session->remove('cart.sum');
                $session->remove('cart');
                return $this->refresh();
            }else{
                //иначе выводим сообщение о неудаче выполнения операции
                Yii::$app->session->setFlash('error', 'Ошибка сохранения заказа');
            }
        }
        $this->setMeta('Корзина');
        return $this->render('view', compact('session', 'order'));
    }

    protected function saveOrderItems($items, $order_id){
        foreach($items as $id => $item){
            //создаем объект модели OrderItems
            $order_items = new OrderItems();
            //заполняем поля этой модели
            $order_items->order_id = $order_id;
            $order_items->product_id = $id;
            $order_items->name = $item['name'];
            $order_items->price = $item['price'];
            $order_items->qty_item = $item['qty'];
            $order_items->sum_item = $item['qty']*$item['price'];
            //сохраняем запись в БД в таблицу order_items
            $order_items->save();
        }
    }
}