<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connection.php';

// Use prepared statements to prevent SQL injection
if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $stmt = $con->prepare("SELECT photo_profile, username FROM user WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $photoProfilePath = $row['photo_profile']; // Path to the profile photo
        $username = $row['username'];
    } else {
        $photoProfilePath = 'images/userinfo/Default_pfp.svg.png'; // Default image if not found
    }

    $stmt->close(); // Close the statement
} else {
    echo "User session not set. Please log in."; // Error message if session not set
    exit; // Exit if no user ID in session
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
<link rel="stylesheet" href="nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
<style>

</style>
</head>
<body>
      <header class="header" id="header">
        <nav class="nav container">
        <a href="accueil.php" class="nav__logo"><img src="Images/userinfo/logo.png" alt="logo"></a>
            <div class="nav__menu" id="nav-menu">
                <ul class="nav__list">
                    <li class="nav__item"><a href="accueil.php" class="nav__link">Acceuil</a></li>
                    <li class="nav__item"><a href="annonce.php" class="nav__link">Annonces</a></li>
                    <li class="nav__item"><a href="agences.php" class="nav__link">Agences</a></li>
                </ul>
                <div class="nav__close" id="nav-close">
                    <i class="ri-close-line"></i>
                </div>
            </div>
            <div class="nav__actions">
                <?php
                    // Check if user is logged in
                    if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
                ?>
                <button class="btn_publier" onclick="window.location.href='publier_annonce.php';" name="ajouter_annonce" role="button">Publier une Annonce</button>
                <img src="<?php echo $photoProfilePath; ?>" alt="" class="user-pic" onclick="toggleMenu()">
                <div class="sub-menu-wrap" id="SubMenu">
                    <div class="sub-menu">
                        <div class="user-info">
                            <img src="<?php echo $photoProfilePath; ?>" alt="Profile Picture">
                            <h3><?php echo $username; ?></h3>
                        </div>
                        <hr>
                        <a href="mes_annonces.php" class="sub-menu-link">
                            <p>Mes Annonces</p>
                            <span>></span>
                        </a>
                        <a href="favoris.php" class="sub-menu-link">
                            <img src="Images/userinfo/heart.png" alt="">
                            <p>Mes Favoris</p>
                            <span>></span>
                        </a>
                        <a href="profile.php" class="sub-menu-link">
                            <img src="Images/userinfo/profile.png" alt="">
                            <p>Profile</p>
                            <span>></span>
                        </a>
                        <a href="logout.php" class="sub-menu-link">
                            <img src="Images/userinfo/logout.png" alt="">
                            <p>Logout</p>
                            <span>></span>
                        </a>
                    </div>
                </div>
            </div>

                    <?php }?>   
                         
            <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-menu-line"></i>
                </div>
                    </nav>
    </header>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    const SubMenu = document.getElementById("SubMenu");
    const userPic = document.querySelector('.user-pic');
    const navMenu = document.getElementById('nav-menu'),
        navToggle = document.getElementById('nav-toggle'),
        navClose = document.getElementById('nav-close');

    function toggleMenu() {
        if (SubMenu) {
            console.log("SubMenu element found");
            SubMenu.classList.toggle("open-menu");
        } else {
            console.error("SubMenu element not found");
        }
    }

    if (userPic) {
        userPic.addEventListener('click', toggleMenu);
    } else {
        console.error("User picture element not found");
    }

    /* Menu show */
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
    });

    /* Menu hidden */
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu')
    });

    // Function to close the modal
    function closeModal() {
        document.querySelector('.body').style.display = 'none';
    }
});
</script>
</body>
</html>
