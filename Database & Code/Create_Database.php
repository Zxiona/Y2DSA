<?php
$servername = "localhost"; // Change if using a remote server
$username = "root";        // Your MySQL username
$password = " ";            // Your MySQL password
$dbname = "twin_city";     // Database name

// Create connection to MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database 'twin_city' created successfully.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// SQL queries to create tables
$sql_queries = [
    "CREATE TABLE IF NOT EXISTS City (
        City_ID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(255) NOT NULL,
        Country VARCHAR(100) NOT NULL,
        Population INT,
        Size FLOAT,
        Latitude DECIMAL(9,6),
        Longitude DECIMAL(9,6),
        Timezone VARCHAR(50),
        Language VARCHAR(50),
        Elevation FLOAT
    )",

    "CREATE TABLE IF NOT EXISTS Type (
        Type_ID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(20) NOT NULL
    )",

    "CREATE TABLE IF NOT EXISTS Place_Of_Interest (
        Place_ID INT PRIMARY KEY AUTO_INCREMENT,
        City_ID INT NOT NULL,
        Type_ID INT NOT NULL,
        Name VARCHAR(255) NOT NULL,
        Address VARCHAR(255),
        Description TEXT,
        Photos VARCHAR(255),
        Opening_Time TIME,
        Ending_Time TIME,
        FOREIGN KEY (City_ID) REFERENCES City(City_ID) ON DELETE CASCADE,
        FOREIGN KEY (Type_ID) REFERENCES Type(Type_ID) ON DELETE CASCADE
    )",

    "CREATE TABLE IF NOT EXISTS Borough (
        Borough_ID INT AUTO_INCREMENT PRIMARY KEY,
        City_ID INT,
        Place_ID INT DEFAULT NULL,
        Name VARCHAR(100) NOT NULL,
        FOREIGN KEY (City_ID) REFERENCES City(City_ID) ON DELETE CASCADE,
        FOREIGN KEY (Place_ID) REFERENCES Place_of_Interest(Place_ID) ON DELETE CASCADE
    )"
];

// Execute table creation
foreach ($sql_queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Table created successfully.<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
}

// Insert sample data
$insert_queries = [
    "INSERT INTO City (Name, Country, Population, Size, Latitude, Longitude, timezone, language, elevation) VALUES 
    ('London', 'United Kingdom', 8982000, 1572.0, 51.507351, -0.127758, 'GMT+0', 'English', 11.0),
    ('New York', 'United States', 8419600, 783.8, 40.712776, -74.005974, 'GMT-5', 'English', 10.0);",

    "INSERT INTO Type (Name)
     VALUES ('Restaurants'), ('Train Stations'), ('Universities'), ('Banks'), ('Museums'), ('5 Star Hotels')",

    "INSERT INTO Place_of_Interest (City_ID, Type_ID, Name, Address, Description, Photos, Opening_time, Ending_time)
    VALUES
    (1, 1, 'Scully', '4 St James''s Market, London SW1Y 4AH', 'Restaurant', 'https://i.imgur.com/zAp50yG.jpg', '08:00:00', '22:00:00'),
    (1, 2, 'Charing Cross', 'Strand, London WC2N 5HF', 'Train Station', 'https://i.imgur.com/XSB8EMh.jpg', '05:00:00', '23:00:00'),
    (1, 3, 'University of Notre Dame (USA) in England', '1 Suffolk St, London SW1Y 4HG', 'University', 'https://i.imgur.com/XLMl5aX.jpg', '09:00:00', '18:00:00'),
    (1, 4, 'HSBC Covent Garden', '16 King St, London WC2E 8JF', 'Bank', 'https://i.imgur.com/nzcj5JW.jpg', '09:00:00', '17:00:00'),
    (1, 5, 'The National Gallery', 'Trafalgar Square, London WC2N 5DN', 'Museum', 'https://i.imgur.com/IUKwxWf.jpg', '10:00:00', '18:00:00'),
    (1, 6, 'Corinthia London', 'Whitehall Pl, London SW1A 2BD', '5 Star Hotel', 'https://i.imgur.com/4LCPpgy.jpg', '00:00:00', '23:59:59')",

    "INSERT INTO Place_of_Interest (City_ID, Type_ID, Name, Address, Description, Photos, Opening_time, Ending_time)
    VALUES
    (2, 1, 'Reserve Cut', '40 Broad St 2nd Floor, New York, NY 10004, United States', 'Restaurant', 'https://i.imgur.com/YqVLAHX.jpg', '08:00:00', '22:00:00'),
    (2, 2, 'World Trade Center', '10007, 70 Vesey St, New York, 10006, United States', 'Train Station', 'https://i.imgur.com/kEm63qX.jpg', '05:00:00', '23:00:00'),
    (2, 3, 'New York University', 'New York, NY 10012, United States', 'University', 'https://i.imgur.com/vV3wKlq.jpg', '09:00:00', '18:00:00'),
    (2, 4, 'Bank of America Financial Center', '50 Bayard St, New York, NY 10013, United States', 'Bank', 'https://i.imgur.com/0bFaPxo.png', '09:00:00', '17:00:00'),
    (2, 5, '9/11 Memorial & Museum', '180 Greenwich St, New York, NY 10007, United States', 'Museum', 'https://i.imgur.com/e4ixMsP.jpg', '10:00:00', '18:00:00'),
    (2, 6, 'The Beekman, A Thompson Hotel, by Hyatt', '123 Nassau St, New York, NY 10038, United States', '5 Star Hotel', 'https://i.imgur.com/KNGQzZO.jpg', '00:00:00', '23:59:59')",


    "INSERT INTO Borough (City_ID,Name)
        VALUES
        (1, 'Barking and Dagenham'),
        (1, 'Barnet'),
        (1, 'Bexley'),
        (1, 'Brent'),
        (1, 'Bromley'),
        (1, 'Camden'),
        (1, 'Croydon'),
        (1, 'Ealing'),
        (1, 'Enfield'),
        (1, 'Greenwich'),
        (1, 'Hackney'),
        (1, 'Hammersmith and Fulham'),
        (1, 'Haringey'),
        (1, 'Harrow'),
        (1, 'Havering'),
        (1, 'Hillingdon'),
        (1, 'Hounslow'),
        (1, 'Islington'),
        (1, 'Kensington and Chelsea'),
        (1, 'Kingston upon Thames'),
        (1, 'Lambeth'),
        (1, 'Lewisham'),
        (1, 'Merton'),
        (1, 'Newham'),
        (1, 'Redbridge'),
        (1, 'Richmond upon Thames'),
        (1, 'Southwark'),
        (1, 'Sutton'),
        (1, 'Tower Hamlets'),
        (1, 'Waltham Forest'),
        (1, 'Wandsworth'),
        (1, 'City of London');",

    "INSERT INTO Borough (City_ID,Place_ID,Name)
        VALUES
        (1, 1, 'Westminster'), -- Scully
        (1, 2, 'Westminster'), -- Charing Cross
        (1, 3, 'Westminster'), -- University of Notre Dame (USA) in England
        (1, 4, 'Westminster'), -- HSBC Covent Garden
        (1, 5, 'Westminster'), -- The National Gallery
        (1, 6, 'Westminster');",

    "INSERT INTO Borough (City_ID, Place_ID, Name)
        VALUES
        (2, 1, 'Manhattan'), -- Reserve Cut
        (2, 2, 'Manhattan'), -- World Trade Center
        (2, 3, 'Manhattan'), -- New York University
        (2, 4, 'Manhattan'), -- Bank of America Financial Center
        (2, 5, 'Manhattan'), -- 9/11 Memorial & Museum
        (2, 6, 'Manhattan');", 
        
    "INSERT INTO Borough (City_ID,Name)
        VALUES
        (2, 'Brooklyn'),
        (2, 'Queens'),
        (2, 'The Bronx'),
        (2, 'Staten Island');"
];

// Execute insert queries
foreach ($insert_queries as $query) {
    if ($conn->query($query) === TRUE) {
        echo "Data inserted successfully.<br>";
    } else {
        echo "Error inserting data: " . $conn->error . "<br>";
    }
}

// Close connection
$conn->close();
?>
