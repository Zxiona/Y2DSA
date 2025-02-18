<?php
// Database connection
$servername = "localhost"; // Change if using a remote server
$username = "root";        // Your MySQL username
$password = "";   // Your MySQL password
$dbname = "twin_city";     // Database name

// Create connection to MySQL server
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    echo "Invalid request! ID parameter is missing.";
    exit;
}

$poi_id = intval($_GET['id']);

// Corrected query: Use `Place_ID` instead of `ID`
$query = "SELECT * FROM place_of_interest WHERE Place_ID = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("i", $poi_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $poi = $result->fetch_assoc();

    //debugging rq, can be error msg
    echo "<pre>";
    print_r($poi);
    echo "</pre>";
} else {
    echo "POI not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($poi['Name']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1><?php echo htmlspecialchars($poi['Name']); ?></h1>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($poi['Address']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($poi['Description']); ?></p>
    <p><strong>Opening Time:</strong> <?php echo htmlspecialchars($poi['Opening_time']); ?></p>
    <p><strong>Closing Time:</strong> <?php echo htmlspecialchars($poi['Ending_time']); ?></p>
    <?php if (!empty($poi['Photos'])): ?>
        <img src="<?php echo htmlspecialchars($poi['Photos']); ?>" alt="<?php echo htmlspecialchars($poi['Name']); ?>" width="400">
    <?php endif; ?>
    <br>
    <a href="TCMap.php">Back to Map</a>
</body>

</html>
