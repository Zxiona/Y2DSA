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

    <header>
        <div class="inner-width">
            <h1>Weather</h1>
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

<?php

include("OpenWeather.php");

?>