<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo 'error'; // Return error if not logged in
    exit;
}

if (isset($_POST['id_p']) && is_numeric($_POST['id_p'])) { // Expect POST parameter
    $property_id = $_POST['id_p'];

    include 'connection.php';

    $user_id = $_SESSION['id'];

    // Prepared statement to insert into 'favoris'
    $stmt = $con->prepare("INSERT INTO favoris (id, id_p) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $property_id);

    if ($stmt->execute()) { // Return 'success' on successful insertion
        echo 'success';
    } else {
        echo 'error'; // Return error on failure
    }

    $stmt->close(); // Close prepared statement
} else {
    echo 'error'; // Return error if data is missing or invalid
}
?>
