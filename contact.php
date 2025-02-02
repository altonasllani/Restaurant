<?php
// Aktivizo raportimin e gabimeve për të identifikuar problemet
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Përfshi skedarin për lidhjen me bazën e të dhënave
include("db_connect.php");

// Krijo një instancë të klasës DataBaza dhe krijo lidhjen me bazën e të dhënave
$db = new DataBaza();
$conn = $db->getConnection();

// Kontrollo nëse ka gabime në lidhjen me bazën e të dhënave
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Kontrollo nëse forma është dërguar me metodën POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Merr të dhënat nga forma
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['messsage']; // Sigurohu që emri i fushës të jetë i saktë

    // Përgatit query-n SQL për të futur të dhënat në tabelën contactmessages
    $sql = "INSERT INTO contactmessages (FullName, Email, Message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Kontrollo nëse query-ja është përgatitur me sukses
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Lidh parametrat me query-n
    $stmt->bind_param("sss", $name, $email, $message);

    // Ekzekuto query-n dhe shfaq mesazhin e duhur
    if ($stmt->execute()) {
        echo "<p style='color: green; text-align: center;'>Message sent successfully!</p>";
    } else {
        echo "<p style='color: red; text-align: center;'>Error: " . $stmt->error . "</p>";
    }

    // Mbyll statement-in
    $stmt->close();
}

// Mbyll lidhjen me bazën e të dhënave
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact form</title>
    
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="footer.css">  
    <script defer src="script.js"></script>
</head>
<body>
<?php include('header.php') ?>

    <main>
        <div class="contact-container">
            <form action="" method="POST" class="contact-left">
                <div class="contact-left-title">
                    <h2>Get in touch</h2>
                    <hr>
                </div>
                <input type="text" name="name" placeholder="Your Name" class="contact-inputs" required>
                <input type="email" name="email" placeholder="Your Email" class="contact-inputs" required>
                <textarea name="messsage" placeholder="Your Message" class="contact-inputs" required></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>
    </main>

    <?php include('footer.php') ?>
</body>
</html>