<?php
# Prevent output before headers
ob_start();

# Set timezone
@date_default_timezone_set("GMT");

# Secure database connection using PDO
try {
    $pdo = new PDO('mysql:host=localhost;dbname=twin_city', 'root', '', [
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

# Fetch Places of Interest (Including Type ID and Borough ID)
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
header('Content-Type: text/xml; charset=UTF-8');

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
$writer->startElement("cities");
foreach ($cities as $city) {
    $writer->startElement("city");

    $writer->writeElement('id', $city['City_ID']);
    $writer->writeElement('name', htmlspecialchars($city['Name']));
    $writer->writeElement('country', htmlspecialchars($city['Country']));
    $writer->writeElement('population', number_format($city['Population']));
    $writer->writeElement('size', number_format($city['Size'], 2) . ' km²');
    $writer->writeElement('timezone', htmlspecialchars($city['Timezone']));
    $writer->writeElement('language', htmlspecialchars($city['Language']));
    $writer->writeElement('elevation', number_format($city['Elevation'], 2) . ' meters');

    $writer->endElement(); # End city
}
$writer->endElement(); # End cities

# --- ADD PLACES OF INTEREST SECTION ---
$writer->startElement("places");
foreach ($places as $place) {
    $writer->startElement("place");

    $writer->writeElement('id', $place['Place_ID']);
    $writer->writeElement('name', htmlspecialchars($place['Place_Name']));
    $writer->writeElement('type', htmlspecialchars($place['Type_Name']));
    $writer->writeElement('borough_id', $place['Borough_ID']);
    $writer->writeElement('borough', htmlspecialchars($place['Borough_Name']));
    $writer->writeElement('city', htmlspecialchars($place['City_Name']));
    $writer->writeElement('country', htmlspecialchars($place['Country']));
    $writer->writeElement('address', htmlspecialchars($place['Address']));
    $writer->writeElement('description', htmlspecialchars($place['Description']));

    # Include the image (if available)
    if (!empty($place['Photos'])) {
        $writer->writeElement('photo', htmlspecialchars($place['Photos']));
    }

    $writer->writeElement('opening_time', $place['Opening_Time']);
    $writer->writeElement('closing_time', $place['Ending_Time']);

    # Add unique GUID for RSS readers
    $writer->startElement('guid');
    $writer->writeAttribute('isPermaLink', 'false');
    $writer->text('place-' . $place['Place_ID']);
    $writer->endElement();

    $writer->endElement(); # End place
}
$writer->endElement(); # End places

$writer->endElement(); # End channel
$writer->endElement(); # End RSS

$writer->endDocument();
$writer->flush();

# Clear output buffer
ob_end_flush();
?>
