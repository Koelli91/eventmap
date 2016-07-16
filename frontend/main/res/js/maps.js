/**
 * Created by Johannes Teklote on 16.07.2016.
 */
var data = [
    {"Titel": "Hallo", "Beschreibung": "blub", "x": "51.494229755747405", "y": "7.4204922026910936"},
    {"Titel": "Hallo", "Beschreibung": "blub", "x": "51.474229755747405", "y": "7.4204922026910936"},
    {"Titel": "Hallo", "Beschreibung": "blub", "x": "51.454229755747405", "y": "7.4204922026910936"},
    {"Titel": "Hallo", "Beschreibung": "blub", "x": "51.434229755747405", "y": "7.4204922026910936"},
    {"Titel": "Hallo", "Beschreibung": "blub", "x": "51.414229755747405", "y": "7.4204922026910936"}


];

var map;

function initialize() {
    var latlng = new google.maps.LatLng(51.494229755747405, 7.4204922026910936);
    var mapOptions = {
        zoom: 8,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

    for (var i = 0; i < data.length; i++) {
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(data[i].x, data[i].y),
            icon: '../res/img/marker_red.png'
        });

        marker.setMap(map);

        google.maps.event.addListener(marker, 'click', function () {
            /*
             m.setIcon('../res/img/marker_red.png');
             }*/
            alert('set blau ' + i);
            marker.setIcon('../res/img/marker_blue.png');
        });
    }
}
google.maps.event.addDomListener(window, 'load', initialize);