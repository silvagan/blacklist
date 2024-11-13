<?php
session_start();
include '../includes/db_connection.php';  // Susijungimas su duomenų baze

// Patikrinti, ar vartotojas prisijungęs
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Jeigu neprisijungęs, nukreipiame į prisijungimo puslapį
    exit();
}

// Gauti duomenis iš formos
$url = $_POST['url'];
$user_id = $_SESSION['user_id']; // Vartotojo ID, kuris pateikia prašymą

// Patikrinti, ar tinklapis jau yra juodajame sąraše
$sql_check_website = "SELECT * FROM websites WHERE url = ?";
$stmt_check = $conn->prepare($sql_check_website);
$stmt_check->bind_param("s", $url);
$stmt_check->execute();
$website_result = $stmt_check->get_result();

if ($website_result->num_rows == 0) {
    // Jei tinklapis neegzistuoja, sukurti naują įrašą `websites` lentelėje
    $sql_insert_website = "INSERT INTO websites (url, is_blacklisted) VALUES (?, FALSE)";
    $stmt_insert = $conn->prepare($sql_insert_website);
    $stmt_insert->bind_param("s", $url);
    $stmt_insert->execute();
    
    // Gauti svetainės ID
    $website_id = $stmt_insert->insert_id;
} else {
    // Jei tinklapis jau egzistuoja, paimti jo ID
    $website = $website_result->fetch_assoc();
    $website_id = $website['id'];
}

// Pateikti prašymą įtraukti arba pašalinti tinklapį į juodąjį sąrašą
$action = $_POST['action'];  // Since we've explicitly defined it in the form

// Patikrinti, ar vartotojas jau pateikė tą patį prašymą
$sql_check_request = "SELECT * FROM requests WHERE user_id = ? AND website_id = ? AND status = 'pending'";
$stmt_check_request = $conn->prepare($sql_check_request);
$stmt_check_request->bind_param("ii", $user_id, $website_id);
$stmt_check_request->execute();
$request_result = $stmt_check_request->get_result();

?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prašymo Būsena</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Link to your stylesheet -->
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>
<body id="request-status-page"> <!-- Local ID for styling this page -->

    <?php include '../includes/navbar.php'; ?>
    <!-- Main Container for consistent styling -->
    <div class="main-container">
        <div class="main-box">
            <?php
            if ($request_result->num_rows > 0) {
                // Display if the request already exists
                echo "<p class='message error'>Jūs jau pateikėte prašymą dėl šio tinklapio!</p>";
            } else {
                // Insert the new request if not already present
                $sql_insert_request = "INSERT INTO requests (user_id, website_id, action) VALUES (?, ?, ?)";
                $stmt_insert_request = $conn->prepare($sql_insert_request);
                $stmt_insert_request->bind_param("iis", $user_id, $website_id, $action);
                if ($stmt_insert_request->execute()) {
                    echo "<p class='message success'>Prašymas sėkmingai pateiktas!</p>";
                } else {
                    echo "<p class='message error'>Įvyko klaida pateikiant prašymą.</p>";
                }
            }
            ?>

            <!-- Back Button -->
            <ul style="list-style: none; padding: 0; margin: 0;">
                <li><button onclick="window.history.back()" class="btn back-btn">Atgal</button></li>
            </ul>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>

</body>
</html>

<?php
$conn->close();
?>
