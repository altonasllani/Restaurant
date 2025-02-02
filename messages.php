<?php
// Aktivizo raportimin e gabimeve
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

// Variabël për të kontrolluar nëse duhet të shfaqet mesazhi
$show_message = false;

// Funksioni për të fshirë një mesazh
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM contactmessages WHERE MessageID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $show_message = true; // Shfaq mesazhin pasi të jetë fshirë mesazhi
    } else {
        echo "<p style='color: red; text-align: center;'>Error deleting message: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Merr të gjitha mesazhet nga tabela contactmessages
$sql = "SELECT * FROM contactmessages ORDER BY CreatedAt DESC";
$result = $conn->query($sql);

// Mbyll lidhjen me bazën e të dhënave
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
        /* Stilizimi specifik për faqen e mesazheve */
        .messages-container {
            padding: 20px;
            max-width: 900px;
            margin: 40px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Lejon rrëshqitjen horizontale nëse është e nevojshme */
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
            text-align: right; /* Vendos të gjithë tekstin në të djathtë */
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
    </style>
</head>
<body>
    <?php include("dashboard.php"); ?>

    <div class="messages-container">
        <h1>Messages from Contact Form</h1>
        <?php
        if ($show_message) {
            echo "<p style='color: green; text-align: center;'>Message deleted successfully!</p>";
        }
        ?>
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
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td data-label='ID'>" . htmlspecialchars($row['MessageID']) . "</td>
                                <td data-label='Name'>" . htmlspecialchars($row['FullName']) . "</td>
                                <td data-label='Email'>" . htmlspecialchars($row['Email']) . "</td>
                                <td data-label='Message'>" . htmlspecialchars($row['Message']) . "</td>
                                <td data-label='Date'>" . htmlspecialchars($row['CreatedAt']) . "</td>
                                <td data-label='Actions'>
                                    <a href='dashboard_messages.php?delete_id=" . htmlspecialchars($row['MessageID']) . "' class='btn btn-delete' onclick='return confirm(\"Are you sure you want to delete this message?\")'>Delete</a>
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
</body>
</html>