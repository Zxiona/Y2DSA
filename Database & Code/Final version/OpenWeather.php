<?php
// Replace with your OpenWeatherMap API key
$apiKey = "a67f6401fd6fa2e768d27abc6719b257";

// Define the citiesZ
$city1 = "London";
$city2 = "New York";
$country1 ="UK";
$country2 ="US";

// Define the API URLs for each city (current weather and 5-day forecast)
$urlCurrent1 = "http://api.openweathermap.org/data/2.5/weather?q={$city1},{$country1}&appid={$apiKey}&mode=xml&units=metric";
$urlCurrent2 = "http://api.openweathermap.org/data/2.5/weather?q={$city2},{$country2}&appid={$apiKey}&mode=xml&units=metric";
$urlForecast1 = "http://api.openweathermap.org/data/2.5/forecast?q={$city1},{$country1}&appid={$apiKey}&mode=xml&units=metric";
$urlForecast2 = "http://api.openweathermap.org/data/2.5/forecast?q={$city2},{$country2}&appid={$apiKey}&mode=xml&units=metric";

// Fetch current weather data for each city
$currentWeather1 = simplexml_load_file($urlCurrent1);
$currentWeather2 = simplexml_load_file($urlCurrent2);

// Fetch 5-day forecast data for each city
$forecast1 = simplexml_load_file($urlForecast1);
$forecast2 = simplexml_load_file($urlForecast2);

// Function to display current weather data
function displayCurrentWeather($weather, $city) {
    $iconCode = $weather->weather['icon']; 
    $iconUrl = "http://openweathermap.org/img/wn/{$iconCode}@2x.png";
    echo "<div style='border:1px solid #ddd; padding: 10px; margin: 10px;'>";
    echo "<h2>Current Weather in $city on " . date('D dS F Y @ H:i:s') . "</h2>";
    echo "<p>Condition: " . $weather->weather['value'] . "<img src='$iconUrl' alt='Weather icon'>" ."</p>";
    echo "<p>Temperature: " . $weather->temperature['value'] . "°C</p>";
    echo "<p>Wind: " . $weather->wind->speed['value'] . " m/s (" . $weather->wind->speed['name'] . ") from a " . $weather->wind->direction['name'] . " direction</p>";
    echo "<p>Humidity: " . $weather->humidity['value'] . "%</p>";
    echo "<p>Pressure: " . $weather->pressure['value'] . " hPa</p>";
    echo "<p>Sunrise: " . date('G:i:s', strtotime($weather->city->sun['rise'])) . "</p>";
    echo "<p>Sunset: " . date('G:i:s', strtotime($weather->city->sun['set'])) . "</p>";
    echo "</div>";
}

// Function to display a 5-day weather forecast
function displayForecast($forecast, $city) {
    echo "<div style='border:1px solid #ddd; padding: 10px; margin: 10px;'>";
    echo "<h2>5-Day Forecast for $city</h2>";

    $currentDate = null;

    foreach ($forecast->forecast->time as $time) {
        // Convert the forecast time to a readable date
        $forecastTime = strtotime($time['from']);
        $date = date('Y-m-d', $forecastTime);

        // Only display one forecast per day (e.g., the noon forecast)
        $hour = date('H', $forecastTime);
        if ($hour == "12") {
            if ($currentDate !== $date) {
                $currentDate = $date;

                echo "<h3>" . date('D, d M Y', $forecastTime) . "</h3>";
                echo "<p>Condition: " . $time->symbol['name'] . "</p>";
                echo "<p>Temperature: " . $time->temperature['value'] . "°C</p>";
                echo "<p>Wind: " . $time->windSpeed['mps'] . " m/s (" . $time->windSpeed['name'] . ") from a " . $time->windDirection['name'] . " direction</p>";
                echo "<p>Humidity: " . $time->humidity['value'] . "%</p>";
                echo "<p>Pressure: " . $time->pressure['value'] . " hPa</p>";
                echo "<br>";
            }
        }
    }
    echo "</div>";
}

// Display current weather and 5-day forecast for both cities
echo "<div style='display: flex; flex-wrap: wrap;'>";
echo "<div style='flex: 1; min-width: 300px;'>";
displayCurrentWeather($currentWeather1, $city1);
displayForecast($forecast1, $city1);
echo "</div>";
echo "<div style='flex: 1; min-width: 300px;'>";
displayCurrentWeather($currentWeather2, $city2);
displayForecast($forecast2, $city2);
echo "</div>";
echo "</div>";
?>
