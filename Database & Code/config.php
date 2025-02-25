<?php
namespace TwinCities;
@date_default_timezone_set("GMT");

define ('a67f6401fd6fa2e768d27abc6719b257', '$WeatherAPIKey') #nathans api key replace with your own
define ('AIzaSyA4XaLaHE88hCOIz54_3CY9qRk31x38B7A', '$MapAPIKey') #nathans api key replace with your own

define ('DBMS', [
    'HOST' => 'localhost',
    'DB' => 'twin-cities',
    'UN' => 'root',
    'PW' => '' #switch password if needed (I DO!!!!!!)
]);

define ('LONDON', [
    'NAME' => 'LONDON',
    'LATITUDE' =>  51.5074,
    'LONGITUDE' =>  -0.1278,
    'POPULATION' =>  ,
    'WEATHERCONDITIONS_URL' => 'url, uk&appid='  . INSERT_WEATHER_API_KEY_HERE 'end of url'
    'WEATHERFORECAST_URL' => 'url, uk&appid='  . INSERT_WEATHER_API_KEY_HERE 'end of url'
    'MARKERS' => "const markers = [
        ['Scully', 51.50873, -0.13301, './icons/name1photoname.png'],
        ['Charing Cross', 51.50825, -0.12475, './icons/name2photoname.png'],
        ['University Of Notre Dame', 
        "
    ])

function ErrorHandler(int $errNo, string $errMsg, string $file, int $line) {
    echo "Twin Cities Application error handler got a #[$errNo] occurred in [$file] at line [$line]: [$errMsg]";
}

define ('NEWYORK', [
    'NAME' => 'NEWYORK',
    'LATITUDE' =>  40.7128,
    'LONGITUDE' =>  -74.0060,
    'POPULATION' =>  ,
    'WEATHERCONDITIONS_URL' => 'url, uk&appid='  . INSERT_WEATHER_API_KEY_HERE 'end of url'
    'WEATHERFORECAST_URL' => 'url, uk&appid='  . INSERT_WEATHER_API_KEY_HERE 'end of url'
    'MARKERS' => "const markers = [
        ['name1', lat1, long1, './icons/name1photoname.png'],
        ['name2', lat2, long2, './icons/name2photoname.png'],
        "
    ])

set_error_handler('twinCitiesErrorHandler');
?>
