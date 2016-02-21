/**
 * @param mapObj Object created map
 * @param ymaps yandex Map object
 * @param options created Map options
 * @param options.coordinates Array map coordinates [lat,lng]
 * @param options.mapCenter Array map center coordinates [lat,lng]
 * @param options.mapZoom Start map zoom
 * @param options.containerId Map container id
 * @param options.attributeId Input html id
 * @param options.coordinatesDelimiter Coordinates delimiter
 * @param options.i18n language messages object
 */
function mapCreate(mapObj,ymaps,options){
    var mapCenter = options.mapCenter;
    if(options.coordinates.length == 2){
        mapCenter = options.coordinates;
    }
    mapObj = new ymaps.Map(options.containerId, {
        center: mapCenter,
        zoom: options.mapZoom,
        controls: ['zoomControl']
    });
    var searchControl = new ymaps.control.SearchControl({
        options: {
            noPlacemark: true
        }
    });
    mapObj.controls.add(searchControl);

    var clearButton = new ymaps.control.Button({
        data: {
            content: options.i18n.clearButton,
            title: options.i18n.clearButtonTitle
        },
        options: {
            selectOnClick: false
        }
    });
    mapObj.controls.add(clearButton, {float: 'right'});

    /* Add picker point */
    var pointCoordinates = options.coordinates;
    if(!pointCoordinates.length){
        pointCoordinates = mapCenter;
    }
    var pointPicker = new ymaps.Placemark(pointCoordinates, {}, {
        preset: 'islands#dotCircleIcon',
        iconColor: '#ff0000',
        draggable: true,
        visible: options.coordinates.length
    });
    mapObj.geoObjects.add(pointPicker);

    /* Events */
    mapObj.events.add('click', function (e) {
        pointPicker.geometry.setCoordinates(e.get('coords'));
        pointPicker.options.set('visible',true);
    });
    pointPicker.events.add('geometrychange',function (event) {
        var picker = event.get('target');
        var coordinates = picker.geometry.getCoordinates();
        setCoordinatesOfModel(coordinates,options);
    });
    clearButton.events.add('press',function(){
        pointPicker.options.set('visible',false);
        clearCoordinatesOfModel(options);
    });
}

/**
 * Set coordinates of model attribute
 * @param coordinates
 * @param options
 */
function setCoordinatesOfModel(coordinates,options){
    var attribute = document.getElementById(options.attributeId);
    attribute.value = coordinates[0] + options.coordinatesDelimiter + coordinates[1];
}
/**
 * Clear coordinates of model attribute
 * @param options
 * @constructor
 */
function clearCoordinatesOfModel(options){
    var attribute = document.getElementById(options.attributeId);
    attribute.value = null;
}