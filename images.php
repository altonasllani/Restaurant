<?php
session_start(); // Starto sesionin

// Kontrollo nëse përdoruesi është i kyçur dhe është admin
if (!isset($_SESSION["username"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php"); // Ridrejto në login nëse nuk është admin
    exit();
}

include("db_connect.php");
$db = new DataBaza();
$db->connectToDatabase();
$conn = $db->getConnection();

// Trajto fshirjen e imazhit nëse ka një kërkesë GET me parametrin 'delete'
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $imageId = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM Images WHERE ImageID = ?");
    $stmt->bind_param("i", $imageId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Image deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete image"]);
    }

    $stmt->close();
    exit(); // Ndalo ekzekutimin e mëtejshëm të skedarit
}

// Marrja e të dhënave të imazheve
$result = $conn->query("SELECT * FROM Images");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Images</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Stilizimi i tabelës dhe butonave */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            padding: 20px;
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
            color: #555;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .btn {
            padding: 8px 12px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-delete {
            background-color: #e74c3c;
        }

        .btn-edit {
            background-color: #3498db;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 15px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-content h3 {
            margin-bottom: 20px;
            font-size: 20px;
            color: #333;
        }

        .modal-content select {
            margin-bottom: 20px;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .modal-content button {
            padding: 10px 15px;
            margin: 5px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content button[type="submit"] {
            background-color: #2ecc71;
            color: white;
        }

        .modal-content button[type="button"] {
            background-color: #e74c3c;
            color: white;
        }

        .modal-content button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <?php include("dashboard.php");?>
    <div class="container">
        <h1>Manage Images</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image Path</th>
                    <th>Page Section</th>
                    <th>Uploaded By</th>
                    <th>Uploaded At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ImageID']) ?></td>
                        <td><?= htmlspecialchars($row['ImagePath']) ?></td>
                        <td><?= htmlspecialchars($row['PageSection']) ?></td>
                        <td><?= htmlspecialchars($row['UploadedBy']) ?></td>
                        <td><?= htmlspecialchars($row['UploadedAt']) ?></td>
                        <td>
                            <!-- Delete Button -->
                            <button class="btn btn-delete" onclick="deleteImage(<?= htmlspecialchars($row['ImageID']) ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Elementi për mesazhin -->
    <div id="message"></div>

    <script>
        // Funksioni për të shfaqur mesazhin
        function showMessage(message, isSuccess = true) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = isSuccess ? 'success' : 'error'; // Shto klasën e duhur
            messageDiv.style.display = 'block';

            // Fshih mesazhin pas 3 sekondash
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        }

        // Funksioni për të fshirë një imazh
        function deleteImage(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                fetch(`manage_images.php?delete=${imageId}`, {
                    method: 'GET'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.message); // Shfaq një mesazh suksesi
                        window.location.reload(); // Rifresko faqen për të pasqyruar ndryshimet
                    } else {
                        showMessage('Error: ' + data.message, false); // Shfaq një mesazh gabimi
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred. Please try again.', false);
                });
            }
        }
    </script>
</body>
</html>