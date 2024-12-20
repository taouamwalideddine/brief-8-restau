<?php
include 'db.php'; 
include 'navbar.php';   
$sql = "
    SELECT menus.ID AS Menu_ID, menus.Name AS Menu_Name, menus.Description AS Menu_Description, 
           users.Name AS Chef_Name 
    FROM menus 
    JOIN users ON menus.Chef_ID = users.ID
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu List</title>
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
    <style>
        .menu-card {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            margin-bottom: 30px;
            background: #fff;
            overflow: hidden;
            text-align: center;
            transition: 0.3s;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
        }

        .menu-header {
            background-color: #f8f9fa;
            padding: 15px;
            color: #333;
        }

        .menu-body {
            padding: 20px;
        }

        .menu-card .btn {
            border-radius: 25px;
            margin: 5px;
            width: 45%;
        }

        .btn-reserve {
            background-color: #28a745;
            color: #fff;
        }

        .btn-view {
            background-color: #17a2b8;
            color: #fff;
        }
    </style>
</head>
<body>
<!-- <nav id="menu" class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.html" class="page-scroll">Main</a></li>
                <li><a href="#restaurant-menu" class="page-scroll">Menu</a></li>
                <li><a href="#team" class="page-scroll">Chef</a></li>
                <li><a href="login.php" class="page-scroll">Login</a></li>
            </ul>
        </div>
    </div>
</nav> -->

<div class="container mt-5" style="margin-top: 100px;">
    <h2 class="text-center mb-5">Our Menus</h2>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-4">
                    <div class="menu-card">
                        <div class="menu-header">
                            <h4>' . htmlspecialchars($row['Menu_Name']) . '</h4>
                            <p>By Chef: ' . htmlspecialchars($row['Chef_Name']) . '</p>
                        </div>
                        <div class="menu-body">
                            <p>' . htmlspecialchars($row['Menu_Description']) . '</p>
                            <button class="btn btn-reserve" onclick="reserveMenu(' . $row['Menu_ID'] . ')">Reserve It</button>
                            <button class="btn btn-view" onclick="viewDishes(' . $row['Menu_ID'] . ')">View Dishes</button>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<p class="text-center">No menus availablse.</p>';
        }
        ?>
    </div>
</div>

<script>
    function reserveMenu(menuId) {
        alert('Reserving menu ID: ' + menuId);
    }

    function viewDishes(menuId) {
        window.location.href = 'dishes.php?menu_id=' + menuId;
    }
</script>

</body>
</html>
<?php $conn->close(); ?>
