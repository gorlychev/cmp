<?php

namespace app\models;

//use app\models\Yii;
use Yii;
use yii\db\ActiveRecord;
use yii\base\ErrorException;
use yii\console\ErrorHandler;
use yii\console\Controller;

class Videos extends ActiveRecord { //extends \yii\base\BaseObject implements \yii\web\IdentityInterface {

    public function rules() {
        return [
            [['id'], 'integer'],
            [['id', 'title', 'url'], 'string', 'max' => 400]
        ];
    }

    public static function tableName() {
        return 'videos';
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
        ];
    }

    public function yamlParse($data) {
        try {
            foreach ($data as $item) {
                $new         = false;
                $videosModel = Videos::findOne(["url" => $item["url"]]);
                if ($videosModel == null) {
                    $videosModel = new Videos();
                    echo "\nAdding new video: " . $item["name"];
                    $new         = true;
                }
                else {
                    echo "\nUpdating exisitng video: " . $videosModel->title;
                }
                $videosModel->title  = $item["name"];
                $videosModel->url    = $item["url"];
                $videosModel->status = 1;
                if ($new == true) {
                    $videosModel->save();
                }
                else {
                    $videosModel->update(false);
                }

                $tagArray = null;
                if (isset($item["labels"]) && $item["labels"]) {
                    $tagArray = explode(",", $item["labels"]);
                    foreach ($tagArray as $tag) {
                        $newTag    = false;
                        $modelTags = Tags::findOne(["videoid" => $videosModel->id, "tag" => trim($tag)]);
                        if ($modelTags == null) {
                            $modelTags = new Tags();
                            $newTag    = true;
                        }

                        $modelTags->tag     = trim($tag);
                        $modelTags->videoid = $videosModel->id;
                        $modelTags->status  = 1;
                        if ($newTag == true) {
                            $modelTags->save();
                        }
                        else {
                            $modelTags->update(false);
                        }
                    }
                }
            }
            return true;
        }
        catch (ErrorException $e) {
            Yii::warning("Error with feed.");
            return false;
        }
    }

    public function jsonParse($data) {
        try {
            foreach ($data->videos as $item) {
                $new         = false;
                $videosModel = Videos::findOne(["url" => $item->url]);
                if ($videosModel == null) {
                    $videosModel = new Videos();
                    echo "\nAdding new video: " . $item->title;
                    $new         = true;
                }
                else {
                    echo "\nUpdating exisitng video: " . $videosModel->title;
                }
                $videosModel->title  = $item->title;
                $videosModel->url    = $item->url;
                $videosModel->status = 1;
                if ($new == true) {
                    $videosModel->save();
                }
                else {
                    $videosModel->update(false);
                }
                if (isset($item->tags) && $item->tags) {
                    foreach ($item->tags as $tag) {
                        $newTag    = false;
                        $modelTags = Tags::findOne(["videoid" => $videosModel->id, "tag" => trim($tag)]);
                        if ($modelTags == null) {
                            $modelTags = new Tags();
                            $newTag    = true;
                        }

                        $modelTags->tag     = trim($tag);
                        $modelTags->videoid = $videosModel->id;
                        $modelTags->status  = 1;
                        if ($newTag == true) {
                            $modelTags->save();
                        }
                        else {
                            $modelTags->update(false);
                        }
                    }
                }
            }
            return true;
        }
        catch (ErrorException $e) {
            Yii::warning("Error with feed.");
            return false;
        }
    }

}
