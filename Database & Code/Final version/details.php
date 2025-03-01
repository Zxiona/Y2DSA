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
    <!-- Viewport settings to control layout on different devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">
    <!-- Google fonts for typography -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/2434cfa3b9.js" crossorigin="anonymous"></script>
    <title><?php echo htmlspecialchars($poi['Name'] ?? 'Place of Interest'); ?></title>
    <!-- Link to external CSS file for custom styles -->
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <header>
        <div class="inner-width">
            <h1>Details of place</h1>
            <div class="menu-icon">
                <i class="fas fa-align-right"></i>
            </div>
        </div>
    </header>

    <div class="navigation-menu">
        <!-- Navigation links -->
        <nav>
            <li><a href="TCMap.php">Map</a></li>
            <li><a href="TCWeather.php">Weather</a></li>
        </nav>
    </div>

    <div class="DETAILS_WRAPPER">
        <div class="PLACE_NAME">
            <h1><?php echo htmlspecialchars($poi['Name'] ?? 'Unknown'); ?></h1>
        </div>
        <div class="CONTENT">
            <p><strong>Address:</strong> <?php echo htmlspecialchars($poi['Address'] ?? 'N/A'); ?></p>
            <p><strong>Borough:</strong> <?php echo htmlspecialchars($poi['Borough_Name'] ?? 'N/A'); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($poi['Type_Name'] ?? 'N/A'); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($poi['Description'] ?? 'No description available.'); ?></p>
            <p><strong>Opening Time:</strong> <?php echo htmlspecialchars($poi['Opening_Time'] ?? $poi['opening_time'] ?? 'N/A'); ?></p>
            <p><strong>Closing Time:</strong> <?php echo htmlspecialchars($poi['Ending_Time'] ?? $poi['ending_time'] ?? 'N/A'); ?></p>
        </div>

        <div class="PLACE_PHOTO">
            <?php if (!empty($poi['Photos'])): ?>
                <img src="<?php echo htmlspecialchars($poi['Photos']); ?>" alt="<?php echo htmlspecialchars($poi['Name'] ?? 'Image'); ?>" width="400">
            <?php else: ?>
                <p>No image available.</p>
            <?php endif; ?>
        </div>

        <br>
        <a href="TCMap.php">Back to Map</a>
    </div>

    <script>
        // Toggle class 'active' for menu icon and navigation menu on click
        $(".menu-icon").click(function() {
            $(this).toggleClass("active");
            $(".navigation-menu").toggleClass("active");
            $(".menu-icon i").toggleClass("fa-times");
        });
    </script>
</body>

</html>