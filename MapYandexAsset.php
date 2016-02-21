<?php
namespace msvdev\widgets\mappicker;

use yii\web\AssetBundle;

/**
 * Class MapYandexAsset
 * @package msvdev\widgets\mappicker
 */
class MapYandexAsset extends AssetBundle{
    /**
     * @var string map language
     */
    public static $language = 'en_US';
    /**
     * @var string yandex map api version
     */
    public static $version = '2.1';
    /**
     * @var string service url
     */
    public $serviceUrl = 'https://api-maps.yandex.ru';
    /**
     * @var string assets source path
     */
    public $sourcePath = '@msvdev/widgets/mappicker/assets';
    /**
     * @var array js options
     */
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    /**
     * @var array need js array
     */
    public $js = [
        'js/yandex-map.js'
    ];

    /**
     * Register service library
     */
    public function init(){
        parent::init();
        $this->js[] = $this->getMapLibrary();
    }

    /**
     * @return string build service library url
     */
    protected function getMapLibrary(){
        $libraryUrl = $this->serviceUrl.'/'.self::$version.'/?';
        $libraryUrl .= http_build_query([
            'lang' => self::$language,
        ]);
        return $libraryUrl;
    }
}
