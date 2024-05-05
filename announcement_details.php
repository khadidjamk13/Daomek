<?php
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if the ID of the property is provided in the URL
if (isset($_GET['id'])) {
    // Get the property ID from the URL
    $id_p = $_GET['id'];
} else {
    echo "Aucun ID d'annonce spécifié.";
    exit; // Exit if no property ID is provided
}

// Fetch property details
$sql = "SELECT proprietes.*, Images.path AS image_path
        FROM proprietes
        LEFT JOIN Images ON proprietes.id_p = Images.id_p
        WHERE proprietes.id_p = '$id_p'";
$result = $con->query($sql);

// Check if there are results
if ($result && $result->num_rows > 0) {
    $propertyDetails = $result->fetch_assoc();

    // Fetch the agency details based on id_agence
    $agencyId = $propertyDetails['id'] ?? null;
    if ($agencyId) {
        $agencyQuery = "SELECT nom, prenom FROM user WHERE id = '$agencyId'";
        $agencyResult = $con->query($agencyQuery);

        if ($agencyResult && $agencyResult->num_rows > 0) {
            $agency = $agencyResult->fetch_assoc();
            $agencyName = $agency['nom'] . ' ' . $agency['prenom'];
        } else {
            $agencyName = 'Agence inconnue';
        }
    } else {
        $agencyName = 'Agence inconnue';
    }
  // Displaying data
  $propertyTitle = $propertyDetails['titre'] ?? 'Titre inconnu';
  $propertyLocation= $propertyDetails['Emplacement'] ?? 'Adresse inconnu';
  $propertyPrice = $propertyDetails['prix'] ?? 'Non disponible';
  $propertyDescription = $propertyDetails['description'] ?? 'Non disponible';

    // Prepare the list of image paths
    $imagePaths = [];
    do {
        if (isset($propertyDetails['image_path'])) {
            $imagePaths[] = $propertyDetails['image_path'];
        }
    } while ($propertyDetails = $result->fetch_assoc());

} else {
    echo "Aucun détail trouvé pour cette propriété.";
    exit; // Exit if no property details found
}

// Close the database connection
$con->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="annonce.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <div class="card-wrapper">
        <div class="card">
            <!-- Image Showcase -->
            <div class="property-imgs">
              <div class = "img-display">
                <div class="img-showcase">
                    <?php
                    if (!empty($imagePaths)) {
                        foreach ($imagePaths as $path) {
                            echo '<img src="' . $path . '" alt="Property Image">';
                        }
                    }
                    ?>
                </div>
              </div>
              <div class="img-select">
                <?php
                  if (count($imagePaths) > 1) {
                      echo '<div class="img-select">';
                      $counter = 1;
                      foreach ($imagePaths as $path) {
                          echo '<div class="img-item">';
                          echo '<a href="#" data-id="' . $counter . '">';
                          echo '<img src="' . $path . '" alt="Property Image ' . $counter . '">';
                          echo '</a>';
                          echo '</div>';
                          $counter++;
                      }
                      echo '</div>';
                  }
                ?>
              </div>
            </div>
            <!-- Property Details -->
            <div class="property-content">
                <h2 class="property-title"><?php echo $propertyTitle; ?></h2>
                <h5 class="prperty-location"><i class="ri-map-pin-line"></i><?php echo $propertyLocation; ?></h5>
                <a href="#" class="property-link"><?php echo $agencyName; ?></a>
                <div class="property-price">
                    <p class="new-price">Prix: <span><?php echo $propertyPrice; ?></span></p>
                </div>
                <div class="property-detail">
                    <h2>Description:</h2>
                    <p><?php echo $propertyDescription; ?></p>
                </div>
                <div class="action">
                <!-- Favorites Button -->
                <?php
                if (!isset($_SESSION['user_id'])) {
                    ?>
                    <a href="#" onclick="alert('Vous devez vous connecter d'abord.');" class="favorite-icon">
                        <i class="fas fa-heart"></i>
                    </a>
                    <?php
                } else {
                    ?>
                    <a href="save_to_favorites.php?id=<?php echo $id_p; ?>" class="favorite-icon">
                        <i class="fas fa-heart"></i>
                    </a>
                    <?php
                }
                ?>
                <!-- Contact Agency Button -->
            <a href="contact_agency.php?id=<?php echo $agencyId; ?>" class="contact-agency-button">
                Contact Agency
            </a>
            </div>
            <div class = "social-links">
            <p>Share At: </p>
            <a href = "#">
              <i class = "fab fa-facebook-f"></i>
            </a>
            <a href = "#">
              <i class = "fab fa-twitter"></i>
            </a>
            <a href = "#">
              <i class = "fab fa-instagram"></i>
            </a>
            <a href = "#">
              <i class = "fab fa-whatsapp"></i>
            </a>
          </div>
            </div>
        </div>
    </div>
    <?php include'footer.php'; ?>
    <script>
        const imgSelect = document.querySelector('.img-select');
        if (imgSelect) {
            const imgBtns = imgSelect.querySelectorAll('a');
            let imgId = 1;

            imgBtns.forEach((imgItem) => {
                imgItem.addEventListener('click', (event) => {
                    event.preventDefault();
                    imgId = imgItem.dataset.id;
                    slideImage(imgId);
                });
            });

            function slideImage(id){
                const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;
                const translateX = -((id - 1) * displayWidth);
                document.querySelector('.img-showcase').style.transform = `translateX(${translateX}px)`;
            }

            window.addEventListener('resize', slideImage.bind(null, 1)); // Fix for resize events
        }


        window.addEventListener('resize', slideImage);
    </script>
</body>
</html>
