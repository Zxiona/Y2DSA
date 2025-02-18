<?php
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

$ldnpoi = "SELECT City_ID, Type_ID, Name, Address, Latitude, Longitude, Description, Photos, Opening_time, Ending_time
            FROM Place_of_Interest
            WHERE City_ID = 1";
$ldnpois = $conn->query($ldnpoi);

$nypoi = "SELECT City_ID, Type_ID, Name, Address, Latitude, Longitude, Description, Photos, Opening_time, Ending_time
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
