/**
 * @param mapObj Object created map
 * @param options created Map options
 * @param options.coordinates Array map coordinates [lat,lng]
 * @param options.mapCenter Array map center coordinates [lat,lng]
 * @param options.mapZoom Start map zoom
 * @param options.containerId Map container id
 * @param options.attributeId Input html id
 * @param options.coordinatesDelimiter Coordinates delimiter
 * @param options.i18n language messages object
 */
function mapCreate(mapObj, options){
    var coordinates = {};
    var pointVisible = false;
    if(options.coordinates.length == 2){
        pointVisible = true;
        coordinates.lat = parseFloat(options.coordinates[0]);
        coordinates.lng = parseFloat(options.coordinates[1]);
    }
    else{
        coordinates.lat = parseFloat(options.mapCenter[0]);
        coordinates.lng = parseFloat(options.mapCenter[1]);
    }

    mapObj = new google.maps.Map(document.getElementById(options.containerId), {
        center: coordinates,
        zoom: options.mapZoom
    });
    var pointPicker = new google.maps.Marker({
        position: coordinates,
        map: mapObj,
        draggable: true,
        visible: pointVisible
    });

    /** Set clear button control */

    var clearControl = clearButton(pointPicker, options);
    mapObj.controls[google.maps.ControlPosition.TOP_RIGHT].push(clearControl);

    /*Events */
    google.maps.event.addListener(mapObj, 'click', function(event) {
        pointPicker.setPosition(event.latLng);
        pointPicker.setVisible(true);
        setCoordinatesOfModel(event.latLng,options);
    });
}

/**
 * Map clear button
 * @param pointPicker
 * @param options
 * @returns {Element}
 */
function clearButton(pointPicker,options){
    var controlDiv = document.createElement('div');
    controlDiv.style.index = 1;
    controlDiv.style.paddingTop = '10px';
    controlDiv.style.paddingRight = '10px';

    var controlUI = document.createElement('div');
    controlUI.style.backgroundColor = '#fff';
    controlUI.style.border = '2px solid #fff';
    controlUI.style.borderRadius = '3px';
    controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
    controlUI.style.cursor = 'pointer';
    controlUI.style.marginBottom = '22px';
    controlUI.style.textAlign = 'center';
    controlUI.title = options.i18n.clearButtonTitle;
    controlDiv.appendChild(controlUI);

    // Set CSS for the control interior.
    var controlText = document.createElement('div');
    controlText.style.color = 'rgb(25,25,25)';
    controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
    controlText.style.fontSize = '12px';
    controlText.style.lineHeight = '25px';
    controlText.style.paddingLeft = '5px';
    controlText.style.paddingRight = '5px';
    controlText.innerHTML = options.i18n.clearButton;
    controlUI.appendChild(controlText);

    // Click event
    controlUI.addEventListener('click', function() {
       pointPicker.setVisible(false);
       clearCoordinatesOfModel(options);
    });
    return controlDiv;
}

/**
* Set coordinates of model attribute
* @param coordinates
* @param options
*/
function setCoordinatesOfModel(coordinates,options){
    var attribute = document.getElementById(options.attributeId);
    attribute.value = coordinates.lat() + options.coordinatesDelimiter + coordinates.lng();
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
