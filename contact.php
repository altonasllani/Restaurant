<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db_connect.php");

$db = new DataBaza();
$conn = $db->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$show_message = false; // Kontrollon nëse duhet të shfaqet mesazhi
$message_type = ""; // Lloji i mesazhit (sukses ose gabim)
$message_text = ""; // Teksti i mesazhit

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['messsage']; 

    $sql = "INSERT INTO contactmessages (FullName, Email, Message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $show_message = true;
        $message_type = "success";
        $message_text = "Message sent successfully!";
    } else {
        $show_message = true;
        $message_type = "error";
        $message_text = "Error: " . $stmt->error;
    }

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
    <style>
        /* Stilizimi i mesazhit */
        .message {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px;
            z-index: 1000;
            color: white;
            font-weight: bold;
        }
        .success {
            color: green;
        }
        .error {
            background-color: red;
        }
    </style>
    <script>
        // Funksioni për të fshehur mesazhin pas 3 sekondash
        function hideMessage() {
            var messageElement = document.getElementById('message');
            if (messageElement) {
                setTimeout(function() {
                    messageElement.style.display = 'none';
                }, 3000); // 3000 milisekonda = 3 sekonda
            }
        }

        // Thirr funksionin kur faqja të ngarkohet
        window.onload = hideMessage;
    </script>
</head>
<body>
<?php include('header.php') ?>

    <main>
        <?php if ($show_message): ?>
            <div id="message" class="message <?php echo $message_type; ?>">
                <?php echo $message_text; ?>
            </div>
        <?php endif; ?>

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