/**
 * Created by Johannes Teklote on 16.07.2016.
 */
$(function () {

    initSearchForm();

    var search_data = {
      "radius": $('#radius').val(),
      "lat": $('#lat').val(),
      "lng": $('#lng').val(),
      "category": $('#category').val(),
    }
    var event_data;
    $.ajax({
        "method": "GET",
        "url": "http://localhost:8000/api/v1/events?lat=" + search_data["lat"] + "&lon="+ search_data["lon"] +"&radius=" + search_data["radius"] + "&category:" + search_data["category"],
        "returnType": "JSON",
        "cache": false
    }).done(function (data) {
        event_data = JSON.parse(data);
        if (window.innerWidth < 800) {
            function mobilinitialize() {
                $("#eventlist").empty();
                for (var i = 0; i < event_data.length; i++) {
                    var li = addElement(event_data[i]);
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
        //console.log(event_data);

        for (var i = 0; i < event_data.length; i++) {

            var marker = new google.maps.Marker({
                id: "marker_" + i,
                position: new google.maps.LatLng(event_data[i].longitude, event_data[i].latitude),
                icon: 'res/img/marker_red.png',
                event: event_data[i]
            });

            marker.setMap(map);

            google.maps.event.addListener(marker, 'click', function () {
                if (prev != undefined) {
                    prev.setIcon('res/img/marker_red.png')
                }
                this.setIcon('res/img/marker_blue.png');
                $("#eventlist").empty();
                $("#eventlist").append(addElement(this.event));
                prev = this;
            });
        }
    }

    function parseTime(time) {
        if (time == undefined || time == "")
            return "nicht festgelegt";

        //2016-07-16T17:00:00+0200
        var hour = time.substr(11, 2);
        var min = time.substr(14, 2);
        var sek = time.substr(17, 2);
        var date = hour + ":" + min + ":" + sek;
        return date;
    }

    function parseDay(time) {
        if (time == undefined || time == "")
            return "nicht festgelegt";

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
        $("#city").geocomplete({details: "form"});
        if (getUrlParameter("lng") != undefined) {
            $("#lng").val(getUrlParameter("lng"))
        }
        if (getUrlParameter("lat") != undefined) {
            $("#lat").val(getUrlParameter("lat"))
        }
        if (getUrlParameter("radius") != undefined) {
            $("#radius").val(getUrlParameter("radius"))
        }
        if (getUrlParameter("category") != undefined) {
            $("#category").val(decodeURIComponent(getUrlParameter("category").replace(/\+/g, ' ')))
        }
        if (getUrlParameter("city") != undefined) {
            $("#city").val(decodeURIComponent(getUrlParameter("city").replace(/\+/g, ' ')))
        }
    }

    function search() {
        $("#search").submit(function (event) {
            if ($("#lng").val() == "" || $("#lat").val() == "") {
                alert("Bitte Ort aus Liste auswählen")
                return false
            } else {
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

    function addElement(data) {
        var out = "\<li>" +
            "\<div class=\"event-info\"><img src=\"http://placehold.it/100x100\">" +
            "\<div class=\"about-event-li\">" +
            "\<div class=\"event-list-headline\">" + data.name + "\</div>" +
            "\<div class=\"event-kategorie text-muted\">(" + data.category + ")\</div>" +
            "\<div class=\"termin\">" + parseDay(data.begin) + ", " +
            "\<span class=\"event-time\">" + parseTime(data.begin) + " - " + parseTime(data.end) + "\</span></div>" +
            "\</div>" +
            "\<div class=\"description\"><div class=\"event-location\">" + data.street_no + "\<br>" + data.zip_code + " " + data.city + "\<br>" + data.country + "\</div><br>" + "\<strong>Beschreibung</strong>\<p>" + data.description + "\</p><a class=\"website_link\" href=\"" + data.website + "\">Zur Website</a>\</div>" +
            "\<div class=\"material-icons more-button\">keyboard_arrow_down</div>" +
            "\</div>" +
            "\</li>";
        return out;
    }

});
