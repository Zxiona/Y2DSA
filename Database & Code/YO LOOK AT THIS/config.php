<?php
define('WeatherAPIKey', 'a67f6401fd6fa2e768d27abc6719b257');
define('MapAPIKey', 'AIzaSyB2ur87DvIcq2ChL047ZHi8OsUlmuSser4');

$servername = "localhost"; // Change if using a remote server
$username = "root";        // Your MySQL username
$password = "";            // Your MySQL password
$dbname = "twin_city";     // Database name

// Create connection to MySQL server
$conn = new mysqli($servername, $username, $password);

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

// Converts London POIs to a PHP array
$ldnpoiData = [];
if ($ldnpois->num_rows > 0) {
    while ($ldnrow = $ldnpois->fetch_assoc()) {
        $ldnpoiData[] = $ldnrow;
    }
}

// Converts New York POIs to a PHP array
$nypoiData = [];
if ($nypois->num_rows > 0) {
    while ($row = $nypois->fetch_assoc()) {
        $nypoiData[] = $row;
    }
}

// Close the connection
$conn->close();

// Error handling function
function ErrorHandler($errno, $errstr, $errfile, $errline)
{
    $errorMessage = "[" . date("Y-m-d H:i:s") . "] Error [$errno]: $errstr in $errfile on line $errline\n";

    // Log error to a file
    error_log($errorMessage, 3, "error_log.txt");

    // Display user-friendly message for critical errors
    if ($errno == E_USER_ERROR) {
        die("<b>Critical Error:</b> A major error occurred. Please try again later.");
    } else {
        echo "<b>Warning:</b> A minor issue occurred. System is still operational.";
    }
}

// Set the error handler
set_error_handler("ErrorHandler");

// Database Connection with Error Handling
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    trigger_error("Database connection failed: " . $conn->connect_error, E_USER_ERROR);
}

// Select Database with Error Handling
if (!$conn->select_db($dbname)) {
    trigger_error("Database selection failed: " . $conn->error, E_USER_ERROR);
}

// Query Execution with Error Handling for London
$ldnpoi = "SELECT * FROM Place_of_Interest WHERE City_ID = 1";
$ldnpois = $conn->query($ldnpoi);
if (!$ldnpois) {
    trigger_error("Query failed: " . $conn->error, E_USER_WARNING);
}

// Query Execution with Error Handling for New York
$nypoi = "SELECT * FROM Place_of_Interest WHERE City_ID = 2";
$nypois = $conn->query($nypoi);
if (!$nypois) {
    trigger_error("Query failed: " . $conn->error, E_USER_WARNING);
}

// Close the connection with error handling
if (!$conn->close()) {
    trigger_error("Database connection closing failed: " . $conn->error, E_USER_WARNING);
}
