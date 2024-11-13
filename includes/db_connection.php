<?php
// Sukuriame prisijungimą prie duomenų bazės
$servername = "localhost";
$username = "root"; // arba jūsų MySQL vartotojas
$password = "admin"; // jūsų MySQL slaptažodis
$dbname = "blacklist"; // Jūsų duomenų bazės pavadinimas

$conn = new mysqli($servername, $username, $password, $dbname);

// Patikriname, ar prisijungimas pavyko
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>