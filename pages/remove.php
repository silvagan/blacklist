<?php
session_start();

// Patikrinti, ar vartotojas prisijungęs
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Jeigu neprisijungęs, nukreipiame į prisijungimo puslapį
    exit();
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prašymas pašalinti tinklapį iš juodojo sąrašo</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>
<body>
    <!-- Include the navigation bar -->
    <?php include '../includes/navbar.php'; ?>

    <div class="main-container">
        <!-- Form Box (main-box) -->
        <div class="main-box">
            <h2>Prašymas pašalinti tinklapį iš juodojo sąrašo</h2>
            <form action="submit_request.php" method="POST">
                <div class="input-group">
                    <label for="url">Tinklapio URL:</label>
                    <input type="text" id="url" name="url" required>
                </div>

                <input type="hidden" name="action" value="remove">  <!-- Hidden input to specify the "remove" action -->
                <div class="form-footer">
                    <!-- Buttons container -->
                    <div class="button-container">
                        <button type="button" onclick="window.history.back()" class="btn small-back-btn">Atgal</button>
                        <input type="submit" value="Pateikti prašymą">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
