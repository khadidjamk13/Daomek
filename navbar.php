<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
<link rel="stylesheet" href="nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css">
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
                <i class="ri-user-line nav__login" id="login-btn"></i>
                <div class="nav__toggle" id="nav-toggle">
                    <i class="ri-menu-line"></i>
                </div>
            </div>
        </nav>
    </header>
    <div class="body" style="display:none;">
      <div class="main">      
          <input type="checkbox" id="connexion" aria-hidden="true">

          <div class="signup">
              <form action="signup.php" method="post">
                  <label class="sign-label" for="connexion" aria-hidden="true">Sign up</label>
                  <input class="sign-input" type="text" name="nom" placeholder="Nom de famille" required>
                  <input class="sign-input" type="text" name="prenom" placeholder="Prénom" required>
                  <input class="sign-input" type="text" name="username" placeholder="User name" required>
                  <input class="sign-input" type="email" name="email" placeholder="Email" required>
                  <input class="sign-input" type="password" name="password_1" placeholder="Password" required>
                  <input class="sign-input" type="password" name="password_2" placeholder="Confirm Password" required>
                  <input class="sign-input" type="date" name="date_naissance" required>
                  <input class="sign-input" type="submit" value="S'inscrire" name="register">
              </form>
          </div>

          <div class="login">
              <form action="login.php" method="post">
                  <label class="sign-label" for="connexion" aria-hidden="true">Login</label>
                  <input class="sign-input" type="email" name="email" placeholder="Email" required>
                  <input class="sign-input" type="password" name="password" placeholder="Password" required>
                  <input class="sign-input" type="submit" id="login" value="Login" name="login">
              </form>
        </div>
          <!-- Close button for the whole modal -->
        <span class="close-btn" onclick="closeModal()">&times;</span>
        </div>
    </div>
    <script>
        /*=============== SHOW MENU ===============*/
        const navMenu = document.getElementById('nav-menu'),
            navToggle = document.getElementById('nav-toggle'),
            navClose = document.getElementById('nav-close')

        /* Menu show */
        navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu')
        })

        /* Menu hidden */
        navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu')
        })
    // Function to display the forms when the links are clicked
    document.getElementById('login-btn').addEventListener('click', function(event) {
        event.preventDefault();
        document.querySelector('.body').style.display = 'block';
    });
     // Function to close the modal
     function closeModal() {
            document.querySelector('.body').style.display = 'none';
        }
</script>
</body>
</html>
