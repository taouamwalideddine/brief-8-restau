<?php
require_once 'db.php';

// Restrict access to Chefs only
requireChef();

$pdo = getDatabaseConnection();
$error = '';
$success = '';

// Handle Reservation Status Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_reservation'])) {
    // CSRF Token Validation
    if (!validateCSRFToken($_POST['csrf_token'])) {
        $error = "Invalid security token";
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE Reservations r
                JOIN Menus m ON r.Menu_ID = m.ID
                SET r.Status = ?
                WHERE r.ID = ? AND m.Chef_ID = ?
            ");
            $stmt->execute([
                sanitizeInput($_POST['status']), 
                $_POST['reservation_id'], 
                $_SESSION['user_id']
            ]);
            $success = "Réservation mise à jour avec succès";
        } catch(PDOException $e) {
            $error = "Erreur de mise à jour : " . $e->getMessage();
        }
    }
}

// Fetch Pending Reservations for Current Chef
$stmt = $pdo->prepare("
    SELECT r.*, u.Name as UserName, m.Name as MenuName 
    FROM Reservations r
    JOIN Users u ON r.User_ID = u.ID
    JOIN Menus m ON r.Menu_ID = m.ID
    WHERE m.Chef_ID = ? AND r.Status = 'Pending'
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Réservations</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Réservations en Attente</h1>

        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b bg-gray-100">Client</th>
                        <th class="px-6 py-3 border-b bg-gray-100">Menu</th>
                        <th class="px-6 py-3 border-b bg-gray-100">Date</th>
                        <th class="px-6 py-3 border-b bg-gray-100">Heure</th>
                        <th class="px-6 py-3 border-b bg-gray-100">Invités</th>
                        <th class="px-6 py-3 border-b bg-gray-100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($reservation['UserName']); ?></td>
                            <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($reservation['MenuName']); ?></td>
                            <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($reservation['ReservationDate']); ?></td>
                            <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($reservation['ReservationTime']); ?></td>
                            <td class="px-6 py-4 border-b"><?php echo htmlspecialchars($reservation['NumberOfGuests']); ?></td>
                            <td class="px-6 py-4 border-b">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                    <input type="hidden" name="reservation_id" value="<?php echo $reservation['ID']; ?>">
                                    <button type="submit" name="update_reservation" value="Approved" class="text-green-500 hover:text-green-700 mr-2">
                                        Accepter
                                    </button>
                                    <button type="submit" name="update_reservation" value="Rejected" class="text-red-500 hover:text-red-700">
                                        Refuser
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>