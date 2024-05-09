<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $property_id = $_GET['id'];

    include 'connection.php';

    $user_id = $_SESSION['id'];
    $sql = "INSERT INTO favoris (id, id_p) VALUES ($user_id, $property_id)";

    if (mysqli_query($con, $sql)) {
        $_SESSION['success'] = "Property added to favorites successfully!";
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($con);
    }

    mysqli_close($con);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    $_SESSION['error'] = "Invalid property ID!";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>
