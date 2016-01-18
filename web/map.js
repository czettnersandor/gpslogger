Zepto(function($){

    var iconStyle = new ol.style.Style({
        image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            anchor: [0.5, 46],
            anchorXUnits: 'fraction',
            anchorYUnits: 'pixels',
            opacity: 0.75,
            src: 'https://www.pragueeventscalendar.com/src/templates/images/web/marker_icon.png'
        }))
    });

    var vectorSource = new ol.source.Vector({
        projection: ol.proj.get('EPSG:4326')
    });

    //add the feature vector to the layer vector, and apply a style to whole layer
    var vectorLayer = new ol.layer.Vector({
        source: vectorSource,
        style: iconStyle
    });

    var map = new ol.Map({
        layers: [
            new ol.layer.Tile({ source: new ol.source.OSM() }),
            vectorLayer
        ],
        view: new ol.View2D({
            projection: 'EPSG:1234',
            center: ol.proj.transform(new ol.Coordinate(-111, 45), 'EPSG:4326', 'EPSG:1234'),
            zoom: 3
        }),
        target: 'mapdiv',
        renderer: 'canvas',
    });

    // Get last positions
    $.ajax({
      type: 'GET',
      url: '/allhistory',
      dataType: 'json',
      timeout: 300,
      context: $('body'),
      success: function(data){
        var lastLat, lastLng;
        var points = []; // for path

        $.each(data, function(key, value){
            console.log(value);
            points.push([value.lng, value.lat]);
            lastLat = value.lat;
            lastLng = value.lng;
        });

        console.log(points);

        // Path

        var points = [
        [lastLng, lastLat], [0, 0]
        ];

        lineFeature = new ol.Feature({
            geometry: new ol.geom.LineString(points),
            name: 'Line'
        });

        vectorSource.addFeature(lineFeature);


        console.log(lineFeature);
        // Marker

        var iconFeature = new ol.Feature({
            geometry: new
              ol.geom.Point([lastLng, lastLat]),
            name: 'Null Island ',
            population: 4000,
            rainfall: 500
        });
        vectorSource.addFeature(iconFeature);

        //map.zoomToExtent(markerLayer.getDataExtent());
      },
      error: function(xhr, type){
        alert('Ajax error!');
      }
    });

});
