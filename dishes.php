<?php
include 'navbar.php';
include 'db.php';

$menu_id = isset($_GET['menu_id']) ? intval($_GET['menu_id']) : 0;

$sql = "
    SELECT dishes.Name AS Dish_Name, dishes.Description, dishes.Price
    FROM dishes
    WHERE dishes.Menu_ID = $menu_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Dishes</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        .dish-card {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            background: #fff;
            transition: 0.3s;
        }

        .dish-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }

        .dish-card h5 {
            color: #007bff;
            margin-bottom: 10px;
        }

        .price-badge {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }
    </style>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="img/apple-touch-icon.png">
<link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">

<link rel="stylesheet" type="text/css"  href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="fonts/font-awesome/css/font-awesome.css">

<link rel="stylesheet" type="text/css"  href="css/style.css">
<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Rochester" rel="stylesheet">
</head>
<body>
<div class="container mt-5"style="margin-top: 100px;">
    <h2 class="text-center mb-4">Dishes in Menu</h2>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-4">
                    <div class="dish-card">
                        <h5>' . htmlspecialchars($row['Dish_Name']) . '</h5>
                        <p>' . htmlspecialchars($row['Description']) . '</p>
                        <span class="price-badge">$' . number_format($row['Price'], 2) . '</span>
                    </div>
                </div>';
            }
        } else {
            echo '<p class="text-center">No dishes found for this menu.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
<?php $conn->close(); ?>
