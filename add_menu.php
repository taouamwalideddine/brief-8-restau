<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Chef') {
    header("Location: editMenu.php");
    exit();
}

$chef_id = $_SESSION['user_id']; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_name = trim($_POST['menu_name']);
    $menu_description = trim($_POST['menu_description']);

    if (!empty($menu_name) && !empty($menu_description)) {
        $stmt = $conn->prepare("INSERT INTO menus (Chef_ID, Name, Description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $chef_id, $menu_name, $menu_description);
        

        if ($stmt->affected_rows > 0) {
            header("Location: editMenu.php?success=1");
            exit();
        } else {
            $error_message = "Error adding the menu. Please try again.";
        }
    } else {
        $error_message = "Menu name and description cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            background-color: white;
        }
        .card-header {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Add New Menu</div>
        <div class="card-body">
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="add_menu.php">
                <div class="form-group">
                    <label for="menu_name">Menu Name</label>
                    <input type="text" name="menu_name" id="menu_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="menu_description">Description</label>
                    <textarea name="menu_description" id="menu_description" class="form-control" rows="5" required></textarea>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Add Menu</button>
                    <a href="chef_menus.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
