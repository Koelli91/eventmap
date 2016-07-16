/**
 * Created by Johannes Teklote on 16.07.2016.
 */
var sample_data = [{
    "location": {
        "lat": "51.49108",
        "lon": "7.57947",
        "street": "Bürenbrucherweg 19",
        "plz": "58239",
        "city": "Schwerte",
        "country": "DE",
        "name": "Yoga Vidya Zentrum"
    },
    "name": "Adam Bauer & The Love Keys",
    "description": "Meditationsmusik",
    "category": "Konzerte",
    "time_start": "2016-07-16T17:00:00+0200",
    "time_end": "",
    "photo": [],
    "website": "http://www.coolibri.de/veranstaltungen/heute/adam-bauer-the-love-keys--/1669214.html"
},
    {
        "location": {
            "lat": "51.47108",
            "lon": "7.57947",
            "street": "Bürenbrucherweg 19",
            "plz": "58239",
            "city": "Schwerte",
            "country": "DE",
            "name": "Yoga Vidya Zentrum"
        },
        "name": "Adam Bauer & The Love Keys",
        "description": "Meditationsmusik",
        "category": "Konzerte",
        "time_start": "2016-07-16T17:00:00+0200",
        "time_end": "",
        "photo": [],
        "website": "http://www.coolibri.de/veranstaltungen/heute/adam-bauer-the-love-keys--/1669214.html"
    },
    {
        "location": {
            "lat": "51.45108",
            "lon": "7.57947",
            "street": "Bürenbrucherweg 19",
            "plz": "58239",
            "city": "Schwerte",
            "country": "DE",
            "name": "Yoga Vidya Zentrum"
        },
        "name": "Adam Bauer & The Love Keys",
        "description": "Meditationsmusik",
        "category": "Konzerte",
        "time_start": "2016-07-16T17:00:00+0200",
        "time_end": "",
        "photo": [],
        "website": "http://www.coolibri.de/veranstaltungen/heute/adam-bauer-the-love-keys--/1669214.html"
    },
    {
        "location": {
            "lat": "51.43108",
            "lon": "7.57947",
            "street": "Bürenbrucherweg 19",
            "plz": "58239",
            "city": "Schwerte",
            "country": "DE",
            "name": "Yoga Vidya Zentrum"
        },
        "name": "Adam Bauer & The Love Keys",
        "description": "Meditationsmusik",
        "category": "Konzerte",
        "time_start": "2016-07-16T17:00:00+0200",
        "time_end": "",
        "photo": [],
        "website": "http://www.coolibri.de/veranstaltungen/heute/adam-bauer-the-love-keys--/1669214.html"
    }];

var map;

var markers = new Array(sample_data.length);

function initialize() {
    var latlng = new google.maps.LatLng(51.494229755747405, 7.4204922026910936);
    var mapOptions = {
        zoom: 8,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

    for (var i = 0; i < sample_data.length; i++) {
        addElement(sample_data, i);

        var marker = new google.maps.Marker({
            id: "marker_" + i,
            position: new google.maps.LatLng(sample_data[i].location.lat, sample_data[i].location.lon),
            icon: '../res/img/marker_red.png'
        });

        markers[i] = marker;

        marker.setMap(map);

        google.maps.event.addListener(markers[i], 'click', function() {
            alert('set blau ' + markers[i].id);
            markers[i].setIcon('../res/img/marker_blue.png');
        });

        /*marker.addListener('click', function() {
            marker.getId
            marker.setIcon('../res/img/marker_blue.png');
        });*/
    }
}
google.maps.event.addDomListener(window, 'load', initialize);

function parseTime(time) {
    //2016-07-16T17:00:00+0200
    var hour = time.substr(11, 2);
    var min = time.substr(14, 2);
    var sek = time.substr(17, 2);
    var date = hour + ":" + min + ":" + sek;
    return date;
}

function parseDay(time) {
    var year = time.substr(0, 4);
    var month = time.substr(5, 2);
    var day = time.substr(8, 2);
    var date = day + "." + month + "." + year;
    return date;
}
function loadEvents(config) {
  $.get("api/v1/events", config, function (data) {

  })
}
function initSearchForm() {
  $("#city").geocomplete({ details: "form" });
  $("#search").submit(function (event) {
    if( $("#lng").val() == "" || $("#lat").val() == ""){
      alert("Bitte Ort aus Liste auswählen")
      return false
    }
  })
}

function addElement(data, i) {
    alert('aufruf');
    var li = "\<li><div class=\"event-name\"><img src=\"http://placehold.it/100x100\">" +
        "\<div class=\"about-event-li\">" +
        "\<div class=\"event-list-headline\">" + data[i].name + "\<span class=\"event-kategorie\">" +
        data[i].category + "\</span></div>" +
        "\<p class=\"termin\">" + parseDay(data[i].time_start) + "\<span class=\"event-time\">" +
        parseTime(data[i].time_start) + "\</span></p>" +
        "\</div>" +
        "\</div>" +
        "\</li>";

    $("#eventlist").append(li);
}
