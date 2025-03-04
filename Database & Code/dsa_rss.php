<?php
# Prevent output before headers
ob_start();

# Set timezone
@date_default_timezone_set("GMT");

# Secure database connection using PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=twin_city', 'root', '!Rr201612066', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

# Fetch Cities
try {
    $sql_cities = 'SELECT City_ID, Name, Country, Population, Size, Timezone, Language, Elevation FROM City';
    $query_cities = $pdo->prepare($sql_cities);
    $query_cities->execute();
    $cities = $query_cities->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching cities: " . $e->getMessage());
}

# Fetch Places of Interest
try {
    $sql_poi = 'SELECT poi.Place_ID, poi.Name AS Place_Name, poi.Address, poi.Description, poi.Photos, 
            poi.Opening_Time, poi.Ending_Time, poi.Type_ID, poi.Borough_ID,
            c.Name AS City_Name, c.Country, 
            b.Name AS Borough_Name, t.Name AS Type_Name
            FROM Place_Of_Interest poi
            JOIN City c ON c.City_ID = poi.City_ID
            JOIN Borough b ON b.Borough_ID = poi.Borough_ID
            JOIN Type t ON t.Type_ID = poi.Type_ID';
    $query_poi = $pdo->prepare($sql_poi);
    $query_poi->execute();
    $places = $query_poi->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching places: " . $e->getMessage());
}

# Set header for XML output
header('Content-Type: application/rss+xml; charset=UTF-8');

# Define RSS feed URL
$feed_url = 'http://localhost/rss_feed.php'; # Change this to your actual URL

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

# Add Atom link for RSS validation
$writer->startElement("atom:link");
$writer->writeAttribute("href", $feed_url);
$writer->writeAttribute("rel", "self");
$writer->writeAttribute("type", "application/rss+xml");
$writer->endElement(); # End atom:link

# RSS feed metadata
$writer->writeElement('title', 'Cities and Places of Interest');
$writer->writeElement('description', 'A list of cities and their places of interest.');
$writer->writeElement('link', $feed_url);
$writer->writeElement('lastBuildDate', date(DATE_RSS));

# Add channel image
$writer->startElement('image');
$writer->writeElement('title', 'Cities and Places of Interest');
$writer->writeElement('link', $feed_url);
$writer->writeElement('url', 'http://localhost/images/logo.png'); # Modify with a valid image URL
$writer->endElement(); # End image

# --- ADD CITIES TO RSS ---
foreach ($cities as $city) {
    $writer->startElement("item");
    $writer->writeElement('title', "City: " . html_entity_decode($city['Name']) . ", " . html_entity_decode($city['Country']));
    $writer->writeElement('link', "http://localhost/city/" . $city['City_ID']);
    
    # Use CDATA to prevent encoding issues
    $writer->startElement('description');
    $writer->writeCData("Population: " . number_format($city['Population']) . " | Size: " . number_format($city['Size'], 2) . " km²");
    $writer->endElement();

    $writer->writeElement('pubDate', date(DATE_RSS));
    $writer->writeElement('guid', "http://localhost/city/" . $city['City_ID']);
    $writer->endElement(); # End item
}

# --- ADD PLACES OF INTEREST TO RSS ---
foreach ($places as $place) {
    $writer->startElement("item");
    $writer->writeElement('title', "Place of Interest: " . html_entity_decode($place['Place_Name']));
    $writer->writeElement('link', "http://localhost/place/" . $place['Place_ID']);
    
    # Use CDATA for descriptions
    $writer->startElement('description');
    $writer->writeCData($place['Description']);
    $writer->endElement();

    $writer->writeElement('pubDate', date(DATE_RSS));
    $writer->writeElement('guid', "http://localhost/place/" . $place['Place_ID']);
    
    # Include an image (if available)
    if (!empty($place['Photos'])) {
        $writer->startElement('enclosure');
        $writer->writeAttribute('url', htmlspecialchars($place['Photos']));
        $writer->writeAttribute('type', 'image/jpeg'); 
        $writer->writeAttribute('length', '0'); # Required field for RSS validation
        $writer->endElement();
    }

    $writer->endElement(); # End item
}

$writer->endElement(); # End channel
$writer->endElement(); # End RSS

$writer->endDocument();
header('Content-Type: text/xml');
$writer->flush();

# Clear output buffer
ob_end_flush();
?>
