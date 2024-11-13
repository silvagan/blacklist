<?php
session_start();
session_destroy(); // Ištrinama sesija
header("Location: login.php"); // Nukreipiame atgal į prisijungimo puslapį
exit();
?>
