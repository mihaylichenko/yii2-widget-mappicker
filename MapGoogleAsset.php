<?php
namespace msvdev\widgets\mappicker;

use yii\web\AssetBundle;

/**
 * Class MapGoogleAsset
 * @package msvdev\widgets\mappicker
 */
class MapGoogleAsset extends AssetBundle{
    /**
     * @var string map language
     */
    public static $language = 'en-US';
    /**
     * @var string google api key
     * @see https://developers.google.com/maps/documentation/javascript/get-api-key
     */
    public static $apiKey;
    /**
     * @var string service url
     */
    public $serviceUrl = 'https://maps.googleapis.com/maps/api/js';
    /**
     * @var string assets source path
     */
    public $sourcePath = '@msvdev/widgets/mappicker/assets';
    /**
     * @var array need js array
     */
    public $js = [
        'js/google-map.js'
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
        $libraryUrl = $this->serviceUrl .'?';
        $libraryUrl .= http_build_query([
            'language' => self::$language,
            'key' => self::$apiKey,
            'callback' => 'initMap'
        ]);
        return $libraryUrl;
    }
}
