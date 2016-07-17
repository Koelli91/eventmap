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
    initSearchForm()
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
            icon: 'res/img/marker_red.png'
        });

        marker.setMap(map);

        google.maps.event.addListener(marker, 'click', function() {
            if (prev != undefined) {
                prev.setIcon('res/img/marker_red.png')
            }
            this.setIcon('res/img/marker_blue.png');
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
function loadEvents() {
  var search_data = {
    "radius": $('#radius').val(),
    "lat": $('#lat').val(),
    "lng": $('#lng').val(),
    "category": $('#category').val(),
  }
  $.get("api/v1/events", search_data, function (data) {
    emptyEventList();
    for (var i = 0; i < data.length; i++) {
      addElement(data, i)
    }
  })
}
function initSearchForm() {

  if(getUrlParameter("lng") != undefined){
    $("#lng").val(getUrlParameter("lng"))
  }
  if(getUrlParameter("lat") != undefined){
    $("#lat").val(getUrlParameter("lat"))
  }
  if(getUrlParameter("radius") != undefined){
    $("#radius").val(getUrlParameter("radius"))
  }
  if(getUrlParameter("category") != undefined){
    $("#category").val(decodeURIComponent(getUrlParameter("category").replace(/\+/g, '%20')))
  }
  if(getUrlParameter("city") != undefined){
    $("#city").val(decodeURIComponent(getUrlParameter("city").replace(/\+/g, '%20')))
  }

  $("#city").geocomplete({ details: "form" });

  $("#search").submit(function (event) {
    if( $("#lng").val() == "" || $("#lat").val() == ""){
      alert("Bitte Ort aus Liste auswählen")
      return false
    }else {
      var latlng = { lat: parseFloat($("#lat").val()), lng: parseFloat($("#lng").val())}
      var circ = new google.maps.Circle({
        map: map,
        center: latlng,
        radius: parseInt($("#radius").val() ) * 1000,
        fillColor: '#EEEEEE',
        strokeColor: '#555555'
      });
      map.setCenter(latlng)
      map.fitBounds(circ.getBounds());
      loadEvents();
      return false
    }
  })
}
function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function emptyEventList() {
    $("#eventlist").empty();
}

function addElement(data, i) {
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
