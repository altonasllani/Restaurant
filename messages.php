<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db_connect.php");

$db = new DataBaza();
$conn = $db->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Merr të gjitha mesazhet nga tabela contactmessages
$sql = "SELECT * FROM contactmessages ORDER BY CreatedAt DESC";
$result = $conn->query($sql);

// Trajtimi i fshirjes së mesazheve
if (isset($_GET['delete'])) {
    $messageId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM contactmessages WHERE MessageID = ?");
    $stmt->bind_param("i", $messageId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
        exit;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Stilizimi i mëparshëm i tabelës dhe elementeve */
        .messages-container {
            padding: 20px;
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        .messages-container h1 {
            text-align: center;
            color: #333;
        }

        .messages-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .messages-container table th,
        .messages-container table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: right;
        }

        .messages-container table th {
            background-color: #f4f4f4;
            color: #555;
        }

        .messages-container table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .messages-container .btn {
            padding: 4px 6px;
            text-decoration: none;
            color: white;
            border-radius: 2px;
            font-size: 11px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
        }

        .messages-container .btn-delete {
            border: none;
            color: red;
            font-weight: bold;
            font-size: 20px;
        }

        .messages-container .btn:hover {
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .messages-container {
                padding: 10px;
            }

            .messages-container table,
            .messages-container thead,
            .messages-container tbody,
            .messages-container th,
            .messages-container td,
            .messages-container tr {
                display: block;
            }

            .messages-container thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .messages-container tr {
                border: 1px solid #ccc;
                margin-bottom: 10px;
                padding: 10px;
            }

            .messages-container td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }

            .messages-container td:before {
                position: absolute;
                top: 10px;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
            }

            .messages-container .btn {
                display: block;
                margin: 5px auto;
                font-size: 15px;
            }
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            color: white;
            background-color: green;
            position: relative;
        }

        .alert .close {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: white;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <?php include("dashboard.php"); ?>

    <div class="messages-container">
        <h1>Messages from Contact Form</h1>
        <div id="message-alert" style="display: none;" class="alert">
            <span id="alert-message"></span>
            <span class="close" onclick="hideMessage()">&times;</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="messages-table-body">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr id='message-" . htmlspecialchars($row['MessageID']) . "'>
                                <td data-label='ID'>" . htmlspecialchars($row['MessageID']) . "</td>
                                <td data-label='Name'>" . htmlspecialchars($row['FullName']) . "</td>
                                <td data-label='Email'>" . htmlspecialchars($row['Email']) . "</td>
                                <td data-label='Message'>" . htmlspecialchars($row['Message']) . "</td>
                                <td data-label='Date'>" . htmlspecialchars($row['CreatedAt']) . "</td>
                                <td data-label='Actions'>
                                    <button onclick='deleteMessage(" . htmlspecialchars($row['MessageID']) . ")' class='btn btn-delete'>Delete</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No messages found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Funksioni për të fshirë mesazhin duke përdorur AJAX
        function deleteMessage(messageId) {
            if (confirm("Are you sure you want to delete this message?")) {
                fetch(`messages.php?delete=${messageId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Fshi rreshtin nga tabela
                            document.getElementById(`message-${messageId}`).remove();
                            // Shfaq mesazhin e suksesit
                            document.getElementById('alert-message').innerText = "Message deleted successfully!";
                            document.getElementById('message-alert').style.display = 'block';
                            // Fshi mesazhin pas 3 sekondash
                            setTimeout(() => {
                                document.getElementById('message-alert').style.display = 'none';
                            }, 3000);
                        } else {
                            alert("Error deleting message: " + data.error);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        // Funksioni për të fshehur mesazhin manualisht
        function hideMessage() {
            document.getElementById('message-alert').style.display = 'none';
        }
    </script>
</body>
</html>