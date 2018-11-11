<?php

namespace app\models;

//use app\models\Yii;
use Yii;
use yii\db\ActiveRecord;

class Sources extends ActiveRecord { //extends \yii\base\BaseObject implements \yii\web\IdentityInterface {

    public function rules() {
        return [

            [['id'], 'integer'],
            [['id', 'name', 'url'], 'string', 'max' => 140]
        ];
    }

    public static function tableName() {
        return 'sources';
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
        ];
    }

}
