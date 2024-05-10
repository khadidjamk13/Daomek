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
<style>
    .body {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        background: linear-gradient(to bottom, #A79277, #D1BB9E, #FFF2E1);        
        top: 50%;
        left: 50%;
        background-color: #fff;
        transform: translate(-50%, -50%);
        /*font-family: 'Jost', sans-serif;*/
        z-index: 9999; /* Ensure the modals appear on top of other content */
    }

    .main {
        width: 350px;
        height: 600px;
        background: red;
        overflow: hidden;
        background: url("https://doc-08-2c-docs.googleusercontent.com/docs/securesc/68c90smiglihng9534mvqmq1946dmis5/fo0picsp1nhiucmc0l25s29respgpr4j/1631524275000/03522360960922298374/03522360960922298374/1Sx0jhdpEpnNIydS4rnN4kHSJtU1EyWka?e=view&authuser=0&nonce=gcrocepgbb17m&user=03522360960922298374&hash=tfhgbs86ka6divo3llbvp93mg4csvb38") no-repeat center/ cover;
        border-radius: 10px;
        box-shadow: 5px 20px 50px #000;
        position: relative; /* Add position relative to the main container */
    }

    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        color: gray;
        cursor: pointer;
    }

    #connexion {
        display: none;
    }

    .signup {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .sign-label {
        color: black;
        font-size: 2.3em;
        justify-content: center;
        display: flex;
        margin: 60px;
        font-weight: bold;
        cursor: pointer;
        transition: .5s ease-in-out;
    }

    .sign-input {
        width: 60%;
        height: 20px;
        background: #e0dede;
        justify-content: center;
        display: flex;
        margin: 20px auto;
        padding: 10px;
        border: none;
        outline: none;
        border-radius: 5px;
    }

    button {
        width: 60%;
        height: 40px;
        margin: 10px auto;
        justify-content: center;
        display: block;
        color: #fff;
        background: #573b8a;
        font-size: 1em;
        font-weight: bold;
        margin-top: 20px;
        outline: none;
        border: none;
        border-radius: 5px;
        transition: .2s ease-in;
        cursor: pointer;
    }

    button:hover {
        background: #6d44b8;
    }

    .login {
        height: 460px;
        background: #eee;
        border-radius: 60% / 10%;
        transform: translateY(-180px);
        transition: .8s ease-in-out;
    }

    .login label {
        color: #573b8a;
        transform: scale(.6);
    }

    #connexion:checked ~ .login {
        transform: translateY(-500px);
    }

    #connexion:checked ~ .login label {
        transform: scale(1);
    }

    #connexion:checked ~ .signup label {
        transform: scale(.6);
    }
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
