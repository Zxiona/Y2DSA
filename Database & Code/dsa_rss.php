<?php
# Prevent output before headers
ob_start();

# Set timezone
@date_default_timezone_set("GMT");

# Open a new MySQL connection using PDO
$pdo = new PDO('mysql:host=localhost;dbname=twin_city', 'root', '!Rr201612066', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

# Fetch Cities
$sql_cities = 'SELECT `City_ID`, `Name`, `Country`, `Population`, `Size`, `Timezone`, `Language`, `Elevation` FROM `city`';
$query_cities = $pdo->prepare($sql_cities);
$query_cities->execute();
$cities = $query_cities->fetchAll();

# Fetch Places of Interest
$sql_poi = 'SELECT poi.`Place_ID`, poi.`Name` AS Place_Name, poi.`Address`, poi.`Description`, poi.`Photos`, poi.`Opening_Time`, poi.`Ending_Time`, 
        c.`Name` AS City_Name, c.`Country`
        FROM `place_of_interest` poi
        JOIN `city` c ON c.`City_ID` = poi.`City_ID`';
$query_poi = $pdo->prepare($sql_poi);
$query_poi->execute();
$places = $query_poi->fetchAll();

# Set header for XML output
header('Content-Type: text/xml');

# Create a new XMLWriter object
$writer = new XMLWriter();
$writer->openURI('php://output');
$writer->startDocument('1.0', 'UTF-8');
$writer->setIndent(4);

# Declare it as an RSS document
$writer->startElement('rss');
$writer->writeAttribute('version', '2.0');
$writer->writeAttribute('xmlns:atom', 'http://www.w3.org/2005/Atom');

$writer->startElement("channel");

# RSS feed metadata
$writer->writeElement('title', 'Cities and Places of Interest');
$writer->writeElement('description', 'A list of cities and their places of interest.');
$writer->writeElement('link', 'http://localhost/cities_places');

# RSS Image
$writer->startElement('image');
$writer->writeElement('title', 'Cities and Places');
$writer->writeElement('link', 'http://localhost/cities_places');
$writer->writeElement('url', 'http://localhost/images/logo.png'); # Modify with a valid image URL
$writer->writeElement('width', '120');
$writer->writeElement('height', '68');
$writer->endElement(); # End image

# --- ADD CITIES SECTION ---
foreach ($cities as $city) {
    $writer->startElement("city");

    $writer->writeElement('name', htmlspecialchars($city['Name']));
    $writer->writeElement('country', htmlspecialchars($city['Country']));
    $writer->writeElement('population', number_format($city['Population']));
    $writer->writeElement('size', number_format($city['Size'], 2) . ' km²');
    $writer->writeElement('timezone', htmlspecialchars($city['Timezone']));
    $writer->writeElement('language', htmlspecialchars($city['Language']));
    $writer->writeElement('elevation', number_format($city['Elevation'], 2) . ' meters');

    $writer->endElement(); # End city
}

# --- ADD PLACES OF INTEREST SECTION ---
foreach ($places as $place) {
    $writer->startElement("place");

    $writer->writeElement('name', htmlspecialchars($place['Place_Name']));
    $writer->writeElement('city', htmlspecialchars($place['City_Name']));
    $writer->writeElement('address', htmlspecialchars($place['Address']));
    $writer->writeElement('description', htmlspecialchars($place['Description']));

    # Include the image (if available)
    if (!empty($place['Photos'])) {
        $writer->writeElement('photo', htmlspecialchars($place['Photos']));
    }

    $writer->writeElement('opening_time', $place['Opening_Time']);
    $writer->writeElement('closing_time', $place['Ending_Time']);

    $writer->endElement(); # End place
}

$writer->endElement(); # End channel
$writer->endElement(); # End RSS

$writer->endDocument();
$writer->flush();

# Clear output buffer
ob_end_flush();
?>
