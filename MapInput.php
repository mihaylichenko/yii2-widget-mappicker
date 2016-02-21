<?php
namespace msvdev\widgets\mappicker;

use yii\base\InvalidConfigException;
use \yii\helpers\Html;
use \yii\web\View;
use \yii\widgets\InputWidget;
use \Yii;

/**
 * Class Map
 * @package msvdev\mappicker
 */
class MapInput extends InputWidget{
    /**
     * @var string map container id auto prefix
     */
    public static $autoIdPrefix = 'map';
    /**
     * @var string lat@lng, attribute coordinate delimiter
     */
    public static $coordinatesDelimiter = '@';
    /**
     * @var string map language
     * Default is the same as in the app
     */
    public $language;
    /**
     * @var string map service, valid values "google" or "yandex"
     */
    public $service = 'google';
    /**
     * @var string google api key
     * @see https://developers.google.com/maps/documentation/javascript/get-api-key
     */
    public $apiKey;
    /**
     * @var string map container width
     */
    public $mapWidth = '500px';
    /**
     * @var string map container height
     */
    public $mapHeight = '400px';
    /**
     * @var int map zoom value
     */
    public $mapZoom = 10;
    /**
     * @var array [lat,lng] coordinates center map with an empty attribute, default Moscow
     */
    public $mapCenter = [55.753338, 37.622861];

    /**
     * Initializes the widget.
     * @throws InvalidConfigException
     */
    public function init(){
        parent::init();
        $this->registerTranslations();
        $this->registerAssets();
    }

    /**
     * Run the widget
     * @return string
     */
    public function run(){
        $containerMap = Html::tag('div','',[
            'id' => $this->id,
            'style' => 'width:'.$this->mapWidth. '; height:'.$this->mapHeight
        ]);
        $inputCoordinates = Html::activeHiddenInput(
            $this->model,
            $this->attribute,
            ['id' => $this->getAttributeId()]
        );
        return $containerMap.$inputCoordinates;
    }

    /**
     * Map language
     * @return string
     */
    public function getMapLanguage(){
        if($this->language){
            return $this->language;
        }
        return Yii::$app->language;
    }

    /**
     * Register translations source
     */
    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['msvdev/widgets/mappicker/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => '@msvdev/widgets/mappicker/messages',
            'fileMap'        => [
                'msvdev/widgets/mappicker/main' => 'main.php',
            ]
        ];
    }

    /**
     * Widget translations
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('msvdev/widgets/mappicker/' . $category, $message, $params, $language);
    }

    /**
     * Return coordinates attribute html id
     * @return string
     */
    protected function getAttributeId(){
        return $this->id.'-'.$this->attribute;
    }

    /**
     * Get coordinates array of the attribute model
     * @return array
     */
    protected function getCoordinates(){
        $attribute = $this->model->{$this->attribute};
        if($attribute){
            return explode(self::$coordinatesDelimiter,$attribute);
        }
        return [];
    }

    /**
     * Return js option the map
     * @return string
     */
    protected function getMapJsOptions(){
        $object = [
            'containerId' => $this->id,
            'attributeId' => $this->getAttributeId(),
            'coordinatesDelimiter' => self::$coordinatesDelimiter,
            'coordinates' => $this->getCoordinates(),
            'mapCenter' => $this->mapCenter,
            'mapZoom' => $this->mapZoom,
            'i18n' => [
                'clearButton' => self::t('main','Clear',[],$this->getMapLanguage()),
                'clearButtonTitle' => self::t('main','Click to clear the selection',[],$this->getMapLanguage()),
            ]
        ];
        return json_encode($object);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets(){
        $view = $this->getView();
        $mapId = $this->id;
        $options = $this->getMapJsOptions();
        if($this->service == 'yandex'){
            MapYandexAsset::$language = $this->getMapLanguage();
            MapYandexAsset::register($view);
            $view->registerJs("
                var $mapId = {};
                ymaps.ready(function () {
                    mapCreate($mapId,ymaps,$options);
                });
            ",View::POS_END);
        }
        elseif($this->service == 'google'){
            if(!$this->apiKey){
                throw new InvalidConfigException("Api key required for google maps.");
            }
            MapGoogleAsset::$apiKey = $this->apiKey;
            MapGoogleAsset::$language  = $this->getMapLanguage();
            MapGoogleAsset::register($view);
            $view->registerJs("
                var $mapId = {};
                function initMap(){
                    mapCreate($mapId, $options);
                };
            ",View::POS_HEAD);
        }
        else{
            throw new InvalidConfigException("This service \"".$this->service."\" is not supported.");
        }
    }

}