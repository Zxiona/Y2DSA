<?php
define('WeatherAPIKey', '');
define('MapAPIKey', '');

$servername = "localhost"; // Change if using a remote server
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$dbname = "twin_city";     // Database name

// Create connection to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select the database
$conn->select_db($dbname);

// Coordinates for London
$londonLat = 51.5074;
$londonLng = -0.1278;

// Coordinates for New York
$NYLat = 40.7128;
$NYLng = -74.0060;

$ldnpoi = "SELECT Place_ID, City_ID, Type_ID, Borough_ID, Name, Address, Latitude, Longitude, Description, Photos, Opening_time, Ending_time
            FROM Place_of_Interest
            WHERE City_ID = 1";
$ldnpois = $conn->query($ldnpoi);

$nypoi = "SELECT Place_ID, City_ID, Type_ID, Borough_ID, Name, Address, Latitude, Longitude, Description, Photos, Opening_time, Ending_time
            FROM Place_of_Interest
            WHERE City_ID = 2";
$nypois = $conn->query($nypoi);

// Convert London POIs to a PHP array
$ldnpoiData = [];
if ($ldnpois->num_rows > 0) {
    while ($ldnrow = $ldnpois->fetch_assoc()) {
        $ldnpoiData[] = $ldnrow;
    }
}

// Convert New York POIs to a PHP array
$nypoiData = [];
if ($nypois->num_rows > 0) {
    while ($row = $nypois->fetch_assoc()) {
        $nypoiData[] = $row;
    }
}

// Close the connection
$conn->close();
