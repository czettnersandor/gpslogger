Zepto(function($){

    var map = new OpenLayers.Map("mapdiv");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lineStyle = {
      strokeColor: '#0000ff',
      strokeOpacity: 0.5,
      strokeWidth: 5
    };

    var lineLayer = new OpenLayers.Layer.Vector("Line Layer");

    map.addLayer(lineLayer);
    //map.addControl(new OpenLayers.Control.DrawFeature(lineLayer, OpenLayers.Handler.Path));

    var devices = []; // TODO

    var markerLayer = new OpenLayers.Layer.Markers("Markers");
    map.addLayer(markerLayer);

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
          lonLat = OpenLayers.Projection.transform([value.lng, value.lat], 'EPSG:4326', 'EPSG:3857')
          points.push(lonLat);
          lastLat = value.lat;
          lastLng = value.lng;
        });

        console.log(points);

        // Path
        var line = new OpenLayers.Geometry.LineString(points);

        var lineFeature = new OpenLayers.Feature.Vector(line, null, lineStyle);
        console.log(line);
        lineLayer.addFeatures([lineFeature]);

        // Marker
        lonLat = new OpenLayers.LonLat(lastLng,lastLat)
            .transform(
                new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                map.getProjectionObject() // to Spherical Mercator Projection
            );
        markerLayer.addMarker(new OpenLayers.Marker(lonLat));
        map.zoomToExtent(markerLayer.getDataExtent());
      },
      error: function(xhr, type){
        alert('Ajax error!');
      }
    });

});
