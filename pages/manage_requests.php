<?php
session_start();
include '../includes/db_connection.php'; // Include your database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Jeigu neprisijungęs, nukreipiame į prisijungimo puslapį
    exit();
}
// Check if the user is an admin (only admins should view this page)
if ($_SESSION['role'] != 'admin') {
    die("You must be an admin to view the requests.");
}

// Fetch all pending requests
$sql = "SELECT r.id, r.user_id, r.website_id, r.action, r.status, w.url 
        FROM requests r 
        JOIN websites w ON r.website_id = w.id 
        WHERE r.status = 'pending'"; // Only fetch pending requests
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prašymų Peržiūra ir Patvirtinimas</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to your stylesheet -->
    <link rel="stylesheet" href="../assets/css/navbar.css"> <!-- Link to your stylesheet -->
</head>
<body id="process-request-page"> <!-- Local ID for styling this page -->

    <?php include '../includes/navbar.php'; ?> <!-- Include navigation -->

    <div class="main-container">
        <!-- Main Box for processing requests -->
        <div class="main-box">
            <h2>Prašymų Peržiūra ir Patvirtinimas</h2>

            <?php
            // Output pending requests
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='request-card'>";
                    echo "<h3>Prašymas ID: " . $row['id'] . "</h3>";
                    echo "<p><strong>Vartotojas ID:</strong> " . $row['user_id'] . "</p>";
                    echo "<p><strong>Tinklapio URL:</strong> <a href='https://" . $row['url'] . "' target='_blank'>" . $row['url'] . "</a></p>";
                    echo "<p><strong>Veiksmas:</strong> " . (ucfirst($row['action']) == "Add" ? "Pridėti" : "Šalinti") . "</p>";
                    echo "<p><strong>Būsena:</strong> " . (ucfirst($row['status']) == "Approved" ? "Patvirtintas" : (ucfirst($row['status']) == "Pending" ? "Laukiama" : "Atmestas")) . "</p>";
                    echo "<form method='POST' action='process_request.php'>
                            <input type='hidden' name='request_id' value='" . $row['id'] . "'>
                            <input type='submit' name='approve' value='Patvirtinti' class='btn approve-btn'>
                            <input type='submit' name='reject' value='Atmesti' class='btn reject-btn'>
                          </form>";
                    echo "</div>";
                }
            } else {
                echo "<p>Neišspręstų prašymų nerasta.</p>";
            }
            ?>

        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>

<?php
$conn->close();
?>
