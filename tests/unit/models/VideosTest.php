<?php

namespace tests\models;

use app\models\Videos;

class VideosTest extends \Codeception\Test\Unit {

    public function testJsonParse() {
        $json = '{
    "videos": [
        {
            "tags": [
                "microwave",
                "cats",
                "peanutbutter"
            ],
            "url": "http://glorf.com/videos/3",
            "title": "science experiment goes wrong"
        },
        {
            "tags": [
                "dog",
                "amazing"
            ],
            "url": "http://glorf.com/videos/4",
            "title": "amazing dog can talk"
        }
    ]
}';
        $data = json_decode($json);

        expect_that(Videos::jsonParse($data));
        expect_not(Videos::jsonParse(null));
    }

}
