<?php
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}

mysqli_set_charset($con, "utf8");

// Get the announcement ID
if (isset($_GET['id'])) {
    $announcementId = (int)$_GET['id']; // Ensure it's an integer
} else {
    // If no ID is provided, redirect with an error message
    header("Location: mes_annonces.php?error=Invalid ID");
    exit();
}

// Retrieve the image paths before deletion
$sql_get_images = "SELECT path FROM Images WHERE id_p = ?";
$stmt_get_images = mysqli_prepare($con, $sql_get_images);
mysqli_stmt_bind_param($stmt_get_images, 'i', $announcementId);
mysqli_stmt_execute($stmt_get_images);
$result_images = mysqli_stmt_get_result($stmt_get_images);

// Delete image files from the server
while ($row = mysqli_fetch_assoc($result_images)) {
    $imagePath = $row['path']; // Path to the image file
    if (file_exists($imagePath)) {
        if (!unlink($imagePath)) { // Delete the file
            // If file deletion fails, log an error
            error_log("Failed to delete image: " . $imagePath);
        }
    } else {
        error_log("Image file does not exist: " . $imagePath);
    }
}

// Now delete from the Images table
$sql_delete_images = "DELETE FROM Images WHERE id_p = ?";
$stmt_delete_images = mysqli_prepare($con, $sql_delete_images);
mysqli_stmt_bind_param($stmt_delete_images, 'i', $announcementId);
mysqli_stmt_execute($stmt_delete_images);

// Delete from the proprietes table
$sql_delete_proprietes = "DELETE FROM proprietes WHERE id_p = ?";
$stmt_delete_proprietes = mysqli_prepare($con, $sql_delete_proprietes);
mysqli_stmt_bind_param($stmt_delete_proprietes, 'i', $announcementId);
mysqli_stmt_execute($stmt_delete_proprietes);

// If deletion is successful, redirect with a success message
if (mysqli_stmt_affected_rows($stmt_delete_proprietes) > 0) {
    header("Location: mes_annonces.php?delete_success=1");
} else {
    // If the deletion fails, redirect with an error message
    error_log("Error deleting announcement: " . mysqli_stmt_error($stmt_delete_proprietes));
    header("Location: mes_annonces.php?error=Failed to delete announcement");
}

// Close the statements
mysqli_stmt_close($stmt_get_images);
mysqli_stmt_close($stmt_delete_images);
mysqli_stmt_close($stmt_delete_proprietes);

?>
