<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Sources;
use app\models\Videos;
use app\models\Tags;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller {

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world') {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionSetInactive() {
        echo "\nSetting all as inactive";
        $params["status"] = 1;
        $videoModel       = Videos::findAll($params);
        if ($videoModel) {
            foreach ($videoModel as $item) {
                $item->status = 0;
                $item->update(false);
            }
        }
        $tagModel = Tags::findAll($params);
        if ($tagModel) {
            foreach ($tagModel as $item) {
                $item->status = 0;
                $item->update(false);
            }
        }
    }

    public function actionGetSource() {
        self::actionSetInactive();
        $params["status"] = 1;
        $modelSources     = Sources::findAll($params);
        foreach ($modelSources as $modelSource) {
            echo "\nParsing: " . $modelSource->name;
            self::actionParse($modelSource->url, $modelSource->format);
        }
    }

    public function actionParse($url, $format) {
        $status = false;
        if ($format == 'YAML') {
            $yaml = @file_get_contents($url);
            if ($yaml !== false) {
                $data   = yaml_parse($yaml);
                $status = Videos::yamlParse($data);
            }
            else {
                echo "\nSource " . $url . " is not available";
            }
        }
        elseif ($format == 'JSON') {
            $json = @file_get_contents($url);
            if ($json !== false) {
                $data   = json_decode($json);
                $status = Videos::jsonParse($data);
            }
            else {
                echo "\nSource " . $url . " is not available";
            }
        }
        else {
            echo "\nUnknown format feed for source: " . $url;
        }
        if ($status == true) {
            echo "\nFeed parsed OK";
        }
        else {
            echo "\nError parsing feed";
        }
    }

}
