<?php
    require 'connection.php';
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Redirect user to login page if not logged in
    if (!isset($_SESSION['id'])) {
        $_SESSION['msg'] = "You have to log in first";
        header('location: login.php');
        exit(); // Stop further execution
    }
    // This line should extract the 'id_p' from the URL
    $id_p = isset($_GET['id']) ? $_GET['id'] : null;
    $id_agence = $_SESSION['id'];
    // Check if id_p is not null or empty
    if (!$id_p) {
        echo "No property specified.";
        exit();
    }


    // Fetch existing images for this property
    $query_prop = "SELECT * FROM `proprietes` WHERE `id_p` = '$id_p' AND `id` = '$id_agence'";
    $result_prop = mysqli_query($con, $query_prop);

    // Check if the query executed successfully
    if (!$result_prop) {
        die("Query failed: " . mysqli_error($con));
    }

    // Check if the property exists
    $property = mysqli_fetch_assoc($result_prop);
    if (!$property) {
        echo "Property not found.";
        exit();
    }
    // Fetch existing images for this property
    $query_images = "SELECT `id`, `path` FROM `Images` WHERE `id_p` = '$id_p'";
    $result_images = mysqli_query($con, $query_images);

    $images = [];
    while ($row = mysqli_fetch_assoc($result_images)) {
        $images[] = $row;
    }

    // Handle form submission for updates
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get updated property information
        $titre = $_POST['titre'];
        $description = mysqli_real_escape_string($con, $_POST['description']); // Escape special characters
        $id_b = $_POST['id_b'];
        $id_o = $_POST['id_o'];
        $prix = $_POST['prix'];
        $piece = $_POST['piece'];
        $superficie = $_POST['superficie'];
        $id_w = $_POST['id_w'];
        $emplacement = $_POST['emplacement'];

        // Update the property information
        $update_query = "UPDATE `proprietes` SET `titre` = '$titre', `description` = '$description', `id_b` = '$id_b', `id_o` = '$id_o', `prix` = '$prix', `piece` = '$piece', `superficie` = '$superficie', `id_w` = '$id_w', `emplacement` = '$emplacement' WHERE `id_p` = '$id_p'";
        mysqli_query($con, $update_query);

        // Handle deleted images
        if (isset($_POST['deletedImages'])) {
            $deletedImages = json_decode($_POST['deletedImages'], true);
        
            foreach ($deletedImages as $imgId) {
                // Retrieve the path of the image to delete
                $query = "SELECT `path` FROM `Images` WHERE `id` = '$imgId'";
                $result = mysqli_query($con, $query);
        
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $imagePath = $row['path'];
        
                    // Delete the image file from the root folder
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the file
                    } else {
                        echo "Image not found: " . $imagePath;
                    }
        
                    // Delete the image record from the database
                    $delete_img_query = "DELETE FROM `Images` WHERE `id` = '$imgId'";
                    mysqli_query($con, $delete_img_query);
                }
            }
        }
        
        // Handle new image uploads
        if (isset($_FILES['image'])) {
            $uploadedFiles = $_FILES['image'];
            $fileCount = count($uploadedFiles['name']);

            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = $uploadedFiles['name'][$i];
                $fileTmpName = $uploadedFiles['tmp_name'][$i];
                $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                $uniqueFileName = uniqid('image_') . '.' . $fileExtension;
                $destinationPath = 'Images/' . $uniqueFileName;

                if (move_uploaded_file($fileTmpName, $destinationPath)) {
                    // Insert the new image path into `Images` table
                    $insert_image_query = "INSERT INTO `Images`(`path`, `id_p`) VALUES ('$destinationPath', '$id_p')";
                    mysqli_query($con, $insert_image_query);
                }
            }
        }

        header('location: mes_annonces.php');
        exit();
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Annonce</title>
    <link rel="stylesheet" href="form.css">
    <style>
        .upload-area {
            margin-top: 1.25rem;
            border: none;
            background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='%23ccc' stroke-width='3' stroke-dasharray='6%2c 14' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            background-color: transparent;
            padding: 3rem;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            &:hover, &:focus {
                background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' stroke='%23605941' stroke-width='3' stroke-dasharray='6%2c 14' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
            }
        }
    </style>
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            const uploadArea = document.getElementById("uploadArea");
            const fileWrapper = document.getElementById("filewrapper");
            const form = document.querySelector("form");
            let uploadedFiles = []; // Array to store new uploaded files
            let deletedImages = []; // Array to store IDs of deleted images

            uploadArea.addEventListener("click", () => {
                const fileInput = document.createElement("input");
                fileInput.type = "file";
                fileInput.name = "image[]";
                fileInput.hidden = true;
                fileInput.multiple = true;
                form.appendChild(fileInput);
                fileInput.click();
                fileInput.addEventListener("change", handleFileChange);
            });

            const handleFileChange = (e) => {
                const files = e.target.files;
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const fileName = file.name;
                    const fileType = fileName.split(".").pop();
                    fileshow(fileName, fileType);
                }
            };

            const fileshow = (fileName, fileType) => {
                const showfileboxElem = document.createElement("div");
                showfileboxElem.classList.add("showfilebox");
                const leftElem = document.createElement("div");
                leftElem.classList.add("left");
                const fileTypeElem = document.createElement("span");
                fileTypeElem.classList.add("filetype");
                fileTypeElem.innerHTML = fileType;
                leftElem.append(fileTypeElem);
                const filetitleElem = document.createElement("h3");
                filetitleElem.innerHTML = fileName;
                leftElem.append(filetitleElem);
                const rightElem = document.createElement("div");
                rightElem.classList.add("right");
                const crossElem = document.createElement("span");
                crossElem.innerHTML = "&#215;";
                rightElem.append(crossElem);
                crossElem.addEventListener("click", () => {
                    showfileboxElem.remove(); // Remove from display
                    const index = uploadedFiles.findIndex(f => f.name === fileName);
                    if (index !== -1) {
                        uploadedFiles.splice(index, 1);
                    }
                });
                showfileboxElem.append(leftElem);
                showfileboxElem.append(rightElem);
                fileWrapper.append(showfileboxElem);
            };

            const existingImages = <?php echo json_encode($images); ?>;

            existingImages.forEach(image => {
                const showfileboxElem = document.createElement("div");
                showfileboxElem.classList.add("showfilebox");
                const leftElem = document.createElement("div");
                leftElem.classList.add("left");
                const fileTypeElem = document.createElement("span");
                fileTypeElem.classList.add("filetype");
                fileTypeElem.innerHTML = "IMG";
                const filetitleElem = document.createElement("h3");
                filetitleElem.innerHTML = image['path'];
                leftElem.append(fileTypeElem);
                leftElem.append(filetitleElem);
                const rightElem = document.createElement("div");
                rightElem.classList.add("right");
                const crossElem = document.createElement("span");
                crossElem.innerHTML = "&#215;";
                crossElem.addEventListener("click", () => {
                    showfileboxElem.remove(); // Remove from display
                    deletedImages.push(image['id']); // Add image ID to deletedImages
                });

                rightElem.append(crossElem);
                showfileboxElem.append(leftElem);
                showfileboxElem.append(rightElem);
                fileWrapper.append(showfileboxElem);
            });

            const deletedImagesInput = document.getElementById("deletedImages");
            form.addEventListener("submit", () => {
                deletedImagesInput.value = JSON.stringify(deletedImages);
            });
        });
    </script>
</head>

<body>
    <?php include 'navbar_loggedin.php' ?>
    <section id="hero" class="align-items-center">
        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper">
            <h3 class="titre">Modifier Annonce</h3>
    <form method="POST" action="" enctype="multipart/form-data">
        <!-- Fields to update the property information -->
        <div class="formbold-mb-3">
            <label for="titre" class="formbold-form-label">Titre</label>
            <textarea rows="2" name="titre" id="titre" class="formbold-form-input" placeholder="Le titre de votre annonce"><?php echo htmlspecialchars($property['titre']); ?></textarea>
        </div>
        <div class="formbold-mb-3">
            <label for="description" class="formbold-form-label">Description</label>
            <textarea rows="4" name="description" id="description" class="formbold-form-input"><?php echo htmlspecialchars($property['description']); ?></textarea>
        </div>
        <div class="formbold-mb-3">
            <div>
                <label for="id_b" class="formbold-form-label"> Type de Bien </label>
                <select id="id_b" name="id_b" required>
                    <?php
                    // Fetch possible types of biens and populate options
                    $query = "SELECT `id_b`, `type_b` FROM `bien`";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = ($property['id_b'] == $row['id_b']) ? 'selected' : '';
                        echo "<option value='{$row['id_b']}' $selected>{$row['type_b']}</option>";
                    }
                    ?>
                </select>
            </div>
        <div class="formbold-mb-3">
            <div>
                <label for="id_o" class="formbold-form-label"> Type d'Operation </label>
                <select id="id_o" name="id_o" required>
                    <?php
                    // Assuming $con is your database connection
                    $query = "SELECT id_o,type_o FROM operation";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_o=$row['id_o'];
                        $type_o = $row['type_o'];
                        $selected = ($property['id_o'] == $row['id_o']) ? 'selected' : '';
                        echo "<option value='{$row['id_o']}' $selected>{$row['type_o']}</option>";                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="formbold-mb-3">
            <div>
                <label for="nom_w" class="formbold-form-label"> Wilaya </label>
                <select id="id_w" name="id_w" required>
                    <?php
                    // Assuming $con is your database connection
                    $query = "SELECT id_w,nom_w FROM wilaya";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $id_w=$row['id_w'];
                        $nom_w = $row['nom_w'];
                        $selected = ($property['id_w'] == $row['id_w']) ? 'selected' : '';
                        echo "<option value='{$row['id_w']}' $selected>{$row['nom_w']}</option>";                       }
                    ?>
                </select>
            </div>
        </div>
        <div class="formbold-mb-3">
            <div>
                <label for="prix">Prix:</label><br>
                <input type="number" id="prix" class="formbold-form-input" name="prix" step="0.01" required 
                    value="<?php echo isset($property['prix']) ? htmlspecialchars($property['prix']) : ''; ?>"> DA<br>
            </div>
        </div>
        <div class="input-container">
            <div class="formbold-mb-3">
                <label for="piece">Piece</label>
                <input type="number" name="piece" id="piece" class="formbold-form-input" placeholder="Piece" 
                    value="<?php echo isset($property['piece']) ? htmlspecialchars($property['piece']) : ''; ?>">
            </div>
            <div class="formbold-mb-3">
                <label for="superficie">Superficie</label>
                <input type="number" name="superficie" id="superficie" class="formbold-form-input" 
                    value="<?php echo isset($property['superficie']) ? htmlspecialchars($property['superficie']) : ''; ?>" 
                    placeholder="Superficie">
            </div>
        </div>
        <div class="formbold-mb-3">
            <label for="emplacement">Emplacement</label>
            <textarea rows="2" name="emplacement" id="emplacement" class="formbold-form-input" 
                placeholder="Emplacement"><?php echo isset($property['emplacement']) ? htmlspecialchars($property['emplacement']) : ''; ?></textarea>
        </div>

    </div>
        <!-- Image upload and display -->
        <div class="formbold-mb-3">
            <div class="upload-area" id="uploadArea">
                Click here to select file(s) to upload.
            </div>
            <div id="filewrapper">
                <!-- Display existing images -->
            </div>
        </div>

        <input type="hidden" id="deletedImages" name="deletedImages" value="">

        <button type="submit" class="formbold-btn">Enregistrer</button>
    </form>
    </div>
        </div>
    </section>
</body>
</html>
