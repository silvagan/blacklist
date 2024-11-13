<nav class="navbar">
    <div class="navbar-container">
        <a href="main.php" class="logo">Juodieji sąrašai</a>
        <ul class="navbar-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Atsijungti</a></li>
            <?php else: ?>
                <li><a href="login.php">Prisijungti</a></li>
                <li><a href="register.php">Registruotis</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
