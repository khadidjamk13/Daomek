<?php
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    echo 'error'; // If not logged in
    exit();
}

// Ensure id_p is set in POST request
if (!isset($_POST['id_p'])) {
    echo 'error'; // If missing required parameter
    exit();
}

$id_p = $_POST['id_p']; // Correct assignment
$id = $_SESSION['id']; // User ID from session

// Prepared statement to delete the favorite
$stmt = $con->prepare("DELETE FROM favoris WHERE id_p = ? AND id = ?");
$stmt->bind_param("ii", $id_p, $id);

if ($stmt->execute()) {
    echo 'success'; // Return success on successful deletion
} else {
    echo 'error'; // Return error on failure
}

$stmt->close(); // Close the prepared statement
?>