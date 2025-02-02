<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include("db_connect.php");
$db = new DataBaza();
$db->connectToDatabase();
$conn = $db->getConnection();

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Marrja e artikujve nga databaza nëse kërkohet përmes AJAX
if (isset($_GET['action']) && $_GET['action'] == 'getMenuItems') {
    $result = $conn->query("SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, i.ImagePath 
                            FROM Menu m 
                            LEFT JOIN Images i ON m.ImageID = i.ImageID");

    $menuItems = [];
    while ($row = $result->fetch_assoc()) {
        $menuItems[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($menuItems);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addMenuItem'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $category = $_POST['category'];
    
    $imagePath = null;
    if (!empty($_FILES['file']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO Menu (Name, Description, Price, Category, ImageID) VALUES (?, ?, ?, ?, ?)");
    
    $imageID = null;
    if ($imagePath) {
        $stmtImg = $conn->prepare("INSERT INTO Images (ImagePath) VALUES (?)");
        $stmtImg->bind_param("s", $imagePath);
        if ($stmtImg->execute()) {
            $imageID = $conn->insert_id;
        }
        $stmtImg->close();
    }

    $stmt->bind_param("ssdsi", $name, $description, $price, $category, $imageID);
    
    if ($stmt->execute()) {
        echo "<script>alert('Article successfully added!'); window.location.href='menuItem.php';</script>";
    } else {
        echo "<script>alert('Error adding item!');</script>";
    }
    
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $menuItemID = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM Menu WHERE MenuItemID = ?");
    $stmt->bind_param("i", $menuItemID);
    if ($stmt->execute()) {
        echo "<script>alert('Article deleted successfully!'); window.location.href='menuItem.php';</script>";
    } else {
        echo "<script>alert('Error deleting!');</script>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateMenuItem'])) {
    $menuItemID = intval($_POST['editId']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $category = $_POST['category'];

    $stmt = $conn->prepare("UPDATE Menu SET Name = ?, Description = ?, Price = ?, Category = ? WHERE MenuItemID = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $category, $menuItemID);
    if ($stmt->execute()) {
        echo "<script>alert('Changes saved successfully!'); window.location.href='menuItem.php';</script>";
    } else {
        echo "<script>alert('Error saving changes!');</script>";
    }
}

$result = $conn->query("SELECT m.MenuItemID, m.Name, m.Description, m.Price, m.Category, i.ImagePath 
                        FROM Menu m 
                        LEFT JOIN Images i ON m.ImageID = i.ImageID");
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="dashboard.css">
    <style>

        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px; 
            overflow: hidden; 
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #343a40;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            padding: 8px 12px; 
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
            display: inline-block;
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
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow-y: auto;
            max-height: 90vh;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #3498db;
            outline: none;
        }
        .form-group textarea {
            height: 80px;
            resize: vertical;
        }
        .form-group button {
            background: #27ae60;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
            transition: background 0.3s ease;
        }
        .form-group button:hover {
            background: #219150;
        }

   
        .add-button-container {
            text-align: center;
            margin: 20px 0;
        }
        .toggle-button {
            background-color: #3498db;
            color: white;
            padding: 15px; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px; 
            width: 100%; 
            margin: 20px 0;
            transition: background-color 0.3s ease;
        }
        .toggle-button:hover {
            background-color: #2980b9;
        }
        .list-section h2{
            text-align: center;
        }

        /* Responsive Design për tabelën kryesore */
        @media (max-width: 768px) {
            table {
                width: 100%; 
                box-shadow: none; 
            }

            th, td {
                display: block; 
                width: 100%;
                text-align: right; 
                padding: 8px;
                box-sizing: border-box;
            }

            th {
                text-align: center; 
                background-color: #343a40;
                color: white;
            }

            td::before {
                content: attr(data-label); 
                float: left; 
                font-weight: bold;
                text-transform: uppercase;
            }

            td {
                border-bottom: 1px solid #ddd;
            }

            tr {
                margin-bottom: 10px; 
                display: block;
                border: 1px solid #ddd; 
                border-radius: 5px; 
            }

            .actions {
                text-align: right; 
            }

            .btn {
                display: inline-block; 
                margin: 5px; 
            }

            td img {
                width: 100%; 
                height: auto; 
                max-width: 100px; 
            }
        }

        /* Responsive Design për modalin "Add New Menu Item" dhe "Edit Menu Item" */
        @media (max-width: 768px) {
            .modal-content {
                width: 90%; 
                margin: 10% auto;
                padding: 15px; 
            }

            .form-group input,
            .form-group textarea,
            .form-group select {
                width: 100%;
                padding: 10px;
                font-size: 16px;
            }

            .form-group textarea {
                height: 100px; 
            }

            .form-group button {
                width: 100%; 
                padding: 12px; 
                font-size: 16px;
            }

            .form-group label {
                font-size: 16px; 
            }

            .close {
                font-size: 24px; 
            }
        }
    </style>
    <script>
        function openAddModal() {
            document.getElementById("addModal").style.display = "block";
            document.body.classList.add("modal-open");
        }

        function closeAddModal() {
            document.getElementById("addModal").style.display = "none";
            document.body.classList.remove("modal-open");
        }

        function openEditModal(id, name, description, price, category) {
            document.getElementById("editModal").style.display = "block";
            document.body.classList.add("modal-open");
            document.getElementById("editId").value = id;
            document.getElementById("editName").value = name;
            document.getElementById("editDescription").value = description;
            document.getElementById("editPrice").value = price;
            document.getElementById("editCategory").value = category;
        }

        function closeEditModal() {
            document.getElementById("editModal").style.display = "none";
            document.body.classList.remove("modal-open");
        }
    </script>
</head>
<body>
    <?php include("dashboard.php"); ?>
    <div class="main-content">
        <div class="container">
            <div class="add-button-container">
                <button class="toggle-button" onclick="openAddModal()">Add New Item</button>
            </div>

            <div class="list-section">
                <h2>List of Menu Items</h2>
                <table>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price (€)</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Photo">
                            <?php if ($row['ImagePath']): ?>
                                <img src="<?= htmlspecialchars($row['ImagePath']) ?>" alt="Menu Image" width="50">
                            <?php else: ?>
                                No image
                            <?php endif; ?>
                        </td>
                        <td data-label="Name"><?= htmlspecialchars($row['Name']) ?></td>
                        <td data-label="Description"><?= htmlspecialchars($row['Description']) ?></td>
                        <td data-label="Price"><?= number_format($row['Price'], 2) ?></td>
                        <td data-label="Category"><?= htmlspecialchars($row['Category']) ?></td>
                        <td class="actions" data-label="Actions">
                            <button class="btn btn-edit" onclick="openEditModal('<?= $row['MenuItemID'] ?>', '<?= htmlspecialchars($row['Name']) ?>', '<?= htmlspecialchars($row['Description']) ?>', '<?= $row['Price'] ?>', '<?= $row['Category'] ?>')">Edit</button>
                            <a href="?delete=<?= $row['MenuItemID'] ?>" class="btn btn-delete" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>

        <!-- Modal për "Add New Item" -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeAddModal()">&times;</span>
                <h2>Add New Menu Item</h2>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Price (€):</label>
                        <input type="number" step="0.01" name="price" required>
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <select name="category" required>
                            <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Carbonated Drinks">Carbonated Drinks</option>
                            <option value="Energy Drinks">Energy Drinks</option>
                            <option value="Fruit Drinks">Fruit Drinks</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Upload photo:</label>
                        <input type="file" name="file">
                    </div>
                    <div class="form-group">
                        <button type="submit" name="addMenuItem">Add Article</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal për Edit -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2>Edit Menu Item</h2>
                <form method="POST">
                    <input type="hidden" id="editId" name="editId">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea id="editDescription" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Price (€):</label>
                        <input type="number" step="0.01" id="editPrice" name="price" required>
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <select id="editCategory" name="category" required>
                        <option value="Breakfast">Breakfast</option>
                            <option value="Lunch">Lunch</option>
                            <option value="Dinner">Dinner</option>
                            <option value="Dessert">Dessert</option>
                            <option value="Carbonated Drinks">Carbonated Drinks</option>
                            <option value="Energy Drinks">Energy Drinks</option>
                            <option value="Fruit Drinks">Fruit Drinks</option>
                        </select>
                    </div>
                    <button type="submit" name="updateMenuItem">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>