/**
 * Created by Johannes Teklote on 16.07.2016.
 */
var sample_data;
$.ajax({
    "method":"GET",
    "url":"http://localhost:8000/api/v1/events?lat=51.494229755747405&lon=7.4204922026910936&radius=25",
    "returnType":"JSON"
}).done(function (data) {
    sample_data = JSON.parse(data);
    console.log(data);
    if (window.innerWidth < 500) {
        function mobilinitialize() {
            $("#eventlist").empty();
            for (var i = 0; i < sample_data.length; i++) {
                var li = addElement(sample_data, i);
                $("#eventlist").append(li);
            }
        };
        mobilinitialize();
    } else {
        google.maps.event.addDomListener(window, 'load', initialize());
    }
});

var map;

var prev;

function initialize() {
    var latlng = new google.maps.LatLng(51.494229755747405, 7.4204922026910936);
    var mapOptions = {
        zoom: 11,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

    for (var i = 0; i < sample_data.length; i++) {
        var li = addElement(sample_data, i);

        var marker = new google.maps.Marker({
            id: "marker_" + i,
            position: new google.maps.LatLng(sample_data[i].longitude, sample_data[i].latitude),
            icon: 'res/img/marker_red.png'
        });
        //TODO Scraper andersrum auslesen

        marker.setMap(map);

        google.maps.event.addListener(marker, 'click', function () {
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

function parseTime(time) {
    if (time == undefined || time == "") {
        return "";
    }
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

function addElement(sample_data, i) {
    var out = "\<li>" +
        "\<div class=\"event-info\"><img src=\"http://placehold.it/100x100\">" +
        "\<div class=\"about-event-li\">" +
        "\<div class=\"event-list-headline\">" + sample_data[i].name + "\</div>" +
        "\<div class=\"event-kategorie text-muted\">(" + sample_data[i].category + ")\</div>" +
        "\<div class=\"event-location\">" + sample_data[i].street_no + "\<br>" + sample_data[i].zip_code + " " + sample_data[i].city + "\<br>" + sample_data[i].country + "\</div>" +
        "\<div class=\"termin\">" + parseDay(sample_data[i].begin) + "\</div>" +
        "\<div class=\"event-time\">" + parseTime(sample_data[i].begin) + parseTime(sample_data[i].end) + "\</div>" +
        "\</div>" +
        "\<div class=\"description\"><strong>Beschreibung</strong>\<p>Lorem</p><a class=\"website_link\" href=\"http://fontawesome.io/icon/angle-down/\">Zur Website</a>\</div>" +
        "\<div class=\"material-icons more-button\">keyboard_arrow_down</div>" +
        "\</div>" +
        "\</li>";
    return out;
}