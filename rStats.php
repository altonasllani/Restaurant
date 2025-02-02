<?php
// include("db_connect.php");
// $db = new DataBaza();
// $db->connectToDatabase();
// $conn = $db->getConnection();
include("db_connect.php");
$db = new DataBaza();
$db->connectToDatabase();
$conn = $db->getConnection();


// Fshirja e rezervimit
if (isset($_GET['delete'])) {
    $reservationId = intval($_GET['delete']);
    $conn->query("DELETE FROM Reservations WHERE ReservationID = $reservationId");
    header("Location: rStats.php");
    exit();
}

// Marrja e të dhënave të rezervimeve
$result = $conn->query("SELECT ReservationID, FullName, PhoneNumber, ReservationDate, ReservationTime, Guests, TablePreference FROM Reservations");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Statistics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
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

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
<?php include("dashboard.php");?>

    <div class="container">
        <h1>Reservation Statistics</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Table</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ReservationID']) ?></td>
                        <td><?= htmlspecialchars($row['FullName']) ?></td>
                        <td><?= htmlspecialchars($row['PhoneNumber']) ?></td>
                        <td><?= htmlspecialchars($row['ReservationDate']) ?></td>
                        <td><?= htmlspecialchars($row['ReservationTime']) ?></td>
                        <td><?= htmlspecialchars($row['Guests']) ?></td>
                        <td><?= htmlspecialchars($row['TablePreference']) ?></td>
                        <td>
                            <a href="rStats.php?delete=<?= htmlspecialchars($row['ReservationID']) ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this reservation?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>