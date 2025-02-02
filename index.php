<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to the Best Restaurant. Discover freshly crafted meals made with love.">
    <title>Best Restaurant</title>

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">  
    <script defer src="script.js"></script>
</head>
<body>
    <?php include('header.php') ?>

    <!-- message for reservation -->
    <?php if (isset($_SESSION["reservation_success"])): ?>
        <div id="success-message" class="message"><?php echo htmlspecialchars($_SESSION["reservation_success"]); ?></div>
        <?php unset($_SESSION["reservation_success"]); ?>
    <?php endif; ?>

    <main>
        <section class="hero">
            <div class="slides">
                <div class="slide active" style="background-image: url('img/green1.jpg');"></div>
                <div class="slide" style="background-image: url('img/slider.jpg');"></div>
            </div>
            <div class="hero-content">
                <h1>Welcome to the Heart of Flavors</h1>
                <p>Discover freshly crafted meals made with love.</p>
                <p>Where every bite tells a story of taste and tradition.</p>
                <a href="menu.php" class="order-button">Book Now</a>
            </div>
            <div class="dots">
                <span class="dot active"></span>
                <span class="dot"></span>
            </div>
        </section>
        <!-- app -->
        <section class="app">
            <div class="text-section">
                <div class="stitle">Inside the app</div>
                <h2>Join our restaurant app</h2>
                <p>The Restaurant App is the ultimate way to enjoy fresh, delicious meals wherever you are. <br>
                Download now to order ahead for easy pickup or delivery. Join our free loyalty program to earn rewards, enjoy birthday treats, 
                access exclusive menu items, and more. Your next great meal is just a tap away</p>
                <div class="btn">
                    <a href="https://play.google.com/store/apps/details?id=com.globalfoodsoft.restaurantapp" class="underline-link" target="_blank">Download</a>
                </div>
            </div>
                
            <div class="app-img">
                <img src="img/app_promo.jpg" alt="Restaurant App Promo">
            </div>
        </section>

        <section class="flex">
            <div class="texti">
                <div>
                    <div class="subtitle">In the lab</div>
                    <h2>Step inside our Restaurant kitchen</h2>
                    <p>Discover the secrets of our kitchen and learn how we craft our signature dishes with passion and care, 
                    using only the freshest ingredients and traditional techniques to deliver an unforgettable dining experience.
                    </p>
                </div>
            </div>

            <div class="video-section" id="v">
                <video autoplay muted loop>
                    <source src="img/kitchen.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </section>

        <!-- Reviews -->
        <section id="tm" class="testimonials">
            <h2>Customer's Reviews</h2>
            <div class="testimonial-container">
                <div class="testimonial">
                    <p>"The food was fantastic! Fast service and a wonderful atmosphere."</p>
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="client-name">- CUSTOMER 1.</p>
                </div>
                <div class="testimonial">
                    <p>"One of the best restaurants I've ever visited."</p>
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="client-name">- CUSTOMER 2.</p>
                </div>
                <div class="testimonial">
                    <p>"The taste of the food was unique, and the restaurant ambiance was very comfortable."</p>
                    <div class="rating">⭐⭐⭐⭐⭐</div>
                    <p class="client-name">- CUSTOMER 3.</p>
                </div>
            </div>
        </section>
    </main>

    <?php include("footer.php"); ?>

    <script>
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'block';
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>