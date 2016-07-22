$(function () {

    $(".navbar-toggle").on("click", function () {
        $(this).toggleClass("active");
    });

    initSearchForm();

    var search_data = {
        "radius": $('#radius').val(),
        "lat": $('#lat').val(),
        "lng": $('#lng').val(),
        "category": $('#category').val()
    };

    var currentPage = 1;
    var pages = 1;
    if (getUrlHashParameter("page") != undefined) {
        currentPage = parseInt(getUrlHashParameter("page"));
    }
    refreshPagination();

    var url = "../api/v1/events?lat=" + search_data["lat"] + "&lon=" + search_data["lng"] + "&radius=" + search_data["radius"] + "&category=" + search_data["category"];
    url += window.innerWidth < 768 ? "&page=" + currentPage : "&page=-1";

    var event_data;

    $.ajax({
        "method": "GET",
        "url": url,
        "returnType": "JSON",
        "cache": false
    }).done(function (data) {
        data = JSON.parse(data);
        event_data = data.eventlist;
        var event_count = data.eventCount;
        if (window.innerWidth < 768) {
            // Alle gefundenen Events zur Eventlist hinzufügen
            var $eventlist = $("#eventlist");
            $eventlist.empty();
            for (var i = 0; i < event_data.length; i++) {
                var li = addElement(event_data[i]);
                $eventlist.append(li);
            }

            var baseUrl = "map.html?lat=" + getUrlParameter("lat") + "&lng=" + getUrlParameter("lng") + "&city=" + getUrlParameter("city") + "&category=" + getUrlParameter("category") + "&radius=" + getUrlParameter("radius");
            pages = parseInt(Math.ceil(event_count / event_data.length));
            pages = !pages ? 1 : pages; // Fall "NaN" abfangen

            if (event_count > 0) {
                // Pagination Start
                var pagination = `
                    <nav class="text-center" id="event-pagination">
                        <ul class="pagination">`;
                // "Previous"-Link
                if (currentPage == 1) pagination += `
                            <li class="disabled">
                                <span>
                                    <span aria-hidden="true">&laquo;</span>
                                </span>
                            </li>`;
                else pagination += `
                            <li>
                                <a href="${baseUrl + "#!/page=" + (currentPage - 1)}" aria-label="Vorherige">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>`;
                // Pages Links
                for (var i = 1; i <= pages; i++) {
                    if (currentPage == i) pagination += `
                        <li class="active">
                            <span>${i}</span>
                        </li>
                `;
                    else pagination += `
                            <li>
                                <a href="${baseUrl + "#!/page=" + i}">${i}</a>
                            </li>
                `;
                }
                // "Next"-Link
                if (currentPage == pages) pagination += `
                            <li class="disabled">
                                <span>
                                    <span aria-hidden="true">&raquo;</span>
                                </span>
                            </li>`;
                else pagination += `
                            <li>
                                <a href="${baseUrl + "#!/page=" + (currentPage + 1)}" aria-label="Nächste">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>`;
                // Pagination End
                pagination += `
                        </ul>
                    </nav>  
                `;

                $('#eventlist-container #eventlist').before(pagination);
                $('#eventlist-container #eventlist').after(pagination);
            }

        } else {
            $('#eventlist-container').append(`<h2 style="padding: 0 20px;">Wähle ein Event auf der Karte aus!</h2>`);
            google.maps.event.addDomListener(window, 'load', initialize());
        }

        if (event_data.length > 0) {
            var ist_sind = event_count > 1 ? "sind" : "ist",
                veranstaltung_en = event_count > 1 ? "Veranstaltungen" : "Veranstaltung";
            $('#eventlist-container .help-block').prepend('<div class="bg-info text-info">Es ' + ist_sind + ' <strong>' + event_count + ' ' + veranstaltung_en + '</strong> in deiner Nähe verfügbar.</div>');
        } else
            $('#eventlist-container .help-block').prepend('<div class="bg-danger text-danger">Es sind leider keiner Veranstaltungen in deiner Nähe verfügbar. Versuche den Radius oder den Suchort zu ändern.</div>');
    });

    $('body').on('click', '#event-pagination a', function (event) {
        // Das Folgen des Links verhindern
        //event.preventDefault();

        window.setTimeout(function () {
            currentPage = parseInt(getUrlHashParameter("page"));
            var url = "../api/v1/events?lat=" + getUrlParameter("lat") + "&lon=" + getUrlParameter("lng") + "&radius=" + getUrlParameter("radius") + "&category=" + getUrlParameter("category") + "&page=" + currentPage;
            var $eventlist = $("#eventlist");

            $eventlist.fadeOut(400, function () {
                $('#eventlist-container .loading-container').addClass('loading');
                // Set a small timeout so the page doesn't "blink" the loading animation
                window.setTimeout(function() {
                    $.ajax({
                        "method": "GET",
                        "url": url,
                        "returnType": "JSON"
                    }).done(function (data) {
                        $('#eventlist-container .loading-container').removeClass('loading');

                        data = JSON.parse(data);
                        event_data = data.eventlist;

                        // Alle gefundenen Events zur Eventlist hinzufügen
                        $eventlist.empty();
                        for (var i = 0; i < event_data.length; i++) {
                            var li = addElement(event_data[i]);
                            $eventlist.append(li);
                        }
                        $eventlist.fadeIn();
                    });
                }, 200);
            });

            refreshPagination();
        }, 0);
    });

    function refreshPagination() {
        var baseUrl = "map.html?lat=" + getUrlParameter("lat") + "&lng=" + getUrlParameter("lng") + "&city=" + getUrlParameter("city") + "&category=" + getUrlParameter("category") + "&radius=" + getUrlParameter("radius");

        // Make old "active" page no more active and clickable
        var $pagination = $('#eventlist-container .pagination');
        var $oldActive = $pagination.find('.active').removeClass('active');
        var oldPageNo = $oldActive.find('span').html();
        var url = baseUrl + "#!/page=" + oldPageNo;
        $oldActive.find('span').replaceWith('<a href="' + url + '">' + oldPageNo + '</a>');

        // Make current page "active" and no more clickable
        $pagination.find('li a').each(function () {
            var pageNo = $(this).html();
            if (pageNo == currentPage) {
                $(this).parent('li').addClass('active');
                $(this).replaceWith('<span>' + pageNo + '</span>');
            }
        });

        // Refresh the "Previous"-Link
        var $previous = $pagination.find('li:first');
        var url = baseUrl + "#!/page=" + (currentPage - 1);
        if ($previous.find('a').length == 0) {
            // "Previous"-Link wasn't clickable, now it will be
            $previous.removeClass('disabled');
            var $span = $previous.find('span').first(),
                innerHtml = $span.html();
            $span.replaceWith('<a href="' + url + '">' + innerHtml + '</a>');
        } else {
            // "Previous"-Link was clickable before, check if it should be any longer
            if (currentPage == 1) {
                // Nope, make it non-clickable
                $previous.addClass('disabled');
                var innerHtml = $previous.find('a').html();
                $previous.find('a').replaceWith('<span>' + innerHtml + '</span>');
            } else {
                // Let him be, let him be.. (but refresh it's link!!)
                $previous.find('a').prop('href', url);
            }
        }

        // Refresh the "Next"-Link
        var $next = $pagination.find('li:last');
        var url = baseUrl + "#!/page=" + (currentPage + 1);
        if ($next.find('a').length == 0) {
            // "Next"-Link wasn't clickable, now it will be
            $next.removeClass('disabled');
            var $span = $next.find('span').first(),
                innerHtml = $span.html();
            $span.replaceWith('<a href="' + url + '">' + innerHtml + '</a>');
        } else {
            // "Next"-Link was clickable before, check if it should be any longer
            if (currentPage == pages) {
                // Nope, make it non-clickable
                $next.addClass('disabled');
                var innerHtml = $next.find('a').html();
                $next.find('a').replaceWith('<span>' + innerHtml + '</span>');
            } else {
                // Let him be, let him be.. (but refresh it's link!!)
                $next.find('a').prop('href', url);
            }
        }
    }

    var map;

    var prev;

    function initialize() {
        var latlng = new google.maps.LatLng(parseFloat($('#lat').val()), parseFloat($('#lng').val()));
        var zoomLevel = 11;
        var radius = parseInt($('#radius').val());
        if (radius >= 200)
            zoomLevel -= 3;
        else if (radius >= 100)
            zoomLevel -= 2;
        else if (radius >= 50)
            zoomLevel -= 1;
        var mapOptions = {
            zoom: zoomLevel,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

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
                var $eventlist = $("#eventlist");
                $eventlist.empty();
                $eventlist.append(addElement(this.event));
                prev = this;
            });
        }
    }

    function parseTime(time) {
        if (time == undefined || time == "")
            return 0;

        //2016-07-16T17:00:00+0200
        var hour = time.substr(11, 2);
        var min = time.substr(14, 2);
        if (hour == 0 && min == 0)
            return 0;
        //var sek = time.substr(17, 2);
        //return hour + ":" + min + ":" + sek;
        return hour + ":" + min;
    }

    function parseDay(time) {
        if (time == undefined || time == "")
            return 0;

        var year = time.substr(0, 4);
        var month = time.substr(5, 2);
        var day = time.substr(8, 2);
        return day + "." + month + "." + year;
    }

    function parseTimeAndDate(time) {
        var _date = parseDay(time);
        var _time = parseTime(time);

        if (_date === 0 && _time === 0)
            return "";
        else if (_time === 0)
            return _date;
        else
            return _date + ", " + _time;
    }

    function loadEvents() {
        var search_data = {
            "radius": $('#radius').val(),
            "lat": $('#lat').val(),
            "lng": $('#lng').val(),
            "category": $('#category').val(),
        };
        $.get("api/v1/events", search_data, function (data) {
            emptyEventList();
            for (var i = 0; i < data.length; i++) {
                addElement(data, i)
            }
        })
    }

    function initSearchForm() {
        var $city = $("#city");
        $city.geocomplete({details: "form"});
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
            $city.val(decodeURIComponent(getUrlParameter("city").replace(/\+/g, ' ')))
        }
    }

    function search() {
        $("#search").submit(function () {
            if ($("#lng").val() == "" || $("#lat").val() == "") {
                alert("Bitte Ort aus Liste auswählen");
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
    }

    function getUrlHashParameter(sParam) {
        var fullHash = decodeURIComponent(window.location.hash.substr(1)),
            patt = new RegExp(sParam + "=[0-9]+", "i"),
            matches = fullHash.match(patt),
            value;
        if (matches !== null)
            value = matches[0].split("=")[1];
        return value;
    }

    function addElement(data) {
        return `
		<li>
            <div class="event-info">
				<img src="//placehold.it/100x100">
				<div class="about-event-li">
					<div class="event-list-headline">${data.name}</div>
					<div class="event-kategorie text-muted">(${data.category})</div>
					<div class="termin">
						<span class="event-time">
							${parseTimeAndDate(data.begin)}
							${(parseTimeAndDate(data.end) !== "") ? "-" : ""}
							${parseTimeAndDate(data.end)}
						</span>
					</div>
				</div>
				<div class="description">
					<div class="event-location">
						${data.street_no}<br>
						${data.zip_code} ${data.city}<br>
						${data.country}
					</div>
					<br>
					<strong>Beschreibung</strong>
					<p>${data.description}</p>
					<a class="website_link" href="${data.website}">Zur Website</a>
				</div>
				<div class="material-icons more-button">keyboard_arrow_down</div>
            </div>
		</li>`;
    }

});
