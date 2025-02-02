<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include_once("db_connect.php");
$db = new DataBaza();
$db->connectToDatabase();
$conn = $db->getConnection();

$dashboard = new Dashboard($conn);
$adminName = $_SESSION["username"]; // Merr emrin e adminit nga sesioni

class Dashboard {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function renderHeader($adminName) {
        echo '<div class="header">';
        echo '<h1 class="welcome">Welcome, ' . htmlspecialchars($adminName) . '</h1>';
        
        echo '<nav class="top-nav">';
        echo '<ul>';
        echo '<li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>';
        echo '<li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>';
        echo '<li><a href="rStats.php"><i class="fas fa-calendar-check"></i> Reservations</a></li>';
        echo '<li><a href="menuitem.php"><i class="fas fa-utensils"></i> Menu</a></li>';
        echo '<li><a href="messages.php"><i class="fas fa-envelope"></i> Messages</a></li>';
        echo '<li><a href="images.php"><i class="fas fa-images"></i> Images</a></li>';
        echo '</ul>';
        echo '</nav>';
    
        // Dropdown për profilin
        echo '<div class="dropdown">';
        echo '<button class="logout"><i class="fas fa-cog"></i></button>';
        echo '<div class="dropdown-content">';
        echo '<a href="dashboard.php">Profile</a>';
        echo '<a href="logout.php">Logout</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    public function fetchCardData() {
        $queries = [
            "Users" => "SELECT COUNT(*) as count FROM users",
            "Reservations" => "SELECT COUNT(*) as count FROM reservations",
            "Menu Items" => "SELECT COUNT(*) as count FROM menu",
            "Messages" => "SELECT COUNT(*) as count FROM contactmessages",
            "Images" => "SELECT COUNT(*) as count FROM images"
        ];

        $cardsData = [];
        foreach ($queries as $title => $query) {
            $result = $this->conn->query($query);
            if ($result && $row = $result->fetch_assoc()) {
                $cardsData[] = [
                    "title" => $title,
                    "description" => "Total $title",
                    "count" => $row['count']
                ];
            } else {
                $cardsData[] = [
                    "title" => $title,
                    "description" => "Total $title",
                    "count" => 0
                ];
            }
        }

        return $cardsData;
    }

    public function renderCards($cardsData) {
        echo '<div class="cards">';
        foreach ($cardsData as $card) {
            echo '<div class="card">';
            echo '<div class="icon"><i class="fas fa-' . $this->getIconForCard($card["title"]) . '"></i></div>';
            echo '<h3>' . htmlspecialchars($card["title"]) . '</h3>';
            echo '<p>' . htmlspecialchars($card["description"]) . '</p>';
            echo '<div class="count">' . htmlspecialchars($card["count"]) . '</div>';
            echo '</div>';
        }
        echo '</div>';
    }

    private function getIconForCard($title) {
        switch ($title) {
            case 'Users':
                return 'users';
            case 'Reservations':
                return 'calendar-check';
            case 'Menu Items':
                return 'utensils';
            case 'Messages':
                return 'envelope';
            case 'Images':
                return 'images';
            default:
                return 'chart-line';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php $dashboard->renderHeader($adminName); ?>

    <button class="menu-toggle" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>
            <?php
            if (basename($_SERVER['PHP_SELF']) === 'dashboard.php') {
                $cardsData = $dashboard->fetchCardData();
                $dashboard->renderCards($cardsData);
            }
            ?>
     <script>
        // dashboard.js
        document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.querySelector('.menu-toggle');
        const topNav = document.querySelector('.top-nav');

        menuToggle.addEventListener('click', function() {
            topNav.classList.toggle('active');
    });

        // Mbyll menun kur klikohet jasht saj
        document.addEventListener('click', function(event) {
    if (!topNav.contains(event.target) && !menuToggle.contains(event.target)) {
            topNav.classList.remove('active');
        }
    });
});
     </script>
</body>
</html>