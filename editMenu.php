<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Chef') {
    header("Location: login.php");
    exit();
}

$chef_id = $_SESSION['user_id']; 

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM menus WHERE ID = $delete_id AND Chef_ID = $chef_id");
    header("Location: chef_menus.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_name = $_POST['menu_name'];
    $menu_description = $_POST['menu_description'];
    $menu_id = isset($_POST['menu_id']) ? intval($_POST['menu_id']) : 0;

    if ($menu_id) {
        $stmt = $conn->prepare("UPDATE menus SET Name = ?, Description = ? WHERE ID = ? AND Chef_ID = ?");
        $stmt->bind_param("ssii", $menu_name, $menu_description, $menu_id, $chef_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO menus (Chef_ID, Name, Description) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $chef_id, $menu_name, $menu_description);
    }
    $stmt->execute();
    header("Location: chef_menus.php");
    exit();
}

$result = $conn->query("SELECT * FROM menus WHERE Chef_ID = $chef_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef Menus</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }
        .login-container {
            margin-top: 100px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            flex: 1; 
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        #footer {
            margin-top: auto;
        }
    </style>
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/apple-touch-icon-114x114.png">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Rochester" rel="stylesheet">
</head>
<body>
<nav id="menu" class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="index.html" class="page-scroll">Main</a></li>
                <li><a href="menu.php" class="page-scroll">Menu</a></li>
                <li><a href="#team" class="page-scroll">Chef</a></li>
                <li><a href="login.php" class="page-scroll">Login</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-5">
    <h2 class="text-center">Your Menus</h2>
    <a href="#addMenuModal" data-toggle="modal" class="btn btn-success mb-3">Add New Menu</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['Name']); ?></td>
                <td><?php echo htmlspecialchars($row['Description']); ?></td>
                <td>
                    <a href="?delete_id=<?php echo $row['ID']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    <a href="#editMenuModal<?php echo $row['ID']; ?>" data-toggle="modal" class="btn btn-primary btn-sm">Edit</a>
                    <a href="chef_dishes.php?menu_id=<?php echo $row['ID']; ?>" class="btn btn-info btn-sm">View Dishes</a>
                </td>
            </tr>

            <div class="modal fade" id="editMenuModal<?php echo $row['ID']; ?>">
                <div class="modal-dialog">
                    <form method="POST" action="chef_menus.php">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Menu</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="menu_id" value="<?php echo $row['ID']; ?>">
                                <div class="form-group">
                                    <label>Menu Name</label>
                                    <input type="text" name="menu_name" class="form-control" value="<?php echo htmlspecialchars($row['Name']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="menu_description" class="form-control" required><?php echo htmlspecialchars($row['Description']); ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="addMenuModal">
    <div class="modal-dialog">
        <form method="POST" action="chef_menus.php">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Menu</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Menu Name</label>
                        <input type="text" name="menu_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="menu_description" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Menu</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="footer">
    <div class="container text-center">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <div class="social">
                <ul>
                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="#"><i class="fa fa-youtube"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
</body>
</html>
<?php $conn->close(); ?>
