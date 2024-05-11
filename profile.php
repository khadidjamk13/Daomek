<?php 
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
  header("Location: accueil.php");
  exit();
}
$id = $_SESSION['id'];
$sql_profile = "SELECT * FROM user WHERE id = $id";
$result_profile = mysqli_query($con, $sql_profile);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
<?php
  if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    include 'navbar_loggedin.php';
  } else {
    include 'navbar.php';
  }
  ?>
  




</body>
</html>