<?php
namespace app\models;
use yii\db\ActiveRecord;

class Cart extends ActiveRecord {

    public function  addToCart($product, $qty = 1){
       //проверка наличия товара в корзине (в сессии)
        if(isset($_SESSION['cart'][$product->id]))
        {   //если такой товар есть, то увеличиваем его кол-во
            $_SESSION['cart'][$product->id]['qty'] += $qty;
        }else{
            //иначе добавляем новый товар в корзину - в массив cart из сессии
            $_SESSION['cart'][$product->id] = [
                'qty' => $qty,
                'name' => $product->name,
                'price' => $product->price,
                'img' => $product->img
            ];
        }

        //подсчет общего кол-ва товаров в корзине и запись его в сессию
        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? ($_SESSION['cart.qty'] + $qty) : ($qty) ;

        //подсчет общей суммы по корзине и запись ее в сессию
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? ($_SESSION['cart.sum'] + $product->price * $qty) : ($product->price * $qty) ;
    }

    public function  recalc($id){
        //проверка наличия товара в корзине (в сессии)
        if(!isset($_SESSION['cart'][$id]))
        {   //если его нет, то возвращаем false
            return false;
        }else{
            //иначу пересчитываем общее кол-во товаров в корзине (в сессии)
            $q = $_SESSION['cart'][$id]['qty']; //получаем кол-во удаляемого товара из корзины
            $_SESSION['cart.qty'] -= $q;

            //пересчитываем общую сумму в корзине (в сессии)
            //получаем сумму удаляемого товара из корзины
            $s = $_SESSION['cart'][$id]['price'] * $_SESSION['cart'][$id]['qty'] ;
            $_SESSION['cart.sum'] -= $s;

            //удаляем товар из корзины (сессии)
            unset($_SESSION['cart'][$id]);
        }
    }
}