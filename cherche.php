<?php
session_start();
include('connection.php');

if (isset($_POST['cherche'])) {
    // Récupération des valeurs des champs du formulaire
    $type_bien = $_POST['type_b'];
    $type_operation = $_POST['type_o'];
    $wilaya = $_POST['wilaya'];
    $dimension = $_POST['dimension'];
    $prix = $_POST['prix'];

    // Construction de la requête SQL avec des requêtes préparées
    $sql = "SELECT p.*, GROUP_CONCAT(Images.path) AS image_paths 
            FROM proprietes p
            LEFT JOIN Images  ON p.id_p = Images.id_p";

    // Jointures avec les autres tables
    $sql .= " LEFT JOIN bien b ON p.id_b = b.id_b";
    $sql .= " LEFT JOIN operation o ON p.id_o = o.id_o";
    $sql .= " LEFT JOIN wilaya w ON p.id_w = w.id_w";

    // Ajout des conditions de recherche
    $sql .= " WHERE 1=1"; // Commence par une clause qui sera toujours vraie
    $params = array();

    if (!empty($type_bien)) {
        $sql .= " AND b.type_b = ?";
        $params[] = $type_bien;
    }
    if (!empty($type_operation)) {
        $sql .= " AND o.type_o = ?";
        $params[] = $type_operation;
    }
    if (!empty($wilaya)) {
        $sql .= " AND w.nom_w = ?";
        $params[] = $wilaya;
    }
    if (!empty($dimension)) {
        $sql .= " AND p.superficie = ?";
        $params[] = $dimension;
    }
    if (!empty($prix)) {
        $sql .= " AND p.prix = ?";
        $params[] = $prix;
    }
    // Exécution de la requête SQL avec des requêtes préparées
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        // Liage des paramètres
        if (!empty($params)) {
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
        }
        // Exécution de la requête
        mysqli_stmt_execute($stmt);
        // Récupération du résultat de la requête
        $result = mysqli_stmt_get_result($stmt);
        echo '<div class="announcements">';
        // Vérification s'il y a des résultats
        if (mysqli_num_rows($result) > 0) {
          // Affichage des résultats
          while($row = mysqli_fetch_assoc($result)) {
              // Début de la boîte d'annonce
              echo '<div class="announcement">';
              echo '<h2>' . $row['titre'] . '</h2>';
              // Afficher les images de chaque annonce dans un slider
              echo '<div class="slider">';
              echo '<div class="slider-container">';
              // Afficher chaque image
              $image_paths = explode(",", $row['image_paths']);
              foreach ($image_paths as $image_path) {
                  echo '<img src="' . $image_path . '" alt="Announcement Image">';
              }
              echo '</div>'; // Fin du slider-container
              // Ajoutez des boutons pour naviguer dans les images
              echo '<div class="prev">&lt;</div>';
              echo '<div class="next">&gt;</div>';
              echo '</div>'; // Fin du slider
              echo '<div class="price">';
              echo '<h3>' . $row['prix'] . ' DA</h3>';
              if (!isset($_SESSION['user_id'])) {
                  echo '<a href="#" onclick="alert(\'You have to log in first.\');" class="fas fa-heart"><i class="fas fa-heart"></i></a>';
              } else {
                  echo '<a href="save_to_favorites.php?id=' . $row['id_p'] . '" class="fas fa-heart"><i class="fas fa-heart"></i></a>';
              }
              echo '<a href="#" class="fas fa-phone"></a>';
              echo '</div>';
              echo '<div class="location">';
              echo '<h3>' . $row['titre'] . '</h3>';
              echo '<p>' . $row['emplacement'] . '</p>';
              echo '</div>';
              echo '<div class="details">';
              echo '<a href="announcement_details.php?id=' . $row['id_p'] . '" class= "btn">Voir plus de détails</a>';
              echo '</div>';
              // Fin de la boîte d'annonce
              echo '</div>';
          }
      } else {
          echo '<p>Aucune annonce disponible pour le moment.</p>';
      }
        echo '</div>'; // Fin des annonces
    } else {
        echo "Erreur de préparation de la requête SQL: " . mysqli_error($con);
    }

    // Fermeture du statement
    mysqli_stmt_close($stmt);
}
?>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="acceuil.css">
  <link rel="stylesheet" href="nav.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="accueil.css">
  <style>
  * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
/* back back back */

#titre {
  overflow: hidden;
  white-space: nowrap;
  animation: reveal 5s steps(50, end);
  position: absolute;
  left: 50%;
  margin-top: -30px;
  transform: translate(-50%, -50%);
}
#h2 {
  margin-top: 50px;
  font-weight: lighter;
  font-size: 20px;
}

.announcement {
  border: .1rem solid rgba(0, 0, 0, .2);
  box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
  border-radius: .8rem;
  overflow: hidden;
  background: #fff;
  flex: 0 1 45%; /* Ajustez la largeur de chaque annonce selon vos besoins */
  margin-bottom: 20px; /* Ajoutez un espacement entre les annonces */
}

.announcements .announcement {
  border: .1rem solid rgba(0, 0, 0, .2);
  box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
  border-radius: .8rem;
  overflow: hidden;
  background: #fff;
  flex: 0 1 20rem;
  /*width: 350px;*/
  /*margin: 20px;*/
}

.slider {
  position: relative;
  overflow: hidden;
  /*width: 100%;
  height: 290px;*/
}

.announcements .announcement .slider-container {
  overflow: hidden;
  position: relative;
  width: 100%;
  height: 22rem;
}

/* Update the .slider img selector */
.announcements .announcement .slider-container img {
  width: 100%;
  height: 100%;
  margin: 0 auto;
  transition: .2s linear;
}

.announcements .announcement .slider-container:hover img {
  transform: scale(1.1);
}

.announcements .announcement .content {
  padding: 1.2rem;
  /*margin-top: -.9rem;*/
}

.announcements .announcement .content .price{
  display: flex;
  align-items: center;
}

.announcements .announcement .content .price h3{
  color: #DEB887;
  font-size: 1.4rem; /*2rem*/
  margin-right: auto;
}

.announcements .announcement .content .price a {
  color: #666;
  font-size: 1rem;
  margin-right: .5rem;
  border-radius: .3rem;
  height: 1.6rem;
  width: 2rem;
  line-height: 1.6rem;
  text-align: center;
  background: #f7f7f7;
}

.announcements .announcement .content .price a:hover{
  background: #DEB887;
  color: #fff;
}

.announcements .announcement .content .location{
  padding: .8rem 0;
}

.announcements .announcement .content .location h3{
  font-size: 1.35rem;
  color: #333;
}

.announcements .announcement .content .location p{
  font-size: .95rem;
  color: #666;
  line-height: 1;
  padding-top: .1rem;
}

.announcements .announcement .content .details{
  padding: .2rem 0;
}

.announcements .announcement .content .details p{
  color: #666;
  font-size: 15px;
  line-height: 1;
}

.announcements .announcement .content .details .btn{
  margin-top: 10px; /* 16px */
  display: inline-block;
  padding: 8px 10px; /* 8px 16px */ 
  border-radius: 4px; /* 4px */
  color: #fff;
  background: #333;
  text-decoration: none;
  cursor: pointer;
}

.announcements .announcement .content .details .btn:hover{
  background: #f7f7f7;
  color: #333;
  border: #333 solid 2px;
  border-radius: 4px;
}

.prev,
.next {
  position: absolute;
  height: 30px;
  width: 30px;
  background: #ffffff80;
  background-size: 100%;
  border-radius: 50px;
  margin-top: auto;
  margin-bottom: auto;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 20px;
  color: #666;
  z-index: 1;
}

.prev:hover,
.next:hover{
  background:#fff;
}

.prev {
  left: 10px;
  /* Adjust as needed */
}

.next {
  right: 10px;
  /* Adjust as needed */
}

/*footer footer footer footer footer footer footer*/

.copy {
  position: static;
  display: contents;
}
.col {
  display: inline-block;
  height: 40px;
  width: 300px;
  margin-left: 100px;
  margin-top: 50px;
}
footer h3 {
  font-size: 18px;
  position: relative;
}
.footer p {
  color: white;
  margin: 15px 0 10px 0;
  font-size: 1rem;
  font-weight: 300;
}

.wave#wave1 {
  z-index: 1000;
  opacity: 1;
  bottom: 0;
  animation: animateWaves 3s linear infinite;
}

.wave#wave2 {
  z-index: 999;
  opacity: 0.7;
  bottom: 10px;
  animation: animate 3s linear infinite !important;
}

.wave#wave3 {
  z-index: 1000;
  opacity: 0.5;
  bottom: 15px;
  animation: animateWaves 3s linear infinite;
}

.wave#wave4 {
  z-index: 999;
  opacity: 0.7;
  bottom: 10px;
  animation: animate 3s linear infinite;
}

@keyframes animateWaves {
  0% {
    background-position-x: 1000px;
  }
  100% {
    background-positon-x: 0px;
  }
}

@keyframes animate {
  0% {
    background-position-x: -1000px;
  }
  100% {
    background-positon-x: 0px;
  }
}

.dic {
  background-image: url("home.png");
  background-size: cover;
  /* Ajuste la taille de l'image pour couvrir l'intégralité de l'arrière-plan */
  background-position: center;
  /* Positionne l'image au centre */
  background-repeat: no-repeat;
  /* Empêche la répétition de l'image */
  width: 90%;
  /* Largeur de l'élément body à 100% */
  height: 70vh;
  /* Hauteur de l'élément body à 100% de la hauteur de la fenêtre du navigateur */
  margin-top: -5px;
  /* Supprime les marges par défaut */
  padding: 0;
  margin-left: 60px;
  border-radius: 20px;
}
.contact{
  color:white;
}
footer h3 {
  font-size: 18px;
  position: relative;
  color: white;
}
.footer p {
  color: white;
  margin: 15px 0 10px 0;
  font-size: 1rem;
  font-weight: 300;
}
.wave {
  position: absolute;
  top: -100px;
  left: 0;
  width: 100%;
  height: 120px;
  background: url("wave-6.svg");
  background-size: 1000px 125px;
}
body {
  background-color: #eeeee7;
}
.footer {
  position: relative;
  width: 100%;
  background-color: rgb(31, 30, 30);
  display: flex;
  height: 400px;
  margin-top:150px;
}


.recherche {
  margin-top: 20px;
  margin-bottom: 20px;
}

input[type="text"] {
  border-radius: 5px;
  padding: 2px;
}
#bt {
  width: 100px;
  height: 23px;
  background-color: rgb(31, 30, 30);
  color: white;
  font-size: 16px;
  border-radius: 5px;
  border: 2px rgb(31, 30, 30) solid;
}
#marg {
  margin-bottom: 150px;
}
.recherche {
  background-color: rgb(48, 47, 47, 0.7);
  padding: 20px;
  width: 83%;
  margin-left: 100px;
  border-radius: 20px;
  color: white;
  margin-top: 30px;
  opacity: 0, 6;
}
#st {
  margin-top: 20px;
}

</style>
</head>
<body>
<script>
    document.querySelectorAll('.slider').forEach(slider => {
      const container = slider.querySelector('.slider-container');
      const prevButton = slider.querySelector('.prev');
      const nextButton = slider.querySelector('.next');
      let currentIndex = 0;

      // Function to show the current slide
      const showSlide = (index) => {
        const slides = container.querySelectorAll('img');
        slides.forEach((slide, i) => {
          slide.style.display = i === index ? 'block' : 'none';
        });
      };

      // Show the initial slide
      showSlide(currentIndex);

      // Function to toggle button visibility based on current index
      const toggleButtonVisibility = () => {
        prevButton.style.display = currentIndex === 0 ? 'none' : 'block';
        nextButton.style.display = currentIndex === container.children.length - 1 ? 'none' : 'block';
      };

      // Event listener for the previous button
      prevButton.addEventListener('click', () => {
        currentIndex = (currentIndex - 1 + container.children.length) % container.children.length;
        showSlide(currentIndex);
        toggleButtonVisibility();
      });

      // Event listener for the next button
      nextButton.addEventListener('click', () => {
        currentIndex = (currentIndex + 1) % container.children.length;
        showSlide(currentIndex);
        toggleButtonVisibility();
      });

      // Hide the next button initially if there is only one image
      toggleButtonVisibility();
    });

  </script>
</body>
</html>
