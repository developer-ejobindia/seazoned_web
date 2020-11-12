$(function () {

    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function (position) {
            loadWeather(position.coords.latitude + ',' + position.coords.longitude);
        });
    } else {
        loadWeather("Kolkata, IN", "");
    }

    setInterval(loadWeather, 10000);
});



function loadWeather(location, woeid) {
    $.simpleWeather({
        location: location,
        woeid: woeid,
        unit: 'c',
        success: function (weather) {
            city = weather.city;
            temp = weather.temp + weather.units.temp;
            wcode = '<img class="weathericon" src="images/weathericons/' + weather.code + '.svg">';
            wind = weather.wind.speed + ' ' + weather.units.speed;
            humidity = weather.humidity + ' %';

            var today = new Date();
            var weekday = new Array(7);
            weekday[0] = "Sunday";
            weekday[1] = "Monday";
            weekday[2] = "Tuesday";
            weekday[3] = "Wednesday";
            weekday[4] = "Thursday";
            weekday[5] = "Friday";
            weekday[6] = "Saturday";


            var months = new Array(12);
            months[0] = "JAN";
            months[1] = "FEB";
            months[2] = "MAR";
            months[3] = "APR";
            months[4] = "MAY";
            months[5] = "JUN";
            months[6] = "JUL";
            months[7] = "AUG";
            months[8] = "SEP";
            months[9] = "OCt";
            months[10] = "NOV";
            months[11] = "DEC";

            $('.current-weather .day').html(weekday[today.getDay()]);
            $('.current-weather .date').html('<h5 class="month">' + months[today.getMonth()] + '<span>' + today.getDate() + '</span></h5>');
            $("#weather_location").html(city);
            $("#weather_code").html(weather.currently);
            $("#temperature").html(temp);
            $("#weather-icon").html(wcode);
            $("#weather_wind").html('<img src="images/wind.png" style="vertical-align: top;"> &nbsp; <span>' + wind + '</span>');
            $("#weather_humidity").html('<img src="images/humidity.png" style="vertical-align: top;"> <span>' + humidity + '</span>');

            var weatherForecast = '';

            for (i = 1; i < 6; i++) {
                weatherForecast = weatherForecast + '<li class="text-center"><p class="day m-b-10">' + weather.forecast[i].day + '</p><div class="weather-icon"><img class="weathericon" src="images/weathericons/' + weather.forecast[i].code + '.svg"></div><h5 class="temperature m-b-0 m-t-10">' + weather.forecast[i].code + '&#x2103;</h5></li>';
            }

            $('#weather_forecast').html(weatherForecast);

            $(".weakly-report-scroll").mCustomScrollbar({
                axis: "x",
                theme: "light-3",
                advanced: {autoExpandHorizontalScroll: true}
            });

        },
        error: function (error) {
            $(".error").html('<p>' + error + '</p>');
        }
    });
}