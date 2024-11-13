<?php
session_start();
include '../includes/db_connection.php';  // Include your database connection

// Check if the user is an admin (only admins should view this page)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Jeigu neprisijungęs, nukreipiame į prisijungimo puslapį
    exit();
}

// Pagination settings
$limit = 10;  // Number of requests per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch all requests from the database with pagination
$sql = "SELECT r.id, r.user_id, r.website_id, r.action, r.status, r.created_at, w.url 
        FROM requests r 
        JOIN websites w ON r.website_id = w.id 
        ORDER BY r.created_at DESC 
        LIMIT $limit OFFSET $offset";  // Paginate results
$result = $conn->query($sql);

// Count total requests for pagination
$total_sql = "SELECT COUNT(*) FROM requests";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_row();
$total_requests = $total_row[0];
$total_pages = ceil($total_requests / $limit);
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request History</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>
<body id="request-history-page"> <!-- Add ID for local styling -->

    <?php include '../includes/navbar.php'; ?>  <!-- Include your navbar -->

    <div class="main-container">
        <!-- Main Box -->
        <div class="main-box">
            <h2>Užklausų istorija</h2>

            <table class="website-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vartotojo ID</th>
                        <th>Tinklapio URL</th>
                        <th>Veiksmas</th>
                        <th>Būsena</th>
                        <th>Pateikimo laikas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Output the rows of requests
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['user_id'] . "</td>";
                            echo "<td><a href='https://" . $row['url'] . "' target='_blank'>" . $row['url'] . "</a></td>";
                            echo "<td>" . (ucfirst($row['action']) == "Add" ? "Pridėti" : "Šalinti") . "</td>";
                            echo "<td>" . (ucfirst($row['status']) == "Approved" ? "Patvirtintas" : (ucfirst($row['status']) == "Pending" ? "Laukiama" : "Atmestas")) . "</td>";
                            echo "<td>" . date('Y-m-d H:i', strtotime($row['created_at'])) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Jokių užklausų nerasta.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="request_history.php?page=<?php echo $page - 1; ?>" class="page-link">Praeitas</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="request_history.php?page=<?php echo $i; ?>" class="page-link <?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="request_history.php?page=<?php echo $page + 1; ?>" class="page-link">Kitas</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
