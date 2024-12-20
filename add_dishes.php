<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Chef') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['menu_id']) || empty($_GET['menu_id'])) {
    die("No menu selected.");
}

$menu_id = intval($_GET['menu_id']);
$chef_id = $_SESSION['user_id'];

$menu_check = $conn->prepare("SELECT * FROM menus WHERE ID = ? AND Chef_ID = ?");
$menu_check->bind_param("ii", $menu_id, $chef_id);
$menu_check->execute();
$menu_result = $menu_check->get_result();

if ($menu_result->num_rows == 0) {
    die("Invalid menu or you do not have permission to add dishes to this menu.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dish_name = $_POST['dish_name'];
    $dish_description = $_POST['dish_description'];
    $dish_price = $_POST['dish_price'];

    if (!empty($dish_name) && !empty($dish_description) && !empty($dish_price) && is_numeric($dish_price)) {
        $stmt = $conn->prepare("INSERT INTO dishes (Menu_ID, Name, Description, Price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issd", $menu_id, $dish_name, $dish_description, $dish_price);
        $stmt->execute();

        header("Location: dishes.php?menu_id=" . $menu_id);
        exit();
    } else {
        $error = "All fields are required, and price must be a valid number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Dishes</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 500px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="text-center">Add a Dish to the Menu</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="dish_name">Dish Name</label>
                <input type="text" class="form-control" id="dish_name" name="dish_name" required>
            </div>
            <div class="form-group">
                <label for="dish_description">Dish Description</label>
                <textarea class="form-control" id="dish_description" name="dish_description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="dish_price">Dish Price</label>
                <input type="number" step="0.01" class="form-control" id="dish_price" name="dish_price" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Add Dish</button>
            <a href="editMenu.php" class="btn btn-secondary btn-block">Back to Menus</a>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
