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

// Initialize errors array
$errors = array();
$id_agence=$_SESSION['id'];
if (isset($_POST['publier_annonce'])) {
    // Retrieve form data
    $titre = $_POST['titre'];
    $description = mysqli_real_escape_string($con, $_POST['description']); // Escape special characters
    $id_b = $_POST['id_b'];
    $id_o = $_POST['id_o'];
    $prix = $_POST['prix'];
    $piece = $_POST['piece'];
    $superficie = $_POST['superficie'];
    $id_w = $_POST['id_w'];
    $emplacement = $_POST['emplacement'];

    // Insert into `proprietes` table
    $insert_prop_query = "INSERT INTO `proprietes`(`titre`, `description`, `id_b`, `id_o`, `prix`, `piece`, `superficie`, `id_w`, `emplacement`,`id_agence`) 
                          VALUES ('$titre', '$description', '$id_b', '$id_o', '$prix', '$piece', '$superficie', '$id_w', '$emplacement','$id_agence')";
    mysqli_query($con, $insert_prop_query);
    $id_p = mysqli_insert_id($con); // Get the auto-generated ID

    // Check if files were uploaded
    if (isset($_FILES['image'])) {
        $uploadedFiles = $_FILES['image'];
        $fileCount = count($uploadedFiles['name']);

        // Move uploaded files to 'Images' folder and insert paths into `Images` table
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $uploadedFiles['name'][$i];
            $fileTmpName = $uploadedFiles['tmp_name'][$i];
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid('image_') . '.' . $fileExtension; // Generate a unique filename
            $destinationPath = 'Images/' . $uniqueFileName;

            if (move_uploaded_file($fileTmpName, $destinationPath)) {
                // Insert image path into `Images` table
                $insert_image_query = "INSERT INTO `Images`(`path`, `id_p`) 
                                       VALUES ('$destinationPath', '$id_p')";
                mysqli_query($con, $insert_image_query);
            } else {
                // Handle error if file move fails
                $errors[] = "Failed to move file: $fileName";
            }
        }
    }

    if (!empty($errors)) {
        // Handle errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }else {
        // Redirect to accueil.php after successful publication
        header('location: accueil.php');
        exit(); // Stop further execution
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier Annonce</title>
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
            console.log("Page loaded");

            const uploadArea = document.getElementById("uploadArea");
            const fileWrapper = document.getElementById("filewrapper");
            const form = document.querySelector("form");
            let uploadedFiles = []; // Array to store uploaded files

            uploadArea.addEventListener("click", () => {
                console.log("Upload area clicked");
                const fileInput = document.createElement("input");
                fileInput.type = "file";
                fileInput.name = "image[]";
                fileInput.hidden = true;
                fileInput.multiple = true;
                fileInput.classList.add("imageInput");
                fileInput.addEventListener("change", handleFileChange);
                form.appendChild(fileInput); // Append file input to the form
                fileInput.click();
            });

            const handleFileChange = (e) => {
                console.log("File input changed");
                const files = e.target.files;
                console.log("Selected files:", files);
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    uploadedFiles.push(file); // Add the file to the uploadedFiles array
                    const fileName = file.name;
                    console.log("Selected file:", fileName);
                    const fileType = fileName.split(".").pop();
                    fileshow(fileName, fileType, file); // Pass file object to fileshow function
                }
            };

            const fileshow = (fileName, fileType, file) => {
                console.log("Showing file:", fileName);
                const showfileboxElem = document.createElement("div");
                showfileboxElem.classList.add("showfilebox");
                const leftElem = document.createElement("div");
                leftElem.classList.add("left");
                const fileTypeElem = document.createElement("span");
                fileTypeElem.classList.add("filetype");
                fileTypeElem.innerHTML = fileType;
                leftElem.append(fileTypeElem);
                showfileboxElem.append(leftElem);
                fileWrapper.append(showfileboxElem);
                const filetitleElem = document.createElement("h3");
                filetitleElem.innerHTML = fileName;
                leftElem.append(filetitleElem);
                showfileboxElem.append(leftElem);
                const rightElem = document.createElement("div");
                rightElem.classList.add("right");
                showfileboxElem.append(rightElem);
                const crossElem = document.createElement("span");
                crossElem.innerHTML = "&#215;";
                rightElem.append(crossElem);
                fileWrapper.append(showfileboxElem);

                // Store source path in a data attribute of the showfilebox element
                showfileboxElem.dataset.sourcePath = URL.createObjectURL(file);

                // Add the data-file-name attribute to the file input element
                const fileInput = document.querySelector('input[type="file"][name="image[]"]:last-of-type');
                if (fileInput) {
                    fileInput.setAttribute("data-file-name", fileName);
                }

                crossElem.addEventListener("click", () => {
                    console.log("File removed:", fileName);
                    fileWrapper.removeChild(showfileboxElem); // Remove the file display from the DOM

                    // Remove the file from the array of uploaded files
                    const index = uploadedFiles.findIndex(file => file.name === fileName);
                    if (index !== -1) {
                        uploadedFiles.splice(index, 1);
                    }

                    // Remove the corresponding file input element
                    const fileInput = document.querySelector(`input[name="image[]"][data-file-name="${fileName}"]`);
                    if (fileInput) {
                        fileInput.parentNode.removeChild(fileInput);
                    }
                });
            };

            // Submit form handler
            form.addEventListener("submit", () => {
                // Remove elements corresponding to removed images
                const removedFileNames = JSON.parse(document.getElementById("removedFiles").value);
                removedFileNames.forEach(fileName => {
                    const elementsToRemove = document.querySelectorAll(`.showfilebox h3:contains(${fileName})`);
                    elementsToRemove.forEach(elem => {
                        elem.closest('.showfilebox').remove(); // Remove the parent element
                    });
                });

                // Update the value of the hidden input with the removed file names
                document.getElementById("removedFiles").value = JSON.stringify(removedFiles);
            });
        });
    </script>


</head>

<body>
    <?php include 'navbar_loggedin.php' ?>
    <section id="hero" class="align-items-center">
        <div class="formbold-main-wrapper">
            <div class="formbold-form-wrapper">
                <h3 class="titre">Publier une Annonce</h3>
                <form method="POST" action="publier_annonce.php" class="container" enctype="multipart/form-data">
                    <input type="hidden" id="uploadedFiles" name="uploadedFiles" value="">
                    <div class="formbold-mb-3">
                        <label for="titre" class="formbold-form-label">Titre</label>
                        <textarea rows="2" name="titre" id="titre" class="formbold-form-input" placeholder="Le titre de votre annonce"></textarea>
                    </div>
                    <div class="formbold-mb-3">
                        <label for="description" class="formbold-form-label">
                            Description
                        </label>
                        <textarea rows="4" name="description" id="description" class="formbold-form-input" placeholder="Description"></textarea>
                    </div>
                    <div class="formbold-mb-3">
                        <div>
                            <label for="id_b" class="formbold-form-label"> Type de Bien </label>
                            <select id="id_b" name="id_b" required>
                                <option value="">Bien</option>
                                <?php
                                // Assuming $con is your database connection
                                $query = "SELECT id_b,type_b FROM bien";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id_b=$row['id_b'];
                                    $type_b = $row['type_b'];
                                    echo "<option value='$id_b'>$type_b</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="formbold-mb-3">
                        <div>
                            <label for="id_o" class="formbold-form-label"> Type d'Operation </label>
                            <select id="id_o" name="id_o" required>
                                <option value="">Operation</option>
                                <?php
                                // Assuming $con is your database connection
                                $query = "SELECT id_o,type_o FROM operation";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id_o=$row['id_o'];
                                    $type_o = $row['type_o'];
                                    echo "<option value='$id_o'>$type_o</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="formbold-mb-3">
                        <div>
                            <label for="nom_w" class="formbold-form-label"> Wilaya </label>
                            <select id="id_w" name="id_w" required>
                                <option value="">Wilaya</option>
                                <?php
                                // Assuming $con is your database connection
                                $query = "SELECT id_w,nom_w FROM wilaya";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id_w=$row['id_w'];
                                    $nom_w = $row['nom_w'];
                                    echo "<option value='$id_w'>$nom_w</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="formbold-mb-3">
                        <div>
                            <label for="prix" class="formbold-form-label">Prix:</label><br>
                            <input type="number" id="prix" class="formbold-form-input" name="prix" step="0.01" required> DA<br>
                        </div>
                    </div>
                    <div class="input-container">
                        <div class="formbold-mb-3">
                            <label for="piece" class="formbold-form-label">
                                Piece
                            </label>
                            <input type="number" name="piece" id="piece" class="formbold-form-input" placeholder="Piece">
                        </div>
                        <div class="formbold-mb-3">
                            <label for="superficie" class="formbold-form-label">
                                Superficie
                            </label>
                            <input type="number" name="superficie" id="superficie" class="formbold-form-input" placeholder="Superficie">
                        </div>
                    </div>
                    <br>
                    <div class="formbold-mb-3">
                        <label for="emplacement" class="formbold-form-label">
                            Emplacement
                        </label>
                        <textarea rows="2" name="emplacement" id="emplacement" class="formbold-form-input" placeholder="Emplacement"></textarea>
                    </div>
                    <div class="formbold-mb-3">
                        <div class="box">
                            <div class="input-bx">
                                <h2 class="model-title">Upload Image</h2>
                                <div class="upload-area" id="uploadArea">
                                    <span class="upload-area-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="340.531" height="419.116" viewBox="0 0 340.531 419.116">
                                            <g id="files-new" clip-path="url(#clip-files-new)">
                                                <path id="Union_2" data-name="Union 2" d="M-2904.708-8.885A39.292,39.292,0,0,1-2944-48.177V-388.708A39.292,39.292,0,0,1-2904.708-428h209.558a13.1,13.1,0,0,1,9.3,3.8l78.584,78.584a13.1,13.1,0,0,1,3.8,9.3V-48.177a39.292,39.292,0,0,1-39.292,39.292Zm-13.1-379.823V-48.177a13.1,13.1,0,0,0,13.1,13.1h261.947a13.1,13.1,0,0,0,13.1-13.1V-323.221h-52.39a26.2,26.2,0,0,1-26.194-26.195v-52.39h-196.46A13.1,13.1,0,0,0-2917.805-388.708Zm146.5,241.621a14.269,14.269,0,0,1-7.883-12.758v-19.113h-68.841c-7.869,0-7.87-47.619,0-47.619h68.842v-18.8a14.271,14.271,0,0,1,7.882-12.758,14.239,14.239,0,0,1,14.925,1.354l57.019,42.764c.242.185.328.485.555.671a13.9,13.9,0,0,1,2.751,3.292,14.57,14.57,0,0,1,.984,1.454,14.114,14.114,0,0,1,1.411,5.987,14.006,14.006,0,0,1-1.411,5.973,14.653,14.653,0,0,1-.984,1.468,13.9,13.9,0,0,1-2.751,3.293c-.228.2-.313.485-.555.671l-57.019,42.764a14.26,14.26,0,0,1-8.558,2.847A14.326,14.326,0,0,1-2771.3-147.087Z" transform="translate(2944 428)" fill="var(--c-action-primary)" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="upload-area-title">Click here to select file(s) to upload.</span>
                                </div>
                                <div id="fileInputs"></div>
                            </div>
                        </div>
                        <div id="filewrapper">
                            <h3 class="uploaded">Uploaded Images</h3>
                            <!-- Images will be displayed here -->
                        </div>
                    </div>
                    <input type="hidden" id="removedFiles" name="removedFiles" value="">
                    <button type="submit" class="formbold-btn" name="publier_annonce">Publier</button>
                </form>
            </div>
        </div>
    </section>
</body>

</html>