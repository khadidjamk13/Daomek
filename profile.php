<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
<?php
  // Start or resume the session
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // Check if the user is logged in
  if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    // User is logged in, include the navigation bar for logged-in users
    include 'navbar_loggedin.php';
  } else {
    // User is not logged in, include the navigation bar for non-logged-in users
    include 'navbar.php';
  }
  ?>
  




</body>
</html>