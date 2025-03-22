<?php
// Enable error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Include configuration file
include "config.php";

// Database connection
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
$query = "SELECT p.*, t.Name AS Type_Name, b.Name AS Borough_Name, c.Name AS City_Name, c.Country
          FROM Place_of_Interest p
          JOIN Type t ON p.Type_ID = t.Type_ID
          JOIN Borough b ON p.Borough_ID = b.Borough_ID
          JOIN City c ON p.City_ID = c.City_ID
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($poi['Name'] ?? 'Place of Interest'); ?></title>

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

    <div class="container py-5 poi-details">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="TCMap.php">Map</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($poi['Name'] ?? 'Details'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h1 class="display-4 poi-name"><?php echo htmlspecialchars($poi['Name'] ?? 'Unknown'); ?></h1>
            </div>
        </div>

        <div class="row">
            <!-- Photo Column -->
            <div class="col-lg-6 mb-4">
                <div class="poi-photo">
                    <?php if (!empty($poi['Photos'])): ?>
                        <img src="<?php echo htmlspecialchars($poi['Photos']); ?>"
                            alt="<?php echo htmlspecialchars($poi['Name'] ?? 'Image'); ?>"
                            class="img-fluid rounded">
                    <?php else: ?>
                        <div class="bg-light text-center py-5">
                            <i class="fas fa-image fa-4x text-muted"></i>
                            <p class="mt-3">No image available</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Map for Location -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Location</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="poiMap" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <!-- Information Column -->
            <div class="col-lg-6">
                <div class="poi-info">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge bg-primary me-2"><?php echo htmlspecialchars($poi['Type_Name'] ?? 'N/A'); ?></span>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($poi['City_Name'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($poi['Country'] ?? 'N/A'); ?></span>
                    </div>

                    <div class="mb-4">
                        <h4>Description</h4>
                        <p><?php echo htmlspecialchars($poi['Description'] ?? 'No description available.'); ?></p>
                    </div>

                    <div class="mb-4">
                        <h4>Details</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="fas fa-map-marker-alt me-2 text-primary" style="width: 20px;"></i>
                                    <div>
                                        <strong>Address</strong>
                                        <p><?php echo htmlspecialchars($poi['Address'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="fas fa-map me-2 text-primary" style="width: 20px;"></i>
                                    <div>
                                        <strong>Borough</strong>
                                        <p><?php echo htmlspecialchars($poi['Borough_Name'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="far fa-clock me-2 text-primary" style="width: 20px;"></i>
                                    <div>
                                        <strong>Opening Hours</strong>
                                        <p><?php echo htmlspecialchars($poi['Opening_Time'] ?? 'N/A'); ?> - <?php echo htmlspecialchars($poi['Ending_Time'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <i class="fas fa-globe me-2 text-primary" style="width: 20px;"></i>
                                    <div>
                                        <strong>Coordinates</strong>
                                        <p><?php echo htmlspecialchars($poi['Latitude'] ?? 'N/A'); ?>, <?php echo htmlspecialchars($poi['Longitude'] ?? 'N/A'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related POIs in the same borough -->
                    <?php
                    // Query to get other POIs in the same borough
                    $relatedQuery = "SELECT Place_ID, Name, Type_ID FROM Place_of_Interest 
                                    WHERE Borough_ID = ? AND Place_ID != ? 
                                    LIMIT 3";
                    $relatedStmt = $conn->prepare($relatedQuery);
                    $relatedStmt->bind_param("ii", $poi['Borough_ID'], $poi_id);
                    $relatedStmt->execute();
                    $relatedResult = $relatedStmt->get_result();

                    if ($relatedResult->num_rows > 0):
                    ?>
                        <div class="mb-4">
                            <h4>Nearby Places of Interest</h4>
                            <div class="list-group">
                                <?php while ($related = $relatedResult->fetch_assoc()): ?>
                                    <a href="details.php?id=<?php echo $related['Place_ID']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <?php echo htmlspecialchars($related['Name']); ?>
                                        <span class="badge bg-primary rounded-pill">
                                            <?php
                                            // Get type name based on Type_ID
                                            $typeQuery = "SELECT Name FROM Type WHERE Type_ID = ?";
                                            $typeStmt = $conn->prepare($typeQuery);
                                            $typeStmt->bind_param("i", $related['Type_ID']);
                                            $typeStmt->execute();
                                            $typeResult = $typeStmt->get_result();
                                            $type = $typeResult->fetch_assoc();
                                            echo htmlspecialchars($type['Name'] ?? 'N/A');
                                            ?>
                                        </span>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="TCMap.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Map
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Twin Cities Project</h5>
                    <p class="small">A web application showcasing twin cities and their points of interest.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="small">© <?php echo date('Y'); ?> Twin Cities Project</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Google Maps JavaScript for POI Location -->
    <script>
        function initMap() {
            const poiLocation = {
                lat: <?php echo $poi['Latitude']; ?>,
                lng: <?php echo $poi['Longitude']; ?>
            };

            const map = new google.maps.Map(document.getElementById("poiMap"), {
                zoom: 16,
                center: poiLocation,
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                fullscreenControl: true,
                streetViewControl: true
            });

            const marker = new google.maps.Marker({
                position: poiLocation,
                map: map,
                title: "<?php echo addslashes($poi['Name']); ?>",
                animation: google.maps.Animation.DROP
            });

            const infoWindow = new google.maps.InfoWindow({
                content: `<div class="info-window">
                    <h5><?php echo addslashes($poi['Name']); ?></h5>
                    <p><?php echo addslashes($poi['Address']); ?></p>
                </div>`
            });

            marker.addListener("click", () => {
                infoWindow.open(map, marker);
            });
        }
    </script>

    <!-- Load the Google Maps API with your API key -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo MapAPIKey; ?>&callback=initMap"></script>
</body>

</html>