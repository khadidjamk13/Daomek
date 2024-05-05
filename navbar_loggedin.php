<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
<style>
      a{
        text-decoration:none;
        color: inherit; 
    }
    li {
        font-size:20px;
    }
    li:hover {
        color:#1f6cA3;
        
       
    }
    .right_navbar {
        margin-left: auto;
    }
    .right_navbar ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .right_navbar li {
        padding-right: 20px;
        font-size: 20px;
    }
    img {
        width:10%;
        height: 6%;
     margin-top:-25px;
     margin-left:10px;
    }
    ul {
        display: flex;
        list-style: none;
        margin-left: 320px;
    }
    ul li {
        padding-right: 100px;
        margin-top: -80px;
    }
    #content {
        margin-top: 20px;
    }
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
        font-family: 'Jost', sans-serif;
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

    #chk {
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
        background: #68684e;
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

    #chk:checked ~ .login {
        transform: translateY(-500px);
    }

    #chk:checked ~ .login label {
        transform: scale(1);
    }

    #chk:checked ~ .signup label {
        transform: scale(.6);
    }
    
    
    .btn_publier {
      appearance: none;
      background-color: transparent;
      border: 2px solid #1A1A1A;
      border-radius: 10px;
      box-sizing: border-box; /* Include padding and border in the total width and height */
      color: #3B3B3B;
      cursor: pointer;
      display: inline-block;
      font-family: Roobert, -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
      font-size: 9px;
      font-weight: 600;
      line-height: normal;
      margin: 0;
      padding: 16px 30px;
      min-height: 40px;
      min-width: 100px;
      outline: none;
      text-align: center;
      text-decoration: none;
      transition: all 300ms cubic-bezier(0.23, 1, 0.32, 1);
      user-select: none;
      -webkit-user-select: none;
      touch-action: manipulation;
      width: auto;
      will-change: transform;
    }


      
      .btn_publier:disabled {
          pointer-events: none;
      }
      
      .btn_publier:hover {
          color: #fff;
          background-color: #1A1A1A;
          box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
          transform: translateY(-2px);
      }
      
      .btn_publier:active {
          box-shadow: none;
          transform: translateY(0);
      }
      nav {
      overflow: hidden;
    }

    .dropdown {
      float: left;
      overflow: hidden;
    }
    

    .dropdown .btn {
      font-size: 16px;  
      border: none;
      outline: none;
      color: white;
      padding: 14px 16px;
      background-color: inherit;
      font-family: inherit;
      margin: 0;
    }

    nav a:hover, .dropdown:hover .btn {
      background-color: gray;
    }
      .dropdown {
      float: left;
      overflow: hidden;
    }
      .dropdown .btn {
          font-size: 16px;  
          border: none;
          outline: none;
          color: Black;
          padding: 14px 16px;
          background-color: inherit;
          font-family: inherit;
          margin: 0;
      }
      .dropdown:hover li {
          background-color: white;
      }
      .dropdown-menu {
          display: none;
          position: absolute;
          background-color: #f9f9f9;
          min-width: 160px;
          box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
          z-index: 1;
      }

      .dropdown-menu a {
      float: none;
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      text-align: left;
    }

    .dropdown-menu a:hover {
      background-color: #ddd;
    }

    .dropdown:hover .dropdown-menu {
      display: block;
    }
</style>
</head>
<body>
    <nav>
        <a href="accueil.php"><img src="logo.png" alt=""></a>
        <ul>
            <li><a href="accueil.php">Acceuil</a></li>
            <li>Annonces</li>
            <?php
                // Check if user is logged in
                if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
                    echo '<ul>';
                    echo '<li><button class="btn_publier" onclick="window.location.href=\'publier_annonce.php\';" name="ajouter_annonce" role="button">Ajouter une Annonce</button></li>';
                    echo '<li class="dropdown">';
                    echo '<button class="btn">' . $_SESSION['username'] . '</button>';
                    echo '<div class="dropdown-menu">';
                    echo '<a href="mes_annonces.php">Mes Annonces</a>';
                    echo '<a href="logout.php">Logout</a>';
                    echo '</div>';
                    echo '</li>';
                    echo '</ul>';
                }
            ?>
        </ul>
    </nav>
</body>
</html>
