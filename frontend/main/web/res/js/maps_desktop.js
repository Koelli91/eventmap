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

var prev;
function initialize() {
    var latlng = new google.maps.LatLng(51.494229755747405, 7.4204922026910936);
    var mapOptions = {
        zoom: 8,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

    for (var i = 0; i < sample_data.length; i++) {

        var li = "\<li>" +
            "\<div class=\"event-name\"><img src=\"http://placehold.it/100x100\">" +
            "\<div class=\"about-event-li\">" +
            "\<div class=\"event-list-headline\">" + sample_data[i].name + "\<span class=\"event-kategorie\">" + sample_data[i].category + "\</span></div>" +
            "\<div class=\"event-location\">" + sample_data[i].city + "\</div>" +
            "\<div class=\"termin\">" + parseDay(sample_data[i].time_start) + "\</div>" +
            "\<div class=\"event-time\">" + parseTime(sample_data[i].time_start) + parseTime(sample_data[i].time_end) + "\</div>" +
            "\</div>" +
            "\<div class=\"description\"><strong>Beschreibung</strong>\<p>Lorem</p><a href=\"http://fontawesome.io/icon/angle-down/\">Zur Website</a>\</div>" +
            "\<div class=\"material-icons more-button\">keyboard_arrow_down</div>" +
            "\<div>" +
            "\</li>";

        var marker = new google.maps.Marker({
            id: "marker_" + i,
            position: new google.maps.LatLng(sample_data[i].location.lat, sample_data[i].location.lon),
            icon: 'res/img/marker_red.png'
        });

        marker.setMap(map);

        google.maps.event.addListener(marker, 'click', function() {
            if (prev != undefined) {
                prev.setIcon('res/img/marker_red.png')
            }
            this.setIcon('res/img/marker_blue.png');
            $("#eventlist").empty();
            $("#eventlist").append(li);
            prev = this;
        });
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