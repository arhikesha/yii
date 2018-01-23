<?php


namespace frontend\controllers;

use frontend\models\Category;
use frontend\models\Product;
use Yii;

class ProductController extends AppController
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                ],
            ];
    }
    public function actionView($id)
    {
        //второй вариант получение Get значения
        //$id = Yii::$app->request->get('id');
        ///ленивая
        $product = Product::findOne($id);
        if(empty($product))
            throw new \yii\web\HttpException(404,
                'Такого товара нету.');
        //Жадная загузка
       // $product = Product::find()
       //     ->with('category')
       //     ->where(['id'=>$id])
       //     ->limit(1)
       //      ->one();

        $hits = Product::find()
            ->where(['hit'=>'1'])
            ->limit(6)
            ->all();

        $this->setMeta('E-SHOPPER |'.$product->name,$product->keywords,
            $product->description);

        return $this->render('view',compact('product','hits'));
    }

    
}