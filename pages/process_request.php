<?php
session_start();
include '../includes/db_connection.php';
include '../includes/send_email.php';  // Include the sendEmail function from send_email.php

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Jeigu neprisijungęs, nukreipiame į prisijungimo puslapį
    exit();
}

$request_id = $_POST['request_id'];

if (isset($_POST['approve'])) {
    // Fetch the request along with the website URL
    $sql = "SELECT r.id, r.user_id, r.website_id, r.action, r.status, w.url 
            FROM requests r 
            JOIN websites w ON r.website_id = w.id 
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    // Get the website URL from the websites table
    $website_url = $request['url'];

    // Check action type and update accordingly
    if ($request['action'] == 'add') {
        // Add website to blacklist
        $sql_add = "UPDATE websites SET is_blacklisted = TRUE WHERE id = ?";
        $stmt_add = $conn->prepare($sql_add);
        $stmt_add->bind_param("i", $request['website_id']);
        $stmt_add->execute();
    } elseif ($request['action'] == 'remove') {
        // Remove website from blacklist
        $sql_remove = "UPDATE websites SET is_blacklisted = FALSE WHERE id = ?";
        $stmt_remove = $conn->prepare($sql_remove);
        $stmt_remove->bind_param("i", $request['website_id']);
        $stmt_remove->execute();
    }

    // Update the request status to 'approved'
    $sql_update = "UPDATE requests SET status = 'approved' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $request_id);
    $stmt_update->execute();

    // Get user email
    $user_email = getUserEmailById($request['user_id']);  // Function to fetch user email by user ID
    

    // Prepare the email content
    $subject = "Your Request has been Approved";
    $message = "Hello,\n\nYour request to " . $request['action'] . " the website " . $website_url . " has been approved.\n\nBest regards,\nAdmin";

    // Send email using the sendEmail function
    if (sendEmail($user_email, $subject, $message)) {
        echo "Prašymas patvirtintas! Email išsiųstas.";
    } else {
        echo "Prašymas patvirtintas, bet nepavyko išsiųsti el. laiško.";
    }
} elseif (isset($_POST['reject'])) {
    // Fetch the request along with the website URL
    $sql = "SELECT r.id, r.user_id, r.website_id, r.action, r.status, w.url 
            FROM requests r 
            JOIN websites w ON r.website_id = w.id 
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $request = $result->fetch_assoc();

    // Get the website URL
    $website_url = $request['url'];

    // Update the request status to 'rejected'
    $sql_update = "UPDATE requests SET status = 'rejected' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("i", $request_id);
    $stmt_update->execute();

    // Get user email
    $user_email = getUserEmailById($request['user_id']);  // Function to fetch user email by user ID

    // Prepare the email content
    $subject = "Your Request has been Rejected";
    $message = "Hello,\n\nYour request to " . $request['action'] . " the website " . $website_url . " has been rejected.\n\nBest regards,\nAdmin";

    // Send email using the sendEmail function
    if (sendEmail($user_email, $subject, $message)) {
        echo "Prašymas atmestas! Email išsiųstas.";
    } else {
        echo "Prašymas atmestas, bet nepavyko išsiųsti el. laiško.";
    }
}



// Function to fetch user email by user ID
function getUserEmailById($user_id) {
    global $conn;
    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user['email'];
}
?>

<!DOCTYPE html>
<html lang="lt">
<body>
    <ul style="list-style: none; padding: 0; margin: 0;">
        <li><button onclick="window.location.href='manage_requests.php'" style="text-align: center; display: block; text-decoration: none; color: white; font-weight: bold; padding: 5px 10px; background-color: #2575fc; border: 2px solid skyblue; border-radius: 5px; cursor: pointer;">Atgal</button></li>
    </ul>
</body>