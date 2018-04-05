# Map input widget for Yii2
The widget allows you to simplify the entry of geographic coordinates for your form. Support google and yandex maps services.

## Installation
The preferred way to install this extension is through composer.

```
$ php composer.phar require msvdev/yii2-widget-mappicker "*"
```

or add

```
"msvdev/yii2-widget-mappicker": "*"
```

to the `require` section of your `composer.json` file.

## Usage

###Minimal example for google maps

```php
echo $form->field($model, 'coordinatesAttribute')->widget(\msvdev\widgets\mappicker\MapInput::className(), ['apiKey' => 'google_api_key']);
```

### Minimal example for yandex maps
```php
echo $form->field($model, 'coordinatesAttribute')->widget(\msvdev\widgets\mappicker\MapInput::className(), ['service' => 'yandex']);
```

### Example settings
```php
use \msvdev\widgets\mappicker\MapInput;

echo $form->field($model, 'coordinatesAttribute')->widget(
    MapInput::className(), 
    [        
        'language' => 'en-Us', // map language, default is the same as in the app        
        'service' => 'google', // map service provider, "google" or "yandex", default "google"       
        'apiKey' => '', // required google maps
        'coordinatesDelimiter' => '@', // attribute coordinate string delimiter, default "@" (lat@lng)       
        'mapWidth' => '800px', // width map container, default "500px"
        'mapHeight' => '500px', // height map container, default "500px"
        'mapZoom' => '14', // map zoom value, default "10"
        'mapCenter' => [55.753338, 37.622861], // coordinates center map with an empty attribute, default Moscow        
    ]
);
```