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

// Trajto përditësimin e rolit nëse ka një kërkesë POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId'], $_POST['role'])) {
    $userId = intval($_POST['userId']);
    $newRole = $conn->real_escape_string($_POST['role']);

    $stmt = $conn->prepare("UPDATE users SET Role = ? WHERE UserID = ?");
    $stmt->bind_param("si", $newRole, $userId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Role updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update role"]);
    }

    $stmt->close();
    exit(); // Ndalo ekzekutimin e mëtejshëm të skedarit
}

// Trajto fshirjen e përdoruesit nëse ka një kërkesë GET me parametrin 'delete'
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete'])) {
    $userId = intval($_GET['delete']);

    $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "User deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete user"]);
    }

    $stmt->close();
    exit(); // Ndalo ekzekutimin e mëtejshëm të skedarit
}

// Marrja e të dhënave të përdoruesve
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        h1 {
            text-align: center;
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
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
        <h1>Manage Users</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['UserID']) ?></td>
                        <td><?= htmlspecialchars($row['Username']) ?></td>
                        <td><?= htmlspecialchars($row['Email']) ?></td>
                        <td><?= htmlspecialchars($row['Role']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <button class="btn btn-edit" onclick="showModal(<?= htmlspecialchars($row['UserID']) ?>, '<?= htmlspecialchars($row['Role']) ?>')">Edit</button>

                            <!-- Delete Button -->
                            <button class="btn btn-delete" onclick="deleteUser(<?= htmlspecialchars($row['UserID']) ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Editing -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <h3>Edit Role</h3>
            <form id="editRoleForm" method="POST">
                <input type="hidden" name="userId" id="userId">
                <select name="role" id="roleSelect">
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                </select>
                <button type="submit" name="updateRole">Save</button>
                <button type="button" onclick="hideModal()">Cancel</button>
            </form>
        </div>
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

        // Funksioni për të dërguar të dhënat e formës në mënyrë asinkrone
        document.getElementById('editRoleForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Ndalo rifreskimin e faqes

            const formData = new FormData(this);

            fetch('users.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message); // Shfaq një mesazh suksesi
                    hideModal(); // Mbyll modal-in
                    window.location.reload(); // Rifresko faqen për të pasqyruar ndryshimet
                } else {
                    showMessage('Error: ' + data.message, false); // Shfaq një mesazh gabimi
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', false);
            });
        });

        // Funksioni për të fshirë një përdorues
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(`users.php?delete=${userId}`, {
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

        function showModal(userId, currentRole) {
            document.getElementById('userId').value = userId;
            document.getElementById('roleSelect').value = currentRole;
            document.getElementById('editModal').style.display = 'flex';
        }

        function hideModal() {
            document.getElementById('editModal').style.display = 'none';
        }
    </script>
</body>
</html>