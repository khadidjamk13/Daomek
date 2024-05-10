<?php
session_start();
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
$errors   = array();


// Call the register() function if the register button is clicked
if (isset($_POST['register'])) {
    register();
}

// REGISTER USER
function register()
{
    global $con, $errors;

    // Check if form data is set
    if (isset($_POST['nom'])) {
        $nom = $_POST['nom'];
    } else {
        $nom = "";
    }
    if (isset($_POST['prenom'])) {
        $prenom = $_POST['prenom'];
    } else {
        $prenom = "";
    }
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    } else {
        $username = "";
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = "";
    }

    if (isset($_POST['date_naissance'])) {
        $date_naissance = $_POST['date_naissance'];
    } else {
        $date_naissance = "";
    }
    if (isset($_POST['password_1'])) {
        $password_1 = $_POST['password_1'];
    } else {
        $password_1 = "";
    }

    if (isset($_POST['password_2'])) {
        $password_2 = $_POST['password_2'];
    } else {
        $password_2 = "";
    }


    // Check SQL Query
    if (!empty($nom) && !empty($prenom) && !empty($username) && !empty($email) && !empty($password_1) && !empty($password_2) && !empty($date_naissance)) {

        // Check if passwords match
        if ($password_1 != $password_2) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        } else {
            // Encrypt the password before saving it
            $password = md5($password_1);

            // Check if the email is valid
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Check if the user already exists
                $select = mysqli_query($con, "SELECT * FROM user WHERE email = '$email'");
                if (mysqli_num_rows($select)) {
                    $errors[] = "Cette personne existe déjà.";
                } else {
                    // Insert new user data into the database
                    $sql = "INSERT INTO user (nom, prenom,username,email, password, date_naissance) VALUES ('$nom', '$prenom','$username', '$email', '$password', '$date_naissance')";
                    if ($con->query($sql) === TRUE) {
                        // Get the ID of the newly created user
                        $id = $con->insert_id;
                        $_SESSION['id'] = $row["id"];
                        $_SESSION['username'] = $row["username"]; 
                        $_SESSION['success']  = "Nouvel utilisateur créé avec succès !";
                        header('location: accueil.php');
                        exit();
                    } else {
                        // Display error message
                        $errors[] = "Erreur lors de l'exécution de la requête SQL: " . mysqli_error($con);
                    }
                }
            } else {
                // Invalid email format
                $errors[] = "L'adresse e-mail n'est pas valide.";
            }
        }
    } else {
        // Form fields are empty
        $errors[] = "Tous les champs doivent être complétés.";
    }

    // Close database connection
    mysqli_close($con);
}
?>
