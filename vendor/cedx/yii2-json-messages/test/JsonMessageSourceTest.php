<?php
declare(strict_types=1);
namespace yii\i18n;

use function PHPUnit\Expect\{expect, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `yii\i18n\JsonMessageSource` class.
 */
class JsonMessageSourceTest extends TestCase {

  /**
   * @test JsonMessageSource::flatten
   */
  public function testFlatten() {
    $flatten = function($array) {
      return $this->flatten($array);
    };

    it('should merge the keys of a multidimensional array', function() use ($flatten) {
      $model = new JsonMessageSource;
      expect($flatten->call($model, []))->to->equal([]);
      expect($flatten->call($model, ['foo' => 'bar', 'baz' => 'qux']))->to->equal(['foo' => 'bar', 'baz' => 'qux']);
      expect($flatten->call($model, ['foo' => ['bar' => 'baz']]))->to->equal(['foo.bar' => 'baz']);

      $source = [
        'foo' => 'bar',
        'bar' => ['baz' => 'qux'],
        'baz' => ['qux' => [
          'foo' => 'bar',
          'bar' => 'baz'
        ]]
      ];

      expect($flatten->call($model, $source))->to->equal([
        'foo' => 'bar',
        'bar.baz' => 'qux',
        'baz.qux.foo' => 'bar',
        'baz.qux.bar' => 'baz'
      ]);
    });

    it('should allow different nesting separators', function() use ($flatten) {
      $source = [
        'foo' => 'bar',
        'bar' => ['baz' => 'qux'],
        'baz' => ['qux' => [
          'foo' => 'bar',
          'bar' => 'baz'
        ]]
      ];

      $model = new JsonMessageSource(['nestingSeparator' => '/']);
      expect($flatten->call($model, $source))->to->equal([
        'foo' => 'bar',
        'bar/baz' => 'qux',
        'baz/qux/foo' => 'bar',
        'baz/qux/bar' => 'baz'
      ]);

      $model = new JsonMessageSource(['nestingSeparator' => '->']);
      expect($flatten->call($model, $source))->to->equal([
        'foo' => 'bar',
        'bar->baz' => 'qux',
        'baz->qux->foo' => 'bar',
        'baz->qux->bar' => 'baz'
      ]);
    });
  }

  /**
   * @test JsonMessageSource::getMessageFilePath
   */
  public function testGetMessageFilePath() {
    $getMessageFilePath = function($category, $language) {
      return $this->getMessageFilePath($category, $language);
    };

    it('should return the proper path to the message file', function() use ($getMessageFilePath) {
      $model = new JsonMessageSource(['basePath' => '@root/test/fixtures']);
      $messageFile = str_replace('/', DIRECTORY_SEPARATOR, __DIR__.'/fixtures/fr/messages.json');
      expect($getMessageFilePath->call($model, 'messages', 'fr'))->to->equal($messageFile);
    });
  }

  /**
   * @test JsonMessageSource::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with the same public values', function() {
      $data = (new JsonMessageSource(['basePath' => '@root/test/fixtures']))->jsonSerialize();
      expect($data)->to->have->property('basePath')->that->equal('@root/test/fixtures');
      expect($data)->to->have->property('forceTranslation')->that->is->false;
    });
  }

  /**
   * @test JsonMessageSource::loadMessagesFromFile
   */
  public function testLoadMessagesFromFile() {
    $loadMessagesFromFile = function($messageFile) {
      return $this->loadMessagesFromFile($messageFile);
    };

    it('should properly load the JSON source and parse it as array', function() use ($loadMessagesFromFile) {
      $model = new JsonMessageSource(['basePath' => '@root/test/fixtures', 'enableNesting' => true]);
      $messageFile = \Yii::getAlias("{$model->basePath}/fr/messages.json");
      expect($loadMessagesFromFile->call($model, $messageFile))->to->equal([
        'Hello World!' => 'Bonjour le monde !',
        'foo.bar.baz' => 'FooBarBaz'
      ]);
    });

    it('should enable proper translation of source strings', function() {
      $model = new JsonMessageSource(['basePath' => '@root/test/fixtures', 'enableNesting' => true]);
      expect($model->translate('messages', 'Hello World!', 'fr'), 'Bonjour le monde !');
      expect($model->translate('messages', 'foo.bar.baz', 'fr'), 'FooBarBaz');
    });
  }

  /**
   * @test JsonMessageSource::__toString
   */
  public function testToString() {
    $model = (string) new JsonMessageSource(['basePath' => '@root/test/fixtures']);

    it('should start with the class name', function() use ($model) {
      expect($model)->to->startWith('yii\i18n\JsonMessageSource {');
    });

    it('should contain the instance properties', function() use ($model) {
      expect($model)->to->contain('"basePath":"@root/test/fixtures"')
        ->and->contain('"forceTranslation":false');
    });
  }
}
