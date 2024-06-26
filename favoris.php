<?php
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$sql_mes_annonces = "SELECT proprietes.*, GROUP_CONCAT(Images.path) AS image_paths
                     FROM proprietes 
                     LEFT JOIN favoris ON proprietes.id_p = favoris.id_p
                     LEFT JOIN Images ON proprietes.id_p = Images.id_p
                     WHERE favoris.id = $id
                     GROUP BY proprietes.id_p
                     ORDER BY proprietes.id_p DESC";
$result_announcements = mysqli_query($con, $sql_mes_annonces);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoris</title>
    <link rel="stylesheet" href="accueil.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    .announcements{
      margin-top:150px;
    }
    .announcements .announcement .content .price a {
      background: #deb887;
      color: #fff;
    } 
    .announcements .announcement .content .price a:hover {
      color: #666;
      background: #f7f7f7;
    }
  </style>
<script>
function removeFromFavorites(id_p) {
  fetch('remove_fav.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: `id_p=${id_p}`,
  })
  .then(response => response.text())
  .then(result => {
    if (result.trim() === 'success') {
      window.location.reload();
    } else {
      alert('Failed to remove favorite.');
    }
  })
  .catch(error => {
    alert('An error occurred while removing the favorite.');
  });
}



</script>
</head>
<body>
  <?php include 'navbar_loggedin.php'; ?>
  <div class="announcements">
 
    <?php
    // Check if there are announcements
    if (mysqli_num_rows($result_announcements) > 0) {
      // Loop through each announcement
      while ($row = mysqli_fetch_assoc($result_announcements)) {
        // Display announcement details
        echo '<div class="announcement">';
          // Start slider container for current announcement
          echo '<div class="slider">';
            // Navigation Arrows 
            echo '<button class="prev"><i class="fas fa-chevron-left"></i></button>';
            echo '<button class="next"><i class="fas fa-chevron-right"></i></button>';
            // Add a container for slider images
            echo '<div class="slider-container">';
              // Explode image paths into an array
              $image_paths = explode(",", $row['image_paths']);
              // Check if there are images for the current announcement
              if (!empty($image_paths)) {
                // Loop through each image path
                foreach ($image_paths as $image_path) {
                  // Display image in the slider
                  echo '<img src="' . $image_path . '" alt="Announcement Image">';
                }
              }
            // Close the slider container
            echo '</div>';
          // End slider container for current announcement
          echo '</div>';
          echo '<div class="content">';
            echo '<div class="price">';
               echo '<h3>' . $row['prix'] . ' DA</h3>';
            if (!isset($_SESSION['id'])) {
                // Si l'utilisateur n'est pas connecté, afficher un message d'alerte lorsqu'il clique sur le lien
                echo '<a href="#" onclick="alert(\'You have to log in first.\');" class=" class="fas fa-heart"><i class="fas fa-heart"></i></a>';
            } else {
                // Si l'utilisateur est connecté, rediriger vers save_to_favorites.php lorsqu'il clique sur le lien
                echo '<a href="#" onclick="removeFromFavorites(' . $row['id_p'] . '); return false;" class="fas fa-heart"></a>';
            }
            echo '</div>';

            echo '<div class="location">';
               echo '<h3>' . $row['titre'] . '</h3>';
               echo '<p>' . $row['emplacement'] . '</p>';
               echo '<div class="details">';
              // Continue displaying announcement details
              /*echo '<p>' . $row['description'] . '</p>';*/
              echo '<a href="announcement_details.php?id=' . $row['id_p'] . '" class= "btn">Voir plus de détails</a>';
            echo '</div>';
            echo '</div>';
          echo '</div>';
        echo '</div>'; // Close announcement div
      }
    } else {
      echo '<p>Aucune annonce disponible pour le moment.</p>';
    }
    ?>
  </div>
<?php include'footer.php'; ?>
</footer>
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