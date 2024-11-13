<?php
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection
    include '../includes/db_connection.php';

    // Get form input data
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $confirm_pass = $_POST['confirm_password'];

    // Validate the input
    if (empty($user) || empty($email) || empty($pass) || empty($confirm_pass)) {
        $error = "Visi laukai privalomi!";
    } elseif ($pass !== $confirm_pass) {
        $error = "Slaptažodžiai nesutampa!";
    } else {
        // Check if the username or email already exists
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $user, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Vartotojo vardas arba el. paštas jau užimtas!";
        } else {
            // Hash the password
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            // Insert the new user into the database
            $sql_insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $user, $email, $hashed_password);

            if ($stmt_insert->execute()) {
                // Redirect to login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                $error = "Įvyko klaida registruojant vartotoją!";
            }
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registracija</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Assuming external CSS file -->
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?> <!-- Navbar included here -->

    <div class="register-container">
        <div class="register-box">
            <h2>Registracija</h2>

            <form action="register.php" method="POST">
                <div class="input-group">
                    <label for="username">Vartotojo vardas:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="email">El. paštas:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Slaptažodis:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Patvirtinkite slaptažodį:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <!-- Show any error messages -->
                <?php if (isset($error)): ?>
                    <div class="error" style="color:red" ><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="form-footer">
                    <input type="submit" value="Registruotis">
                    <p><a href="login.php">Jau turite paskyrą? Prisijunkite</a></p>
                </div>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
