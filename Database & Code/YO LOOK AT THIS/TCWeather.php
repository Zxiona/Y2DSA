<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twin Cities Weather</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/2434cfa3b9.js" crossorigin="anonymous"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Responsive Navigation -->
    <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="text-center mb-4">Twin Cities Weather Forecast</h1>
                <p class="text-center">Current weather and 5-day forecast for our twin cities.</p>
            </div>
        </div>

        <?php
        // Include config file for API key
        include "config.php";

        // Define the cities
        $city1 = "London";
        $city2 = "New York";
        $country1 = "UK";
        $country2 = "US";

        // URL encode city names to handle spaces and special characters
        $encodedCity1 = urlencode($city1);
        $encodedCity2 = urlencode($city2);
        $encodedCountry1 = urlencode($country1);
        $encodedCountry2 = urlencode($country2);

        // Define the API URLs for each city (current weather and 5-day forecast)
        $urlCurrent1 = "http://api.openweathermap.org/data/2.5/weather?q={$encodedCity1},{$encodedCountry1}&appid=" . WeatherAPIKey . "&units=metric";
        $urlCurrent2 = "http://api.openweathermap.org/data/2.5/weather?q={$encodedCity2},{$encodedCountry2}&appid=" . WeatherAPIKey . "&units=metric";
        $urlForecast1 = "http://api.openweathermap.org/data/2.5/forecast?q={$encodedCity1},{$encodedCountry1}&appid=" . WeatherAPIKey . "&units=metric";
        $urlForecast2 = "http://api.openweathermap.org/data/2.5/forecast?q={$encodedCity2},{$encodedCountry2}&appid=" . WeatherAPIKey . "&units=metric";

        // Function to safely fetch JSON data with error handling
        function fetchWeatherData($url)
        {
            $context = stream_context_create([
                'http' => [
                    'ignore_errors' => true,
                    'method' => 'GET',
                    'header' => 'Accept: application/json'
                ]
            ]);

            $response = @file_get_contents($url, false, $context);

            // Check if the request was successful
            if ($response === false) {
                return null;
            }

            // Decode JSON response
            $data = json_decode($response, true);

            // Check if API returned an error
            if (isset($data['cod']) && $data['cod'] != 200) {
                error_log("API Error: " . ($data['message'] ?? 'Unknown error'));
                return null;
            }

            return $data;
        }

        // Fetch current weather data for each city
        $currentWeather1 = fetchWeatherData($urlCurrent1);
        $currentWeather2 = fetchWeatherData($urlCurrent2);

        // Fetch 5-day forecast data for each city
        $forecast1 = fetchWeatherData($urlForecast1);
        $forecast2 = fetchWeatherData($urlForecast2);

        // Function to display current weather in a Bootstrap card
        function displayCurrentWeather($weather, $city)
        {
            // Check if we have valid weather data
            if (!$weather) {
                echo '<div class="col-md-6 mb-4">';
                echo '<h2 class="city-header text-center">' . $city . '</h2>';
                echo '<div class="weather-container bg-white p-4">';
                echo '<div class="text-center mb-4">';
                echo '<h3>Weather Data Unavailable</h3>';
                echo '<p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Unable to retrieve weather data at this time.</p>';
                echo '</div>'; // End text-center
                echo '</div>'; // End weather-container
                echo '</div>'; // End column
                return;
            }

            $temp = round($weather['main']['temp']);
            $feelsLike = round($weather['main']['feels_like']);
            $description = ucfirst($weather['weather'][0]['description']);
            $humidity = $weather['main']['humidity'];
            $windSpeed = $weather['wind']['speed'];
            $iconCode = $weather['weather'][0]['icon'];
            $iconUrl = "http://openweathermap.org/img/wn/{$iconCode}@2x.png";

            echo '<div class="col-md-6 mb-4">';
            echo '<h2 class="city-header text-center">' . $city . '</h2>';
            echo '<div class="weather-container bg-white p-4">';

            // Current Weather Card
            echo '<div class="text-center mb-4">';
            echo '<h3>Current Weather</h3>';
            echo '<p class="text-muted">' . date('D, M j, Y g:i A') . '</p>';
            echo '<div class="d-flex justify-content-center align-items-center">';
            echo '<img src="' . $iconUrl . '" alt="' . $description . '" class="weather-icon">';
            echo '<span class="current-temp">' . $temp . '°C</span>';
            echo '</div>';
            echo '<p class="mt-2">' . $description . '</p>';

            // Weather Details
            echo '<div class="row mt-3 weather-details">';
            echo '<div class="col-6 text-center">';
            echo '<p><i class="fas fa-thermometer-half me-2"></i> Feels like: ' . $feelsLike . '°C</p>';
            echo '</div>';
            echo '<div class="col-6 text-center">';
            echo '<p><i class="fas fa-tint me-2"></i> Humidity: ' . $humidity . '%</p>';
            echo '</div>';
            echo '<div class="col-6 text-center">';
            echo '<p><i class="fas fa-wind me-2"></i> Wind: ' . $windSpeed . ' m/s</p>';
            echo '</div>';
            echo '<div class="col-6 text-center">';
            echo '<p><i class="fas fa-compass me-2"></i> Direction: ' . getWindDirection($weather['wind']['deg']) . '</p>';
            echo '</div>';
            echo '</div>'; // End weather details

            echo '</div>'; // End current weather card
            echo '</div>'; // End weather container
            echo '</div>'; // End column
        }

        // Function to display a 5-day weather forecast
        function displayForecast($forecast, $city)
        {
            // Check if we have valid forecast data
            if (!$forecast) {
                echo '<div class="col-md-6 mb-4">';
                echo '<h2 class="city-header text-center">' . $city . ' 5-Day Forecast</h2>';
                echo '<div class="weather-container bg-white p-4">';
                echo '<div class="text-center mb-4">';
                echo '<h3>Forecast Data Unavailable</h3>';
                echo '<p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i> Unable to retrieve forecast data at this time.</p>';
                echo '</div>'; // End text-center
                echo '</div>'; // End weather-container
                echo '</div>'; // End column
                return;
            }

            echo '<div class="col-md-6 mb-4">';
            echo '<h2 class="city-header text-center">' . $city . ' 5-Day Forecast</h2>';
            echo '<div class="weather-container bg-white p-4">';

            // Process forecast data to get one entry per day
            $dailyForecasts = [];
            $currentDate = null;

            foreach ($forecast['list'] as $timeSlot) {
                $date = date('Y-m-d', $timeSlot['dt']);
                $hour = date('H', $timeSlot['dt']);

                // Get the noon forecast for each day
                if ($hour >= 11 && $hour <= 13) {
                    if (!isset($dailyForecasts[$date])) {
                        $dailyForecasts[$date] = $timeSlot;
                    }
                }
            }

            // Display forecasts
            echo '<div class="row">';
            $count = 0;
            foreach ($dailyForecasts as $date => $dayForecast) {
                if ($count >= 5) break; // Limit to 5 days

                $temp = round($dayForecast['main']['temp']);
                $description = ucfirst($dayForecast['weather'][0]['description']);
                $iconCode = $dayForecast['weather'][0]['icon'];
                $iconUrl = "http://openweathermap.org/img/wn/{$iconCode}.png";
                $dayName = date('D', $dayForecast['dt']);
                $formattedDate = date('M j', $dayForecast['dt']);

                echo '<div class="col">';
                echo '<div class="forecast-day text-center p-2 mt-2">';
                echo '<p class="mb-1 fw-bold">' . $dayName . '</p>';
                echo '<p class="small text-muted mb-2">' . $formattedDate . '</p>';
                echo '<img src="' . $iconUrl . '" alt="' . $description . '" class="mb-2" style="width: 50px; height: 50px;">';
                echo '<p class="mb-1 fw-bold">' . $temp . '°C</p>';
                echo '<p class="small mb-1">' . $description . '</p>';
                echo '</div>';
                echo '</div>';

                $count++;
            }
            echo '</div>'; // End row

            echo '</div>'; // End weather container
            echo '</div>'; // End column
        }

        // Helper function to convert wind degrees to direction
        function getWindDirection($degrees)
        {
            $directions = ["N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW", "N"];
            return $directions[round($degrees / 22.5)];
        }

        // Display current weather for both cities
        echo '<div class="row">';
        displayCurrentWeather($currentWeather1, $city1);
        displayCurrentWeather($currentWeather2, $city2);
        echo '</div>';

        // Display 5-day forecast for both cities
        echo '<div class="row">';
        displayForecast($forecast1, $city1);
        displayForecast($forecast2, $city2);
        echo '</div>';
        ?>
    </div>

    <!-- Weather comparison card - only show if both city data is available -->
    <?php if ($currentWeather1 && $currentWeather2): ?>
        <div class="container pb-5">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0">Weather Comparison</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Metric</th>
                                            <th><?php echo $city1; ?></th>
                                            <th><?php echo $city2; ?></th>
                                            <th>Difference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Temperature</td>
                                            <td><?php echo round($currentWeather1['main']['temp']); ?>°C</td>
                                            <td><?php echo round($currentWeather2['main']['temp']); ?>°C</td>
                                            <td><?php echo round(abs($currentWeather1['main']['temp'] - $currentWeather2['main']['temp'])); ?>°C</td>
                                        </tr>
                                        <tr>
                                            <td>Humidity</td>
                                            <td><?php echo $currentWeather1['main']['humidity']; ?>%</td>
                                            <td><?php echo $currentWeather2['main']['humidity']; ?>%</td>
                                            <td><?php echo abs($currentWeather1['main']['humidity'] - $currentWeather2['main']['humidity']); ?>%</td>
                                        </tr>
                                        <tr>
                                            <td>Wind Speed</td>
                                            <td><?php echo $currentWeather1['wind']['speed']; ?> m/s</td>
                                            <td><?php echo $currentWeather2['wind']['speed']; ?> m/s</td>
                                            <td><?php echo round(abs($currentWeather1['wind']['speed'] - $currentWeather2['wind']['speed']), 1); ?> m/s</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Twin Cities Project</h5>
                    <p class="small">A web application showcasing twin cities and their points of interest.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small">Weather data provided by <a href="https://openweathermap.org/" class="text-white">OpenWeatherMap</a></p>
                    <p class="small">© <?php echo date('Y'); ?> Twin Cities Project</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>