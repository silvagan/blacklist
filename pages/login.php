<?php
session_start();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection
    include '../includes/db_connection.php';

    // Get form input data
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Validate the input (you can improve this later)
    if (empty($user) || empty($pass)) {
        $error = "Vartotojo vardas ir slaptažodis negali būti tušti!";
    } else {
        // Check if the user exists in the database
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($pass, $row['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];

                // Redirect to the main page after successful login
                header("Location: main.php");
                exit();
            } else {
                $error = "Neteisingas slaptažodis";
            }
        } else {
            $error = "Vartotojas nerastas";
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prisijungti</title>
    <!-- Link to Google Fonts for modern fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?> <!-- Navbar included here -->

    <div class="login-container">
        <div class="login-box">
            <h2>Prisijungti</h2>

            <form action="login.php" method="POST">
                <div class="input-group">
                    <label for="username">Vartotojo vardas:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Slaptažodis:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <!-- Show any error messages -->
                <?php if (isset($error)): ?>
                    <div class="error" style="color:red"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="form-footer">
                    <input type="submit" value="Prisijungti">
                    <p><a href="register.php">Neturite paskyros? Registruokitės</a></p>
                </div>
            </form>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>
