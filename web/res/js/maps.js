var currentPage = 1,
    pages = 1,
    event_data,
    event_count,
    events_per_page,
    map,
    activeMarker;

$(function () {

    $(".navbar-toggle").on("click", function () {
        $(this).toggleClass("active");
    });

    initSearchForm();

    // Auf Änderung des Hash-Werts zu "page" in der URL reagieren
    window.addEventListener('hashchange', function() {
        if (getUrlHashParameter("page") != undefined) {
            var page = parseInt(getUrlHashParameter("page"));
            if (currentPage !== page) {
                currentPage = page;
                
                var url = "../api/v1/events?lat=" + getUrlParameter("lat") + "&lon=" + getUrlParameter("lng") + "&radius=" + getUrlParameter("radius") + "&category=" + getUrlParameter("category") + "&page=" + currentPage;

                $('#eventlist-container .loading-container').addClass('loading');
                // Set a small timeout so the page doesn't "blink" the loading animation
                window.setTimeout(function () {
                    $.ajax({
                        "method": "GET",
                        "url": url,
                        "returnType": "JSON"
                    }).done(function (data) {
                        data = JSON.parse(data);
                        event_data = data.eventlist;
                        event_count = data.eventCount;
                        events_per_page = data.eventsPerPage;

                        // Alle gefundenen Events zur Eventlist hinzufügen
                        addEventsToEventlist();
                        
                        $('#eventlist-container .loading-container').removeClass('loading');
                    });
                }, 200);

                refreshPagination();
            }
        }
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
        
        // Get ALL events (not only current page)
        var url = "../api/v1/events?lat=" + getUrlParameter("lat") + "&lon=" + getUrlParameter("lng") + "&radius=" + getUrlParameter("radius") + "&category=" + getUrlParameter("category") + "&page=-1";
        var all_event_data;
        
        $.ajax({
            "method": "GET",
            "url": url,
            "returnType": "JSON"
        }).done(function (data) {
            data = JSON.parse(data);
            all_event_data = data.eventlist;
            
            for (var i = 0; i < all_event_data.length; i++) {
                var marker = new google.maps.Marker({
                    id: "marker_" + i,
                    position: new google.maps.LatLng(all_event_data[i].longitude, all_event_data[i].latitude),
                    icon: 'res/img/marker_red.png',
                    event: all_event_data[i]
                });

                marker.setMap(map);

                google.maps.event.addListener(marker, 'click', function () {
                    if (activeMarker == undefined) {
                        activeMarker = this;
                        this.setIcon('res/img/marker_blue.png');
                        var $eventlist = $("#eventlist");
                        emptyEventList();
                        $eventlist.append(addElement(this.event));
                        $('#eventlist-container h2').remove();
                    } else {
                        disableActiveMarker();
                    }
                });
                
                google.maps.event.addListener(map, 'click', function() {
                    if (activeMarker != undefined) {
                        disableActiveMarker();
                    }
                });
            }
        });
    }
    
    function disableActiveMarker() {
        activeMarker.setIcon('res/img/marker_red.png')
        activeMarker = undefined;
        addEventsToEventlist();
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
        $.get("../api/v1/events", search_data, function (data) {
            emptyEventList();
            for (var i = 0; i < data.length; i++) {
                addElement(data, i)
            }
        }, 'json');
    }

    function initSearchForm() {
        $.get('../api/v1/category', function (data) {
            // Get categories from api/database
            var categories = data.categories;

            if (categories) {
                for (var i = 0; i < categories.length; i++) {
                    $('select#category').append(`
                        <option>${categories[i].Name}</option>
                    `);
                }
            }

            // Fill the form fields
            var $city = $("#city");
            $city.geocomplete({details: "form"});
            if (getUrlParameter("lng") != undefined) {
                $("#lng").val(decodeURIComponent(getUrlParameter("lng")))
            }
            if (getUrlParameter("lat") != undefined) {
                $("#lat").val(decodeURIComponent(getUrlParameter("lat")))
            }
            if (getUrlParameter("radius") != undefined) {
                $("#radius").val(decodeURIComponent(getUrlParameter("radius")))
            }
            if (getUrlParameter("category") != undefined) {
                $("#category").val(decodeURIComponent(getUrlParameter("category").replace(/\+/g, ' ')))
            }
            if (getUrlParameter("city") != undefined) {
                $city.val(decodeURIComponent(getUrlParameter("city").replace(/\+/g, ' ')))
            }

            // let the initialization continue
            initSearchFormContinue();
        }, 'json');
    }

    function initSearchFormContinue() {
        var search_data = {
            "radius": $('#radius').val(),
            "lat": $('#lat').val(),
            "lng": $('#lng').val(),
            "category": $('#category').val()
        };

        if (getUrlHashParameter("page") != undefined) {
            currentPage = parseInt(getUrlHashParameter("page"));
        }
        refreshPagination();

        var url = "../api/v1/events?lat=" + search_data["lat"] + "&lon=" + search_data["lng"] + "&radius=" + search_data["radius"] + "&category=" + encodeURIComponent(search_data["category"]).replace(/\%20/g, '+') + "&page=" + currentPage;

        $.ajax({
            "method": "GET",
            "url": url,
            "returnType": "JSON",
            "cache": false
        }).done(function (data) {
            data = JSON.parse(data);
            event_data = data.eventlist;
            event_count = data.eventCount;
            events_per_page = data.eventsPerPage;
            
            // Alle gefundenen Events zur Eventlist hinzufügen
            addEventsToEventlist();
            
            // Google Map initialisieren
            google.maps.event.addDomListener(window, 'load', initialize());

            if (event_data.length > 0) {
                var ist_sind = event_count > 1 ? "sind" : "ist",
                    veranstaltung_en = event_count > 1 ? "Veranstaltungen" : "Veranstaltung";
                $('#eventlist-container .help-block').prepend('<div class="bg-info text-info">Es ' + ist_sind + ' <strong>' + event_count + ' ' + veranstaltung_en + '</strong> in deiner Nähe verfügbar.</div>');
            } else
                $('#eventlist-container .help-block').prepend('<div class="bg-danger text-danger">Es sind leider keiner Veranstaltungen in deiner Nähe verfügbar. Versuche den Radius oder den Suchort zu ändern.</div>');
        });
    }
    
    function addEventsToEventlist() {
        var $eventlist = $("#eventlist");
        emptyEventList();
        for (var i = 0; i < event_data.length; i++) {
            var li = addElement(event_data[i]);
            $eventlist.append(li);
        }

        var baseUrl = "map.html?lat=" + getUrlParameter("lat") + "&lng=" + getUrlParameter("lng") + "&city=" + getUrlParameter("city") + "&category=" + getUrlParameter("category") + "&radius=" + getUrlParameter("radius");
        pages = parseInt(Math.ceil(event_count / events_per_page));
        pages = !pages ? 1 : pages; // Fall "NaN" abfangen

        if (event_count > 0) {
            // Pagination Start
            var pagination_common = `
                <ul class="pagination">`;
            // "Previous"-Link
            if (currentPage == 1) pagination_common += `
                    <li class="disabled">
                        <span>
                            <span aria-hidden="true">&laquo;</span>
                        </span>
                    </li>`;
            else pagination_common += `
                    <li>
                        <a href="${baseUrl + "#!/page=" + (currentPage - 1)}" aria-label="Vorherige">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>`;
            // Pages Links
            for (var i = 1; i <= pages; i++) {
                if (currentPage == i) pagination_common += `
                <li class="active">
                    <span>${i}</span>
                </li>
        `;
                else pagination_common += `
                    <li>
                        <a href="${baseUrl + "#!/page=" + i}">${i}</a>
                    </li>
        `;
            }
            // "Next"-Link
            if (currentPage == pages) pagination_common += `
                    <li class="disabled">
                        <span>
                            <span aria-hidden="true">&raquo;</span>
                        </span>
                    </li>`;
            else pagination_common += `
                    <li>
                        <a href="${baseUrl + "#!/page=" + (currentPage + 1)}" aria-label="Nächste">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>`;
            // Pagination End
            pagination_common += `
                </ul>
            </nav>`;
            
            var pagination_top = `
            <nav class="text-center" id="event-pagination-top">` + pagination_common;
            var pagination_bottom = `
            <nav class="text-center" id="event-pagination-bottom">` + pagination_common;
            
            $('#eventlist-container #eventlist-wrapper #eventlist').before(pagination_top);
            $('#eventlist-container #eventlist-wrapper #eventlist').after(pagination_bottom);
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
        });
    }

    function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
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
					    ${data.location_name !== "" ? "<strong>" + data.location_name + "</strong><br>" : ""}
						${data.street_no}<br>
						${data.zip_code} ${data.city}<br>
						${data.country}
					</div>
					<br>
					<strong>Beschreibung</strong>
					<p>${data.description == "" ? "keine Beschreibung verfügbar" : data.description}</p>
					<a class="website_link" href="${data.website}" target="_blank">Zur Website</a>
				</div>
				<div class="material-icons more-button">keyboard_arrow_down</div>
            </div>
		</li>`;
    }
    
    function emptyEventList() {
        $('#eventlist-wrapper #event-pagination-top').remove();
        $('#eventlist-wrapper #event-pagination-bottom').remove();
        $('#eventlist-wrapper #eventlist').empty();
    }

});
