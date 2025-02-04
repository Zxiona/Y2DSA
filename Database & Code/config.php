<?php
namespace TwinCities;
@date_default_timezone_set("GMT");

define ('INSERT_WEATHER_API_KEY_HERE', '')
define ('INSERT_GOOGLE_MAPS_API_KEY_HERE', '')

define ('DBMS', [
    'HOST' => 'localhost',
    'DB' => 'twin-cities',
    'UN' => 'root',
    'PW' => ''
]);

define ('LONDON', [
    'NAME' => 'LONDON',
    'LATITUDE' =>  ,
    'LONGITUDE' =>  ,
    'POPULATION' =>  ,
    'WEATHERCONDITIONS_URL' => 'url, uk&appid='  . INSERT_WEATHER_API_KEY_HERE 'end of url'
    'WEATHERFORECAST_URL' => 'url, uk&appid='  . INSERT_WEATHER_API_KEY_HERE 'end of url'
    'MARKERS' => "const markers = [
        ['name1', lat1, long1, './icons/name1photoname.png'],
        ['name2', lat2, long2, './icons/name2photoname.png'],
        "
    ])

function ErrorHandler(int $errNo, string $errMsg, string $file, int $line) {
    echo "Twin Cities Application error handler got a #[$errNo] occurred in [$file] at line [$line]: [$errMsg]";
}

set_error_handler('twinCitiesErrorHandler');
?>
