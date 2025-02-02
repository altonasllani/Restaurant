<?php
session_start(); // Starto sesionin

// Kontrollo nëse përdoruesi është i kyçur
if (!isset($_SESSION["username"])) {
    // Nëse nuk është i kyçur, ruaj të dhënat e rezervimit në sesion dhe ridrejto në login
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $_SESSION["reservation_data"] = [
            "name" => $_POST["name"],
            "phone" => $_POST["phone"],
            "date" => $_POST["date"],
            "time" => $_POST["time"],
            "guests" => $_POST["guests"],
            "table" => $_POST["table"]
        ];
        // Ridrejto direkt në login pa shfaqur mesazhin
        header("Location: login.php");
        exit();
    }
} else {
    // Nëse përdoruesi është i kyçur, ruaj të dhënat në databazë dhe ridrejto në index.php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        include("db_connect.php");

        $db = new DataBaza();
        $db->connectToDatabase();
        $conn = $db->getConnection();

        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $date = $_POST["date"];
        $time = $_POST["time"];
        $guests = $_POST["guests"];
        $table = $_POST["table"];
        $userId = $_SESSION["user_id"];

        // Ruaj rezervimin në databazë
        $stmt = $conn->prepare("INSERT INTO Reservations (UserID, FullName, PhoneNumber, ReservationDate, ReservationTime, Guests, TablePreference) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssis", $userId, $name, $phone, $date, $time, $guests, $table);

        if ($stmt->execute()) {
            $_SESSION["reservation_success"] = "Reservation made successfully!";
        } else {
            $_SESSION["reservation_error"] = "Failed to make reservation. Please try again.";
        }

        $stmt->close();
        header("Location: index.php"); // Ridrejto në index.php
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservation Form</title>
  <link rel="stylesheet" href="reservations.css">
  <style>
    /* Stilizimi i mesazhit të suksesit/gabimit */
  
  </style>
</head>
<body>

  <!-- Shigjeta për kthim prapa -->
  <div id="back-arrow" tabindex="0">
    &#8592; Back
  </div>

  <!-- Seksioni për formen e rezervimit -->
  <section id="reservation">
    <h2>Reserve a Table</h2>
    <p>Fill in the details below to reserve your spot at our restaurant.</p>

    <!-- Mesazhet për sukses ose gabim -->
    <?php if (isset($_SESSION["reservation_success"])): ?>
        <div id="success-message" class="message success"><?php echo htmlspecialchars($_SESSION["reservation_success"]); ?></div>
        <?php unset($_SESSION["reservation_success"]); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["reservation_error"])): ?>
        <div id="error-message" class="message error"><?php echo htmlspecialchars($_SESSION["reservation_error"]); ?></div>
        <?php unset($_SESSION["reservation_error"]); ?>
    <?php endif; ?>

    <form action="reservations.php" method="POST" class="reservation-form">
      <label for="name">Full Name:</label>
      <input type="text" id="name" name="name" placeholder="Your Name" required>

      <label for="phone">Phone Number:</label>
      <input type="text" id="phone" name="phone" pattern="[0-9]{9,}" placeholder="Your Phone Number" required>

      <label for="date-time">Date and Time:</label>
      <div class="date-time-container">
        <input type="date" id="date" name="date" required>
        <input type="time" id="time" name="time" required>
      </div>

      <label for="guests">Number of Guests:</label>
      <input type="number" id="guests" name="guests" min="1" max="20" required>

      <label for="table">Table Preference:</label>
      <select id="table" name="table" required>
        <option value="indoor">Indoor</option>
        <option value="outdoor">Outdoor</option>
        <option value="window">Window</option>
        <option value="corner">Corner</option>
      </select>

      <button type="submit">Reserve Now</button>
    </form>
  </section>

  <!-- Footer -->
  <footer>
    &copy; 2024 Restaurant. All Rights Reserved.
  </footer>

  <script>

    document.getElementById('back-arrow').addEventListener('click', function() {
      window.history.back();
    });
  </script>
</body>
</html>