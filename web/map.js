Zepto(function($){

    var map = L.map('mapdiv').setView([51.5, -0.09], 13);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);


    var LeafIcon = L.Icon.extend({
        options: {
            //shadowUrl: '../docs/images/leaf-shadow.png',
            iconSize:     [38, 50],
            shadowSize:   [50, 64],
            iconAnchor:   [22, 94],
            shadowAnchor: [4, 62],
            popupAnchor:  [-3, -76]
        }
    });

    var greenIcon = new LeafIcon({iconUrl: '/map-marker.png'});

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
            lastLat = parseFloat(value.lat);
            lastLng = parseFloat(value.lng);
            points.push([lastLat, lastLng]);
            L.marker([lastLat, lastLng], {icon: greenIcon}).bindPopup("I am a green leaf.").addTo(map);
        });

        console.log(points);
        var arrow = L.polyline(points, {}).addTo(map);
        console.log(points);
        // create a red polyline from an array of LatLng points
        
        var arrow = L.polyline(points, {}).addTo(map);

        var polyline = L.polyline(points, {color: 'red'}).addTo(map);

        // zoom the map to the polyline
        map.fitBounds(polyline.getBounds());

        // Path
      },
      error: function(xhr, type){
        alert('Ajax error!');
      }
    });

});
