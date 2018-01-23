<?php

namespace frontend\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{

    public static function tableName(){
        return 'user';
    }

    //возвращает найденого пользователя по id
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    //
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        return static::findOne(['access_token' => $token]);
    }

    //ищем ползователя по имени(Логин)
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->id;
    }

    //auth_key - как в БД
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    //auth_key - как в БД
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    //сравнивает пароль  в бд с тем что в поле
    public function validatePassword($password)
    {
     // return $this->password === $password;
        //хеширование пароля
      return \Yii::$app->security->validatePassword($password, $this->password);
    }

    //Генерирует случайную строку для куки auth_key
    public function generateAuthKey(){
       $this->auth_key = \Yii::$app->security->generateRandomString();
    }
}
