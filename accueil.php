<?php
session_start();
include 'connection.php';

mysqli_set_charset($con, "utf8");

$sql_announcements = "SELECT proprietes.*, GROUP_CONCAT(Images.path) AS image_paths
                      FROM proprietes 
                      LEFT JOIN Images ON proprietes.id_p = Images.id_p
                      GROUP BY proprietes.id_p 
                      ORDER BY proprietes.id_p DESC
                      LIMIT 9";
$result_announcements = mysqli_query($con, $sql_announcements);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="acceuil.css">

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
  <div class="dic">
    <div class="welcome">
      <h1 align="center" class="title">Bienvenus à Propriété Parfait</h1>
      <h2 align="center" id="st">votre guide qui vous accompagne pour trouver votre maison</h2>
    </div>
  </div>

  <div class="recherche">

    <form action="cherche.php" method="post">

      <label for="">À la recherche de</label>

      <select name="type_b" id="type_b">

        <option value=""> </option>

        <?php

        $sql_bien = "SELECT type_b FROM bien";

        $result_bien = mysqli_query($con, $sql_bien);

        if ($result_bien->num_rows > 0) {

          while ($row = $result_bien->fetch_assoc()) {

            echo "<option value='" . $row["type_b"] . "'>" . $row["type_b"] . "</option>";
          }
        } else {

          echo "0 résultats";
        }

        ?>

      </select>



      <label for="">Type de l'opération</label>

      <select name="type_o" id="type_o">

        <option value=""> </option>

        <?php

        $sql_op = "SELECT type_o FROM operation";

        $result_op = mysqli_query($con, $sql_op);

        if ($result_op->num_rows > 0) {

          while ($row = $result_op->fetch_assoc()) {

            echo "<option value='" . $row["type_o"] . "'>" . $row["type_o"] . "</option>";
          }
        } else {

          echo "0 résultats";
        } ?>

      </select>

      <label for="">wilaya</label>

      <select name="wilaya" id="wilaya">

        <option value=""> </option>

        <?php

        $sql_wilayas = "SELECT nom_w FROM wilaya";

        $result_wilayas = mysqli_query($con, $sql_wilayas);

        if ($result_wilayas->num_rows > 0) {

          while ($row = $result_wilayas->fetch_assoc()) {

            echo "<option value='" . $row["nom_w"] . "'>" . $row["nom_w"] . "</option>";
          }
        } else {

          echo "0 résultats";
        } ?>

      </select>

      <label for="">Dimension</label>

      <input type="text" id="dimension" name="dimension">

      <label for="">prix</label>

      <input type="text" id="prix" name="prix">

      <input type="submit" name="cherche" value="rechercher">

    </form>
  </div>

  <div class="announcements-slider">
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
          if (!isset($_SESSION['user_id'])) {

            // Si l'utilisateur n'est pas connecté, afficher un message d'alerte lorsqu'il clique sur le lien

            echo '<a href="#" onclick="alert(\'You have to log in first.\');" class=" class="fas fa-heart"><i class="fas fa-heart"></i></a>';
          } else {

            // Si l'utilisateur est connecté, rediriger vers save_to_favorites.php lorsqu'il clique sur le lien

            echo '<a href="save_to_favorites.php?id=' . $row['id_p'] . '" class="fas fa-heart"><i class="fas fa-heart"></i></a>';
          }

          echo '<a href="#" class="fas fa-phone"></a>';
          echo '</div>';

          echo '<div class="location">';
          echo '<h3>' . $row['titre'] . '</h3>';
          echo '<p>' . $row['emplacement'] . '</p>';
          echo '</div>';

          echo '<div class="details">';
          // Continue displaying announcement details
          /*echo '<p>' . $row['description'] . '</p>';*/
          echo '<a href="announcement_details.php?id=' . $row['id_p'] . '" class= "btn">Voir plus de détails</a>';
          echo '</div>';

          echo '</div>';



          echo '</div>'; // Close announcement div
        }
      } else {
        echo '<p>Aucune annonce disponible pour le moment.</p>';
      }
      ?>
    </div>

    <button class="prevv"><i class="fas fa-chevron-left"></i></button>
    <button class="nextt"><i class="fas fa-chevron-right"></i></button>

    <!-- Pagination dots will be dynamically added here -->
    <div class="pagination"></div>

  </div>


  <?php include 'footer.php' ?>
  <script>
  document.addEventListener('DOMContentLoaded', function () {
  const slider = document.querySelector('.announcements-slider');
  const announcements = document.querySelector('.announcements');
  const prevButton = slider.querySelector('.prevv');
  const nextButton = slider.querySelector('.nextt');
  const pagination = slider.querySelector('.pagination');

  let currentIndex = 0;
  const announcementWidth = announcements.firstElementChild.offsetWidth + 10; // Width of each announcement card + margin-right
  const announcementCount = announcements.children.length;
  const pageCount = Math.ceil(announcementCount / 3);

  // Show the initial set of announcements (first 3)
  showAnnouncements();
  createPaginationDots();

  // Function to show the current set of announcements
  function showAnnouncements() {
    for (let i = 0; i < announcementCount; i++) {
      announcements.children[i].style.display = i >= currentIndex && i < currentIndex + 3 ? 'block' : 'none';
    }
    toggleButtonVisibility();
    updatePagination();
  }

  // Function to toggle button visibility based on current index
  function toggleButtonVisibility() {
    prevButton.style.display = currentIndex === 0 ? 'none' : 'block';
    nextButton.style.display = currentIndex + 3 >= announcementCount ? 'none' : 'block';
  }

  // Function to create pagination dots
  function createPaginationDots() {
    for (let i = 0; i < pageCount; i++) {
      const dot = document.createElement('span');
      dot.classList.add('dot');
      if (i === 0) {
        dot.classList.add('active');
      }
      pagination.appendChild(dot);
    }
  }

  // Function to update pagination dots based on the current index
  function updatePagination() {
    const activeDotIndex = Math.floor(currentIndex / 3);
    const dots = pagination.querySelectorAll('.dot');
    dots.forEach((dot, index) => {
      dot.classList.toggle('active', index === activeDotIndex);
    });
  }

  // Event listener for the previous button
  prevButton.addEventListener('click', () => {
    if (currentIndex > 0) {
      currentIndex -= 3; // Move back by 3
      showAnnouncements();
    }
  });

  // Event listener for the next button
  nextButton.addEventListener('click', () => {
    if (currentIndex + 3 < announcementCount) {
      currentIndex += 3; // Move forward by 3
      showAnnouncements();
    }
  });

  // Event listeners for pagination dots
  pagination.addEventListener('click', (event) => {
    if (event.target.classList.contains('dot')) {
      const dotIndex = Array.from(pagination.children).indexOf(event.target);
      currentIndex = dotIndex * 3;
      showAnnouncements();
    }
  });
});
</script>

</body>

</html>
