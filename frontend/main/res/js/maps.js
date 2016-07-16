/**
 * Created by Johannes Teklote on 16.07.2016.
 */
var sample_data = [{
    "location": {
        "lat": "51.42108",
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

function initialize() {
    var latlng = new google.maps.LatLng(51.494229755747405, 7.4204922026910936);
    var mapOptions = {
        zoom: 8,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

    for (var i = 0; i < sample_data.length; i++) {
        var li = "\<li><div class=\"event-name\"><img src=\"http://placehold.it/100x100\">" +
            "\<div class=\"about-event-li\">" +
            "\<div class=\"event-list-headline\">" + sample_data[i].name + "\<span class=\"event-kategorie\">" +
            sample_data[i].category + "\</span></div>" +
            "\<p class=\"termin\">" + parseDay(sample_data[i].time_start) + "\<span class=\"event-time\">" +
            parseTime(sample_data[i].time_start) + "\</span></p>" +
            "\</div>" +
            "\</div>" +
            "\</li>";

        alert(sample_data[0].time_start);

        $("#eventlist").append(li);


        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(sample_data[i].location.lat, sample_data[i].location.lon),
            icon: '../res/img/marker_red.png'
        });

        marker.setMap(map);

        google.maps.event.addListener(marker, 'click', function () {
            alert('set blau ' + i);
            marker.setIcon('../res/img/marker_blue.png');
        });
    }
}
google.maps.event.addDomListener(window, 'load', initialize);

function parseTime(time) {
    //2016-07-16T17:00:00+0200
    var hour = time.substr(14, 16);
    var min = time.substr(17, 19);
    var sek = time.substr(20, 22);
    var date =hour + ":" + min + ":" + sek;
    alert('time: ' + date);
    return date;
}

function parseDay(time) {
    var year = time.substr(0, 4);
    var month = time.substr(5, 7);
    var day = time.substr(8, 10);
    var date =  day + "." + month + "." + year;
    alert('day ' + date);
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
