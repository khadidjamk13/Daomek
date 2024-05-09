<?php
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Get the ID of the favorite to remove from the POST request
if (!isset($_POST['id_f'])) {
    echo 'Error: missing ID of favorite to remove.';
    exit();
}
$id_p = $_POST['id_p'];

$id = $_SESSION['id'];

// Remove the favorite from the database
$query = "DELETE FROM favoris WHERE id_p = '$id_p' AND id = '$id'";
if (!mysqli_query($con, $query)) {
    echo 'Error: query failed.';
    exit();
}

echo 'success';

?>