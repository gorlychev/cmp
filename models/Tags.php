<?php

namespace app\models;

//use app\models\Yii;
use Yii;
use yii\db\ActiveRecord;

class Tags extends ActiveRecord { //extends \yii\base\BaseObject implements \yii\web\IdentityInterface {

    public function rules() {
        return [

            [['id', 'videoid'], 'integer'],
            [['id', 'tag'], 'string', 'max' => 140]
        ];
    }

    public static function tableName() {
        return 'tags';
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
        ];
    }

}
