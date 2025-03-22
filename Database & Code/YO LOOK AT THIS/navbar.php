<?php
// Get current page filename to highlight active navigation link
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
  <div class="container">
    <a class="navbar-brand" href="TCMap.php">
      <i class="fas fa-city me-2"></i>Twin Cities
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'TCMap.php') ? 'active' : ''; ?>" href="TCMap.php">
            <i class="fas fa-map-marked-alt me-1"></i> Map
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'TCWeather.php') ? 'active' : ''; ?>" href="TCWeather.php">
            <i class="fas fa-cloud-sun me-1"></i> Weather
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
