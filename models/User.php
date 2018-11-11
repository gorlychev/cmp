<?php

namespace app\models;

//use app\models\Yii;
use Yii;
use yii\db\ActiveRecord;

class User extends ActiveRecord { //extends \yii\base\BaseObject implements \yii\web\IdentityInterface {
    /*
      public $id;
      public $login;
      public $username;
      public $password;
     */
    /* public $authKey;
      public $accessToken; */
    /* private static $users = [
      '100' => [
      'id'          => '100',
      'username'    => 'admin',
      'password'    => 'admin',
      'authKey'     => 'test100key',
      'accessToken' => '100-token',
      ],
      '101' => [
      'id'          => '101',
      'username'    => 'demo',
      'password'    => 'demo',
      'authKey'     => 'test101key',
      'accessToken' => '101-token',
      ],
      ];
     */

    public function rules() {
        return [
            [['login', 'password'], 'required'],
            [['id'], 'integer'],
            [['login', 'password', 'name'], 'string', 'max' => 140]
        ];
    }

    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600
                                    * 24 * 30 : 0);
        }
        return false;
    }

    public static function tableName() {
        return 'users';
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
        ];
    }

}
