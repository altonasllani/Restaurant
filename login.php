<?php
session_start(); // Starto sesionin
include("db_connect.php");

$db = new DataBaza();
$db->connectToDatabase();
$conn = $db->getConnection();

// Trajto formën e login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["buttonL"])) {
  $username = trim($_POST["usernameL"]);
  $password = trim($_POST["passwordL"]);

  if (empty($username) || empty($password)) {
      echo "<script>alert('Please fill in all fields');</script>";
  } else {
      $sql = "SELECT * FROM Users WHERE Username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          $user = $result->fetch_assoc();

          if (password_verify($password, $user["Password"])) {
              $_SESSION["username"] = $user["Username"];
              $_SESSION["role"] = $user["Role"];
              $_SESSION["user_id"] = $user["UserID"]; // Ruaj ID-në e përdoruesit në sesion

              // Nëse përdoruesi është admin, ridrejto në dashboard.php
              if ($_SESSION["role"] === "admin") {
                  header("Location: dashboard.php");
                  exit();
              }

              // Nëse ka të dhëna të rezervimit në sesion, ruaji në databazë
              if (isset($_SESSION["reservation_data"])) {
                  $reservationData = $_SESSION["reservation_data"];
                  $name = $reservationData["name"];
                  $phone = $reservationData["phone"];
                  $date = $reservationData["date"];
                  $time = $reservationData["time"];
                  $guests = $reservationData["guests"];
                  $table = $reservationData["table"];
                  $userId = $_SESSION["user_id"];

                  // Ruaj rezervimin në databazë
                  $stmt = $conn->prepare("INSERT INTO Reservations (UserID, FullName, PhoneNumber, ReservationDate, ReservationTime, Guests, TablePreference) VALUES (?, ?, ?, ?, ?, ?, ?)");
                  $stmt->bind_param("issssis", $userId, $name, $phone, $date, $time, $guests, $table);

                  if ($stmt->execute()) {
                      $_SESSION["reservation_success"] = "Reservation made successfully!";
                      unset($_SESSION["reservation_data"]); // Fshi të dhënat e rezervimit nga sesioni
                  } else {
                      $_SESSION["reservation_error"] = "Failed to make reservation. Please try again.";
                  }

                  $stmt->close();
              }

              // Ridrejto në index.php pas login-it të suksesshëm për përdoruesit e tjerë
              header("Location: index.php");
              exit();
          } else {
              echo "<script>alert('Invalid username or password');</script>";
          }
      } else {
          echo "<script>alert('Invalid username or password');</script>";
      }
      $stmt->close();
  }
}

// Trajto formën e regjistrimit
if (isset($_POST["buttonR"])) {
    $username = trim($_POST["usernameR"]);
    $email = trim($_POST["emailR"]);
    $password = trim($_POST["passwordR"]);
    $confirmPassword = trim($_POST["confirmPasswordR"]);

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo "<script>alert('Please fill in all fields');</script>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    } elseif ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match');</script>";
    } else {
        $sql = "SELECT * FROM Users WHERE Username = ? OR Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Username or email already exists');</script>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO Users (Username, Email, Password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Registration failed, please try again');</script>";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Register</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="footer.css">
  <script defer src="script.js"></script>
</head>

<body>
    <?php include('header.php') ?>

<main>
  <div class="wrapper">
    <!-- Login Form -->
    <form class="form login-form" action="login.php" method="post" id="loginForm">
        <h1>Login</h1>
        <div class="input-box">
          <input type="text" name="usernameL" placeholder="Username" required>
        </div>
        <div class="input-box">
          <input type="password" name="passwordL" placeholder="Password" required>
        </div>
        <button type="submit" name="buttonL" class="btn">Login</button>
        <div class="register-link">
          <p>Don't have an account? <a href="#" id="switch-to-register">Register</a></p>
        </div>
    </form>

    <!-- Register Form -->
    <form class="form register-form" action="" method="post" style="display: none;" id="registerForm">
        <h1>Register</h1>
        <div class="input-box">
          <input id="registerUsername" name="usernameR" type="text" placeholder="Username" required>
        </div>
        <div class="input-box">
          <input id="email" type="email" name="emailR" placeholder="Email Address" required>
        </div>
        <div class="input-box">
          <input id="registerPass" type="password" name="passwordR" placeholder="Password" required>
        </div>
        <div class="input-box">
          <input id="registerConfirmPass" type="password" name="confirmPasswordR" placeholder="Confirm Password" required>
        </div>
        <button type="submit" name="buttonR" class="btn">Register</button>
        <div class="register-link">
          <p>Already have an account? <a href="#" id="switch-to-login">Login</a></p>
        </div>
    </form>
  </div>
</main>

  <script>
    const loginForm = document.getElementById("loginForm");
    const registerForm = document.getElementById("registerForm");
    const switchToRegister = document.getElementById("switch-to-register");
    const switchToLogin = document.getElementById("switch-to-login");

    switchToRegister.addEventListener('click', (e) => {
      e.preventDefault();
      loginForm.style.display = 'none';
      registerForm.style.display = 'block';
    });

    switchToLogin.addEventListener('click', (e) => {
      e.preventDefault();
      loginForm.style.display = 'block';
      registerForm.style.display = 'none';
    });

    document.addEventListener("DOMContentLoaded", () => {
      function validatePassword(password) {
        const minLength = 8;
        const maxLength = 20;
        const hasLetter = /[a-zA-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);

        if (password.length < minLength || password.length > maxLength) {
          return false;
        }
        if (!hasLetter || !hasNumber) {
          return false;
        }
        return true;
      }

      // Form validation
      loginForm.addEventListener("submit", (e) => {
        const username = document.querySelector('input[name="usernameL"]');
        const password = document.querySelector('input[name="passwordL"]');

        if (!username.value.trim()) {
          alert("Username is required.");
          e.preventDefault();
        }

        if (!password.value.trim() || !validatePassword(password.value)) {
          alert("Password is not valid.");
          e.preventDefault();
        }
      });

      registerForm.addEventListener("submit", (e) => {
        const username = document.getElementById("registerUsername");
        const email = document.getElementById("email");
        const password = document.getElementById("registerPass");
        const confirmPassword = document.getElementById("registerConfirmPass");

        if (!username.value.trim()) {
          alert("Username is required.");
          e.preventDefault();
        }

        if (!email.value.trim() || !validateEmail(email.value.trim())) {
          alert("Valid email address is required.");
          e.preventDefault();
        }

        if (!password.value.trim() || !validatePassword(password.value)) {
          alert("Password is not valid.");
          e.preventDefault();
        }

        if (password.value.trim() !== confirmPassword.value.trim()) {
          alert("Passwords do not match.");
          e.preventDefault();
        }
      });

      function validateEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
      }
    });
  </script>

  <?php include("footer.php"); ?>
</body>
</html>