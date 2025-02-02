<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Starto sesionin vetëm nëse nuk është filluar ende
}
echo '

    <header class="header">
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <h2><a href="index.php">Restaurant</a></h2>
            </div>
            
            <!-- Menu Toggle Button for Mobile -->
            <div class="toggle-button" id="toggle-menu">
                <div></div>
                <div></div>
                <div></div>
            </div>

            <!-- Navigation -->
            <nav class="nav" id="nav">
                <button class="close-button" id="close-menu">&times;</button>
                <ul class="nav-list">
                    <li><a href="index.php" class="nav-link">Home</a></li>
                    <li><a href="menu.php" class="nav-link">Menu</a></li>
                    <li><a href="reservations.php" class="nav-link">Reservations</a></li>
                    <li><a href="about.php" class="nav-link">About</a></li>
                    <li><a href="contact.php" class="nav-link">Contact</a></li>
                    <li>';
                        if (isset($_SESSION["username"])) {
                            echo '<a href="logout.php" class="login-button">Log Out</a>';
                        } else {
                            echo '<a href="login.php" class="login-button">Log In</a>';
                        }
                    echo '</li>
                </ul>
            </nav>
        </div>
    </header>
'
?>