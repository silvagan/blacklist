<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include database connection
include '../includes/db_connection.php';

// Pagination settings for the URLs table
$limit = 10;  // Number of items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch blacklisted websites from the database with pagination
$sql = "SELECT * FROM websites WHERE is_blacklisted = TRUE LIMIT $limit OFFSET $offset";
$result_websites = $conn->query($sql);

// Count total blacklisted websites for pagination
$total_sql = "SELECT COUNT(*) FROM websites WHERE is_blacklisted = TRUE";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_row();
$total_websites = $total_row[0];
$total_pages = ceil($total_websites / $limit);

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM requests WHERE user_id = $user_id ORDER BY created_at DESC";
$result_requests = $conn->query($sql);

// Check for query errors
if (!$result_websites) {
    die("Error fetching blacklisted websites: " . $conn->error);
}
if (!$result_requests) {
    die("Error fetching requests data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juodasis Sąrašas</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?> <!-- Navbar included here -->
    
    <!-- User Data Box -->
    <div class="main-box" style="margin-left: 1rem; padding: 1rem;">
        <h3>Duomenys</h3>
        <p>Prisijungęs vartotojas: <strong><?php echo $_SESSION['username']; ?></strong></p>
        <p>El. paštas: <strong><?php echo $_SESSION['email']; ?></strong></p>
        <p>Administracinės teisės: <strong><?php echo $_SESSION['role']; ?></strong></p>
    </div>
    
    <!-- Main Container with URLs Table -->
    <div class="main-container" style="padding: 1rem;">
        <div class="main-box" style="padding: 1rem;">
            <h3>Juodasis URL sąrašas</h3>
            
            <?php
            // Check if there are any blacklisted websites
            if ($result_websites->num_rows > 0) {
                echo "<table class='website-table' style='width: 100%; padding: 1rem;'>
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                        </tr>";

                // Output each blacklisted website
                while ($row = $result_websites->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row['id'] . "</td>
                            <td><a href='https://" . $row['url'] . "' target='_blank'>" . $row['url'] . "</a></td>
                        </tr>";
                }

                echo "</table>";
            } else {
                echo "<p>Nėra juodajame sąraše užfiksuotų tinklapių.</p>";
            }
            ?>

            <!-- Pagination Links -->
            <div class="pagination" style="margin-top: 1rem;">
                <?php if ($page > 1): ?>
                    <a href="main.php?page=<?php echo $page - 1; ?>" class="page-link">Praeitas</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="main.php?page=<?php echo $i; ?>" class="page-link <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="main.php?page=<?php echo $page + 1; ?>" class="page-link">Kitas</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
<body>
    <!-- Actions Section -->
    <div class="main-box" style="margin-right: 1rem; display: flex; flex-direction: column; align-items: center; padding: 1rem;">
        <h3>Veiksmai</h3>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li><button onclick="window.location.href='add.php'" style="text-align: center; display: block; text-decoration: none; color: white; font-weight: bold; padding: 10px 20px; background-color: #2575fc; border: 2px solid skyblue; border-radius: 5px; cursor: pointer;">Pridėti</button></li>
        </ul>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li><button onclick="window.location.href='remove.php'" style="text-align: center; display: block; text-decoration: none; color: white; font-weight: bold; padding: 10px 20px; background-color: #2575fc; border: 2px solid skyblue; border-radius: 5px; cursor: pointer;">Šalinti</button></li>
        </ul>
        <?php if (($_SESSION['role']) == 'admin'): ?>
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li><button onclick="window.location.href='manage_requests.php'" style="text-align: center; display: block; text-decoration: none; color: white; font-weight: bold; padding: 10px 20px; background-color: #2575fc; border: 2px solid skyblue; border-radius: 5px; cursor: pointer;">Administruoti</button></li>
            </ul>
        <?php endif; ?>
        <ul style="list-style: none; padding: 0; margin: 0;">
            <li><button onclick="window.location.href='request_history.php'" style="text-align: center; display: block; text-decoration: none; color: white; font-weight: bold; padding: 10px 20px; background-color: #2575fc; border: 2px solid skyblue; border-radius: 5px; cursor: pointer;">Istorija</button></li>
        </ul>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
