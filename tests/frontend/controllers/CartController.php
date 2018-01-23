<?php


namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\controllers\AppController;
use frontend\models\Product;
use frontend\models\Cart;
use app\models\OrderItems;
use app\models\Order;
use Yii;
class CartController extends AppController
{
    public function actionAdd()
    {
        $id = Yii::$app->request->get('id');
        $qty =(int)Yii::$app->request->get('qty');
        $qty =!$qty ? 1 : $qty;
      // debug($id);
        $product = Product::findOne($id);
        if(empty($product)) return false;
        $session = Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->addToCart($product,$qty);
        //для того если отключен JS
        if(!Yii::$app->request->isAjax){
            return $this->redirect(Yii::$app->request->referrer);
        }
        //debug($session['cart']);
        $this->layout = false;
        return $this->render('cart-modal',compact('session'));
    }
    public function actionClear()
    {
        $session = Yii::$app->session;
        $session->open();
        $session->remove('cart');
        $session->remove('cart.qty');
        $session->remove('cart.sum');
        $this->layout = false;
        return $this->render('cart-modal',compact('session'));
    }

    public function actionDelItem()
    {
        $id = Yii::$app->request->get('id');
        $session = Yii::$app->session;
        $session->open();
        $cart = new Cart();
        $cart->recalc($id);
        $this->layout = false;
        return $this->render('cart-modal',compact('session'));
    }
    public function actionShow()
    {
        $id = Yii::$app->request->get('id');
        $session = Yii::$app->session;
        $session->open();
        $this->layout = false;
        return $this->render('cart-modal',compact('session'));
    }

        public function actionView()
        {
            //debug(Yii::$app->params['adminEmail']);
            $session = Yii::$app->session;
            $session->open();
            $this->setMeta('Корзина');
            $order =  new Order();
            if($order->load(Yii::$app->request->post()))
            {
                $order->qty = $session['cart.qty'];
                $order->sum = $session['cart.sum'];
                if($order->save())
                {
                    $this->saveOrderItems($session['cart'],$order->id);
                    Yii::$app->session->setFlash('success','Ваш заказ принят');

                   /* Yii::$app->mailer->compose('order',['session'=>$session])
                        ->setFrom(['zachariy@gmail.com'])
                        ->setTo($order->email)
                        ->setSubject('Заказа')
                        ->send();*/

                    $session->remove('cart');
                    $session->remove('cart.qty');
                    $session->remove('cart.sum');
                    return $this->refresh();
                }else
                {
                    Yii::$app->session->setFlash('error','Ошибка оформление заказа');
                }
            }
            return $this->render('view',compact('session','order'));
        }
    protected function saveOrderItems($items, $order_id)
    {
        foreach($items as $id => $item)
        {
            $order_items = new OrderItems();
            $order_items->order_id = $order_id;
            $order_items->product_id = $id;
            $order_items->name = $item['name'];
            $order_items->price = $item['price'];
            $order_items->qty_item = $item['qty'];
            $order_items->sum_item = $item['qty'] * $item['price'];
            $order_items->save();
        }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $login = new LoginForm();
        if ($login->load(Yii::$app->request->post()) && $login->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'login' => $login,
            ]);
        }
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}