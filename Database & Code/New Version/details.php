<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "twin_city";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and retrieve 'id' from GET parameters
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request! POI ID is missing or incorrect.");
}

$poi_id = intval($_GET['id']); // Convert to integer

// Retrieve POI details
$query = "SELECT p.*, t.Name AS Type_Name, b.Name AS Borough_Name
          FROM Place_of_Interest p
          JOIN Type t ON p.Type_ID = t.Type_ID
          JOIN Borough b ON p.Borough_ID = b.Borough_ID
          WHERE p.Place_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $poi_id);
$stmt->execute();
$result = $stmt->get_result();
$poi = $result->fetch_assoc();

// Check if POI exists
if (!$poi) {
    die("POI not found!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($poi['Name'] ?? 'Place of Interest'); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($poi['Name'] ?? 'Unknown'); ?></h1>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($poi['Address'] ?? 'N/A'); ?></p>
    <p><strong>Borough:</strong> <?php echo htmlspecialchars($poi['Borough_Name'] ?? 'N/A'); ?></p>
    <p><strong>Type:</strong> <?php echo htmlspecialchars($poi['Type_Name'] ?? 'N/A'); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($poi['Description'] ?? 'No description available.'); ?></p>
    <p><strong>Opening Time:</strong> <?php echo htmlspecialchars($poi['Opening_Time'] ?? $poi['opening_time'] ?? 'N/A'); ?></p>
    <p><strong>Closing Time:</strong> <?php echo htmlspecialchars($poi['Ending_Time'] ?? $poi['ending_time'] ?? 'N/A'); ?></p>


    <?php if (!empty($poi['Photos'])): ?>
        <img src="<?php echo htmlspecialchars($poi['Photos']); ?>" alt="<?php echo htmlspecialchars($poi['Name'] ?? 'Image'); ?>" width="400">
    <?php else: ?>
        <p>No image available.</p>
    <?php endif; ?>

    <br>
    <a href="TCMap.php">Back to Map</a>
</body>
</html>
