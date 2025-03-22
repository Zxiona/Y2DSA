<?php
// include function for connecting file to external.php
include "config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twin Cities Map</title>

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

    <!-- City Toggle Button -->
    <div class="city-toggle" id="cityToggle">
        <i class="fa-solid fa-city"></i>
    </div>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- Bootstrap Modal for City Information -->
    <div class="modal fade" id="cityInfoModal" tabindex="-1" aria-labelledby="cityInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cityInfoModalLabel">City Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="londonInfo">
                        <h4>London, United Kingdom</h4>
                        <p>Population: 8,982,000</p>
                        <p>Area: 1,572 km²</p>
                        <p>Currently displaying points of interest in London.</p>
                    </div>
                    <div id="newyorkInfo" style="display: none;">
                        <h4>New York, United States</h4>
                        <p>Population: 8,419,600</p>
                        <p>Area: 783.8 km²</p>
                        <p>Currently displaying points of interest in New York.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Google Maps JavaScript -->
    <script>
        // Pass PHP data to JavaScript
        const ldnpoiData = <?php echo json_encode($ldnpoiData); ?>;
        const nypoiData = <?php echo json_encode($nypoiData); ?>;

        // State variable to track the active city
        let isNewYorkActive = false;

        function initMap() {
            console.log("London POI Data:", ldnpoiData);
            console.log("New York POI Data:", nypoiData);

            const london = {
                lat: <?php echo $londonLat; ?>,
                lng: <?php echo $londonLng; ?>
            };
            const newyork = {
                lat: <?php echo $NYLat; ?>,
                lng: <?php echo $NYLng; ?>
            };

            // Initialize map centered on London
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: london,
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                    position: google.maps.ControlPosition.TOP_LEFT
                },
                fullscreenControl: true,
                streetViewControl: true,
                zoomControl: true
            });

            // Create a single InfoWindow instance for hover effect
            const infowindow = new google.maps.InfoWindow();

            // Store markers in arrays for toggling
            let londonMarkers = [];
            let newYorkMarkers = [];

            // Function to create markers with improved styling
            function createMarker(poi, isNewYork = false) {
                const markerIcon = {
                    url: getMarkerIcon(poi.Type_ID),
                    scaledSize: new google.maps.Size(30, 30)
                };

                const marker = new google.maps.Marker({
                    position: {
                        lat: parseFloat(poi.Latitude),
                        lng: parseFloat(poi.Longitude)
                    },
                    map: isNewYork ? null : map,
                    title: poi.Name,
                    icon: markerIcon,
                    animation: google.maps.Animation.DROP
                });

                // Create info window content with Bootstrap styling
                const contentString = `
                    <div class="marker-info">
                        <h5>${poi.Name}</h5>
                        <p class="mb-1"><small>${poi.Address}</small></p>
                        <p class="mb-1"><small>Opening: ${poi.Opening_time} - Closing: ${poi.Ending_time}</small></p>
                        <button class="btn btn-sm btn-primary mt-2" onclick="window.location.href='details.php?id=${poi.Place_ID}'">View Details</button>
                    </div>
                `;

                // MouseOver event to show brief info
                marker.addListener("mouseover", () => {
                    infowindow.setContent(contentString);
                    infowindow.open(map, marker);
                });

                // MouseOut event to hide the InfoWindow
                marker.addListener("mouseout", () => {
                    infowindow.close();
                });

                // Click event to navigate to a detailed page
                marker.addListener("click", () => {
                    window.location.href = `details.php?id=${poi.Place_ID}`;
                });

                return marker;
            }

            // Function to get appropriate marker icon based on type
            function getMarkerIcon(typeId) {
                switch (parseInt(typeId)) {
                    case 1:
                        return 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'; // Restaurants
                    case 2:
                        return 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png'; // Train Stations
                    case 3:
                        return 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'; // Universities
                    case 4:
                        return 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png'; // Banks
                    case 5:
                        return 'https://maps.google.com/mapfiles/ms/icons/purple-dot.png'; // Museums
                    case 6:
                        return 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png'; // 5 Star Hotels
                    default:
                        return 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
                }
            }

            // Create markers for London POIs
            ldnpoiData.forEach(poi => {
                const marker = createMarker(poi, false);
                londonMarkers.push(marker);
            });

            // Create markers for New York POIs
            nypoiData.forEach(poi => {
                const marker = createMarker(poi, true);
                newYorkMarkers.push(marker);
            });

            // Initially hide New York markers
            newYorkMarkers.forEach(marker => marker.setMap(null));

            // City toggle functionality with improved UI feedback
            document.getElementById('cityToggle').addEventListener('click', function() {
                isNewYorkActive = !isNewYorkActive;
                this.classList.toggle('active');

                if (isNewYorkActive) {
                    // Switch to New York
                    map.setCenter(newyork);
                    map.setZoom(14);
                    londonMarkers.forEach(marker => marker.setMap(null));
                    newYorkMarkers.forEach(marker => marker.setMap(map));

                    // Show New York info in modal
                    document.getElementById('londonInfo').style.display = 'none';
                    document.getElementById('newyorkInfo').style.display = 'block';
                } else {
                    // Switch to London
                    map.setCenter(london);
                    map.setZoom(14);
                    londonMarkers.forEach(marker => marker.setMap(map));
                    newYorkMarkers.forEach(marker => marker.setMap(null));

                    // Show London info in modal
                    document.getElementById('londonInfo').style.display = 'block';
                    document.getElementById('newyorkInfo').style.display = 'none';
                }

                // Show the modal with city information
                const cityInfoModal = new bootstrap.Modal(document.getElementById('cityInfoModal'));
                cityInfoModal.show();
            });

            // Add a legend to the map
            const legend = document.createElement('div');
            legend.id = 'legend';
            legend.className = 'bg-white p-2 rounded shadow-sm';
            legend.style.position = 'absolute';
            legend.style.bottom = '20px';
            legend.style.left = '20px';
            legend.style.zIndex = '1000';
            legend.innerHTML = `
                <h6 class="mb-2">Legend</h6>
                <div class="d-flex align-items-center mb-1">
                    <img src="https://maps.google.com/mapfiles/ms/icons/red-dot.png" height="20" width="20" alt="Restaurant">
                    <span class="ms-2 small">Restaurants</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <img src="https://maps.google.com/mapfiles/ms/icons/blue-dot.png" height="20" width="20" alt="Train Station">
                    <span class="ms-2 small">Train Stations</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <img src="https://maps.google.com/mapfiles/ms/icons/green-dot.png" height="20" width="20" alt="University">
                    <span class="ms-2 small">Universities</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <img src="https://maps.google.com/mapfiles/ms/icons/yellow-dot.png" height="20" width="20" alt="Bank">
                    <span class="ms-2 small">Banks</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <img src="https://maps.google.com/mapfiles/ms/icons/purple-dot.png" height="20" width="20" alt="Museum">
                    <span class="ms-2 small">Museums</span>
                </div>
                <div class="d-flex align-items-center">
                    <img src="https://maps.google.com/mapfiles/ms/icons/orange-dot.png" height="20" width="20" alt="Hotel">
                    <span class="ms-2 small">5 Star Hotels</span>
                </div>
            `;
            document.body.appendChild(legend);
        }
    </script>

    <!-- Load the Google Maps API with your API key -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo MapAPIKey; ?>&callback=initMap"></script>
</body>

</html>