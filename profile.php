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
$user_data = mysqli_fetch_assoc($result_profile);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
  <div class="profile_container">
    <?php
    // Check if profile photo exists, display default image otherwise
    $profile_photo = $user_data['photo_profile'];
    if (!empty($profile_photo)) {
      echo "<img src='$profile_photo' alt='Profile Photo'>";
    } else {
      echo "<img src='Images/userinfo/Default_pfp.png' alt='Default Profile Photo'>";
    }
    ?>
    <h2><?php echo $user_data['nom'] . " " . $user_data['prenom']; ?></h2>
    <p>Username: <?php echo $user_data['username']; ?></p>
    <p>Email: <?php echo $user_data['email']; ?></p>
    <p>Date of Birth: <?php echo $user_data['date_naissance']; ?></p>
    <a href="edit_profile.php" class="edit-profile-btn">
    <i class="fas fa-edit"></i> Edit Profile
  </a>
    </div>



    <?php include'footer.php'; ?>
</body>
</html>