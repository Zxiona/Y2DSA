<?php
// Define the Maps API key
$apiKey = 'AIzaSyA4XaLaHE88hCOIz54_3CY9qRk31x38B7A'; // Replace with your actual API key

// Coordinates for London
$londonLat = 51.5074;
$londonLng = -0.1278;

// Coordinates for New York
$NYLat = 40.7128;
$NYLng = -74.0060;

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
  <!-- Link to external CSS file for custom styles -->
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- wrapper to position the icon -->
  <div class="wrapper">
    <!-- Menu icon for triggering dynamic navigation menu -->
    <div class="menu-icon">
      <i class="fas fa-align-right"></i>
    </div>
  </div>

  <!-- wrapper to position the icon -->
  <div class="wrapper2">
    <!-- city icon for triggering change of location -->
    <div class="city-icon">
      <i class="fa-solid fa-city"></i>
    </div>
  </div>


  <div class="navigation-menu">
    <!-- Navigation links -->
    <nav>
      <li><a href="TCMap.php">Map</a></li>
      <li><a href="TCWeather.php">Weather</a></li>
    </nav>
  </div>

  <!-- Map container -->
  <div id="map"></div>

  <!-- Google Maps JavaScript API -->
  <script>
    // State variable to track the active city
    let isNewYorkActive = false;

    function initMap() {

      // Define the coordinates for London and New York
      const london = {
        lat: <?php echo $londonLat; ?>,
        lng: <?php echo $londonLng; ?>
      };

      const newyork = {
        lat: <?php echo $NYLat; ?>,
        lng: <?php echo $NYLng; ?>
      }

      // Create a map centered on London initially
      const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12, // Adjust zoom level
        center: london, // Center the map on London
      });

      // Add markers for both cities
      const londonMarker = new google.maps.Marker({
        position: london,
        map: map,
        title: "London, UK",
      });

      const newYorkMarker = new google.maps.Marker({
        position: newyork,
        map: map,
        title: "New York, USA",
      });

      // Initially hide the New York marker
      newYorkMarker.setVisible(false);

      // Add a click event listener to the city-icon
      document.querySelector('.city-icon').addEventListener('click', function() {
        // Toggle the active city state
        isNewYorkActive = !isNewYorkActive;

        // Update the map center and marker visibility based on the active city
        if (isNewYorkActive) {
          map.setCenter(newyork);
          londonMarker.setVisible(false);
          newYorkMarker.setVisible(true);
        } else {
          map.setCenter(london);
          londonMarker.setVisible(true);
          newYorkMarker.setVisible(false);
        }
      });
    }
  </script>

  <!-- Load the Google Maps API with your API key -->
  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $apiKey; ?>&callback=initMap"></script>

  <script>
    // Toggle class 'active' for menu icon and navigation menu on click
    $(".menu-icon").click(function() {
      $(this).toggleClass("active");
      $(".navigation-menu").toggleClass("active");
      $(".menu-icon i").toggleClass("fa-times");
    });

    $(".city-icon").click(function() {
      $(this).toggleClass("active");
      $("ny-icon i").toggleClass("fa-times");
    });
  </script>

</body>

</html>