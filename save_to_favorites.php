<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect the user to the login page if they are not logged in
    header('Location: login.php');
    exit;
}

// Check if property ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $property_id = $_GET['id'];

    // Connect to the database
    include 'connection.php';

    // Prepare and execute the SQL query to insert into favorites
    $user_id = $_SESSION['id'];
    $sql = "INSERT INTO favoris (id_client, id_proprietes) VALUES ($user_id, $property_id)";

    if (mysqli_query($con, $sql)) {
        // Property successfully added to favorites
        $_SESSION['success'] = "Property added to favorites successfully!";
    } else {
        // Error occurred while adding property to favorites
        $_SESSION['error'] = "Error: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);

    // Redirect back to the previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    // Property ID is not provided or invalid
    $_SESSION['error'] = "Invalid property ID!";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
