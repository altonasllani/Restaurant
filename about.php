<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discover the story behind Best Restaurant and what makes us unique.">
    <title>About Us - Best Restaurant</title>
    <link rel="stylesheet" href="about.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Merriweather&display=swap" rel="stylesheet">
    <script defer src="script.js"></script>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">
</head>
<body>
<?php include('header.php') ?>


    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-container">
            <!-- Image Section -->
            <div class="about-image">
                <img src="img/RA.jpg" alt="Restaurant Ambiance">
            </div>
            <!-- Text Section -->
            <div class="about-text">
                <h2>About Us</h2>
                <p>
                    At Our Restaurant, we believe that dining is more than just eating; it's an experience. 
                    Our mission is to bring people together over exceptional food, delightful ambiance, and unforgettable moments.
                </p>
                <p>
                    From farm-to-table ingredients to innovative culinary creations, we pour our passion into every plate.
                    Our dedicated chefs and team strive to exceed your expectations and make every visit special.
                </p>
                <p>
                    Whether it's a family gathering, a romantic dinner, or a celebration with friends, Best Restaurant is here to make it memorable.
                </p>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="core-values">
        <h2>What Makes Us Special</h2>
        <div class="values-container">
            <div class="value-box">
                <img src="img/fresh.jpg" alt="Fresh Ingredients">
                <h3>Fresh Ingredients</h3>
                <p>We source only the freshest, locally-sourced ingredients to create our dishes.</p>
            </div>
            <div class="value-box">
                <img src="img/service.jpg" alt="Exceptional Service">
                <h3>Exceptional Service</h3>
                <p>Your satisfaction is our priority. We go above and beyond to make your visit enjoyable.</p>
            </div>
            <div class="value-box">
                <img src="img/cuisine.jpg" alt="Innovative Cuisine">
                <h3>Innovative Cuisine</h3>
                <p>Our chefs blend tradition with creativity to craft unique dining experiences.</p>
            </div>
        </div>
    </section>
<?php include("footer.php"); ?>
    
</body>
</html>
