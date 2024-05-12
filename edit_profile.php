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

// Handle form submission for updating information and profile photo
if (isset($_POST['submit'])) {
  $nom = $_POST['nom'];
  $prenom = $_POST['prenom'];
  $email = $_POST['email'];
  $date_naissance = $_POST['date_naissance'];

  // Basic validation (more can be added)
  if (empty($nom) || empty($prenom) || empty($email) || empty($date_naissance)) {
    $error_message = "Please fill in all required fields.";
  } else {

    // Profile Photo Handling (if uploaded)
    $profile_photo = "";
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
      $target_dir = "uploads/"; // Change this to your upload directory path
      $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
      $uploadOk = 1;
      $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

      // Check if image file is a real image or a fake image
      if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
          $uploadOk = 1;
        } else {
          echo "File is not an image.";
          $uploadOk = 0;
        }
      }

      // Check if file already exists
      if (file_exists($target_file)) {
        $error_message = "Sorry, file already exists.";
        $uploadOk = 0;
      }

      // Check file size
      if ($_FILES["profile_photo"]["size"] > 500000) {
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
      }

      // Allow certain file formats
      if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
      && $imageFileType != "gif" ) {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
      }

      // If everything is OK, upload the file
      if ($uploadOk == 1) {
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file);
        $profile_photo = $target_file; // Update profile photo path
      }
    }

    $sql_update = "UPDATE user SET nom='$nom', prenom='$prenom', email='$email', date_naissance='$date_naissance', photo_profile='$profile_photo' WHERE id=$id";
    $result_update = mysqli_query($con, $sql_update);

    if ($result_update) {
      header("Location: profile.php");
      exit();
    } else {
      $error_message = "An error occurred while updating your information.";
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="profile_page.css">
    <style>
      /* Existing styles (if any) */

body {
  font-family: sans-serif;
  margin: 0;
  padding: 0;
}

/* New and improved styles for edit profile page */

.edit-profile-container {
  max-width: 700px; /* Set a maximum width for responsiveness */
  margin: 200px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 10px;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
}

h2 {
  font-size: 20px;
  margin-bottom: 10px;
  text-align: center;
}

.form-group {
  margin-bottom: 15px;
  display: flex; /* Use flexbox for better layout */
  justify-content: space-between; /* Align labels and inputs horizontally */
}

.form-group label {
  width: 20%; /* Set label width */
  text-align: right; /* Right-align labels */
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="date"],
.form-group input[type="password"] {
  width: 75%; /* Set input width */
  padding: 5px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.error-message {
  background-color: #f0f0f0;
  color: #c00;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 15px;
}

.profile-photo-container {
  position: relative;
  margin-bottom: 20px;
  text-align: center;
  
}
.profile-pic {
  max-width: 40%;
  border-radius: 50%; 
  padding: 30px;
  margin: 0 auto;
}

    </style>
    <title>Edit Profile</title>
</head>
<body>
  <?php
  if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    include 'navbar_loggedin.php';
  } else {
    include 'navbar.php';
  }
  ?>
  <div class="edit-profile-container">
    <h2>Edit Profile</h2>
    <?php if (isset($error_message)) : ?>
      <div class="error-message">
        <?php echo $error_message; ?>
      </div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="profile-photo-container">
        <?php
        // Check if profile photo exists, display default image otherwise
        $profile_photo = $user_data['photo_profile'];
        if (!empty($profile_photo)) {
          echo "<img class='profile-pic' src='$profile_photo' alt='Profile Photo'>";
        } else {
          echo "<img src='Images/userinfo/Default_pfp.png' class='profile-pic 'alt='Default Profile Photo'>"; // Replace with your default image path
        }
        ?>
        <label for="profile_photo">Change Photo</label>
        <input type="file" name="profile_photo" id="profile_photo">
      </div>
      <div class="form-group">
        <label for="nom">Name:</label>
        <input type="text" name="nom" id="nom" value="<?php echo $user_data['nom']; ?>">
      </div>
      <div class="form-group">
        <label for="prenom">First Name:</label>
        <input type="text" name="prenom" id="prenom" value="<?php echo $user_data['prenom']; ?>">
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $user_data['email']; ?>">
      </div>
      <div class="form-group">
        <label for="date_naissance">Date of Birth:</label>
        <input type="date" name="date_naissance" id="date_naissance" value="<?php echo $user_data['date_naissance']; ?>">
      </div>
      <div class="form-group">
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" required>
      </div>
      <div class="form-group">
        <label for="new_password">New Password (optional):</label>
        <input type="password" name="new_password" id="new_password">
      </div>
      <div class="form-group">
        <label for="confirm_password">Confirm New Password (optional):</label>
        <input type="password" name="confirm_password" id="confirm_password">
      </div>
      <div class="form-group">
        <input type="submit" name="submit" value="Save Changes">
      </div>
    </form>
  </div>
</body>
</html>