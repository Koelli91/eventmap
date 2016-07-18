$(function () {
    $("#city").geocomplete({details: "form"});

    $("#search").submit(function (event) {
        if ($("#lng").val() == "" || $("#lat").val() == "") {
            alert("Bitte Ort aus Liste auswählen");
            return false;
        }
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, handleError);
    }
    
    function showPosition(position) {
        $('.waiting-container').removeClass('waiting');
        $('input[name=city]').val(getCityFromGeo(position.coords.latitude, position.coords.longitude));
        $('input[name=lat]').val(position.coords.latitude);
        $('input[name=lng]').val(position.coords.longitude);
    }

    function handleError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
            case error.POSITION_UNAVAILABLE:
            case error.TIMEOUT:
            case error.UNKNOWN_ERROR:
                getLocationByIp();
                break;
        }
    }

    function getCityFromGeo(latitude, longitude) {
        var city = '';
        $.ajax({
            method: 'GET',
            url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + latitude + ',' + longitude,
            dataType: 'json',
            async: false
        }).done(function (data) {
            var addressComponents = data.results[0].address_components;
            for (i = 0; i < addressComponents.length; i++) {
                var types = addressComponents[i].types;
                if (types == 'locality,political') {
                    city = addressComponents[i].long_name;
                }
            }
        });
        return city;
    }

    function getLocationByIp() {
        $.get('get_ip_location.php', function (data) {
            $('.waiting-container').removeClass('waiting');
            if (data.city)
                $('input[name=city]').val(data.city);
            if (data.latitude)
                $('input[name=lat]').val(data.latitude);
            if (data.longitude)
                $('input[name=lng]').val(data.longitude);
        }, 'json');
    }
});