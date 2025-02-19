CREATE DATABASE twin_city;

USE twin_city;

CREATE TABLE IF NOT EXISTS City (
        City_ID INT PRIMARY KEY AUTO_INCREMENT,
        Name VARCHAR(255) NOT NULL,
        Country VARCHAR(100) NOT NULL,
        Population INT,
        Size FLOAT,
        Latitude DECIMAL(9,6),
        Longitude DECIMAL(9,6),
        Timezone VARCHAR(50),
        Language VARCHAR(50),
        Elevation FLOAT);

CREATE TABLE Type (
    Type_ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(20) NOT NULL
);

CREATE TABLE IF NOT EXISTS Borough (
        Borough_ID INT AUTO_INCREMENT PRIMARY KEY,
        City_ID INT,
        Name VARCHAR(100) NOT NULL,
        FOREIGN KEY (City_ID) REFERENCES City(City_ID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Place_Of_Interest (
        Place_ID INT PRIMARY KEY AUTO_INCREMENT,
        City_ID INT NOT NULL,
        Type_ID INT NOT NULL,
        Borough_ID INT NOT NULL,
        Name VARCHAR(255) NOT NULL,
        Address VARCHAR(255),
        Latitude DECIMAL(18,15),
        Longitude DECIMAL(18,15),
        Description TEXT,
        Photos VARCHAR(255),
        Opening_Time TIME,
        Ending_Time TIME,
        FOREIGN KEY (City_ID) REFERENCES City(City_ID) ON DELETE CASCADE,
        FOREIGN KEY (Type_ID) REFERENCES Type(Type_ID) ON DELETE CASCADE,
        FOREIGN KEY (Borough_ID) REFERENCES Borough(Borough_ID) ON DELETE CASCADE);

INSERT INTO City (Name, Country, Population, Size, Latitude, Longitude, timezone, language, elevation)
VALUES 
('London', 'United Kingdom', 8982000, 1572.0, 51.507351, -0.127758, 'GMT+0', 'English', 11.0),
('New York', 'United States', 8419600, 783.8, 40.712776, -74.005974, 'GMT-5', 'English', 10.0);

INSERT INTO Type (Name)
VALUES
('Restaurants'),
('Train Stations'),
('Universities'),
('Banks'),
('Museums'),
('5 Star Hotels');

INSERT INTO Borough (City_ID,Name)
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
        (1, 'Westminster'),
        (1, 'City of London');

INSERT INTO Borough (City_ID,Name)
        VALUES
        (2, 'Manhattan'),
        (2, 'Brooklyn'),
        (2, 'Queens'),
        (2, 'The Bronx'),
        (2, 'Staten Island');

INSERT INTO Place_of_Interest (City_ID, Type_ID, Borough_ID, Name, Address, Latitude, Longitude, Description, Photos, Opening_time, Ending_time)
    VALUES
    (1, 1, 32, 'Scully', '4 St James''s Market, London SW1Y 4AH', 51.509136687911685, -0.1329663068511105,'Hip, wood-hued eatery serving artfully plated seasonal flavors from Australia to India & Ireland.', 'https://i.imgur.com/zAp50yG.jpg', '08:00:00', '22:00:00'),
    (1, 2, 32, 'Charing Cross', 'Strand, London WC2N 5HF', 51.50829422485241, -0.12483750236822497, 'Charing Cross railway station (also known as London Charing Cross) is a central London railway terminus between the Strand and Hungerford Bridge in the City of Westminster.', 'https://i.imgur.com/XSB8EMh.jpg', '05:00:00', '23:00:00'),
    (1, 3, 32, 'University of Notre Dame (USA) in England', '1 Suffolk St, London SW1Y 4HG', 51.508420513914544, -0.1303707027499567, 'Notre Dame London is part of Notre Dame Global, a network of 12 global locations representing the University of Notre Dame’s mission to foster transformative global education, research, scholarship, and partnerships through innovation and collaboration.', 'https://i.imgur.com/XLMl5aX.jpg', '09:00:00', '18:00:00'),
    (1, 4, 32, 'HSBC Covent Garden', '16 King St, London WC2E 8JF', 51.51141719809663, -0.12495604692421396, 'No counter or coin service available.', 'https://i.imgur.com/nzcj5JW.jpg', '09:00:00', '17:00:00'),
    (1, 5, 32, 'The National Gallery', 'Trafalgar Square, London WC2N 5DN', 51.50906252259639, -0.12836337575917522, 'Trafalgar Square art museum whose masterworks trace the development of Western European painting.', 'https://i.imgur.com/IUKwxWf.jpg', '10:00:00', '18:00:00'),
    (1, 6, 32, 'Corinthia London', 'Whitehall Pl, London SW1A 2BD', 51.50658047404813, -0.12433406041980899, 'The Corinthia London Hotel, at the corner of Northumberland Avenue and Whitehall Place in central London, is a hotel and former British Government building, located on a triangular site between Trafalgar Square and the Thames Embankment.', 'https://i.imgur.com/4LCPpgy.jpg', '00:00:00', '23:59:59');

INSERT INTO Place_of_Interest (City_ID, Type_ID, Borough_ID, Name, Address, Latitude, Longitude, Description, Photos, Opening_time, Ending_time)
    VALUES
    (2, 1, 34, 'Reserve Cut', '40 Broad St 2nd Floor, New York, NY 10004, United States', 40.7061850023493, -74.01175457253665, 'High-end kosher steakhouse with a wine room and French-Asian menu that includes sushi and Wagyu beef.', 'https://i.imgur.com/YqVLAHX.jpg', '08:00:00', '22:00:00'),
    (2, 2, 34, 'World Trade Center', '10007, 70 Vesey St, New York, 10006, United States', 40.712844368679654, -74.01188602903355, 'The World Trade Center station is a terminal station on the PATH system, within the World Trade Center complex in the Financial District of Manhattan, New York City.', 'https://i.imgur.com/kEm63qX.jpg', '05:00:00', '23:00:00'),
    (2, 3, 34, 'New York University', 'New York, NY 10012, United States', 40.72960280456913, -73.99646090321473, 'Greenwich Village-based private school known for its liberal arts, law, business & medical programs.', 'https://i.imgur.com/vV3wKlq.jpg', '09:00:00', '18:00:00'),
    (2, 4, 34, 'Bank of America Financial Center', '50 Bayard St, New York, NY 10013, United States', 40.715404959295654, -73.99693820331343, 'There have four 24hours ATM.', 'https://i.imgur.com/0bFaPxo.png', '09:00:00', '17:00:00'),
    (2, 5, 34, '9/11 Memorial & Museum', '180 Greenwich St, New York, NY 10007, United States', 40.71173208843338, -74.01331474554571, 'Plaza, pools & exhibits honoring victims of 1993 & 2001 WTC terrorist attacks. Free timed admission.', 'https://i.imgur.com/e4ixMsP.jpg', '10:00:00', '18:00:00'),
    (2, 6, 34, 'The Beekman, A Thompson Hotel, by Hyatt', '123 Nassau St, New York, NY 10038, United States', 40.71131902044953, -74.00692557728706, 'Set in the Financial District, this posh hotel is a 3-minute walk from the Fulton Street subway station and a 14-minute walk from the iconic Brooklyn Bridge.', 'https://i.imgur.com/KNGQzZO.jpg', '00:00:00', '23:59:59');
