<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connection.php');
$errors=array();
if(isset($_POST['login'])){
    login();
}
//login user
function login()
{
    global $con,$errors;
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
      } else {
        $email = "";
      }
    
      if (isset($_POST['password'])) {
        $password = $_POST['password'];
      } else {
        $password = "";
      }
    // form validation: ensure that the form is correctly filled
    if (!empty($email) and !empty($password)) {
        // register user if there are no errors in the form
        if (count($errors) == 0) {
            $password = md5($password);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password' LIMIT 1";
                $result = mysqli_query($con, $sql);
                $count = mysqli_num_rows($result);

                if ($count == 1) {
                    $row = mysqli_fetch_array($result);
                    echo "<h1><center> Login successful </center></h1>";
                    $_SESSION['id'] = $row["id"];
                    $_SESSION['username'] = $row["username"]; 
                    $_SESSION['success']  = "Login successful";
                    header('location: accueil.php');
                    exit();
                } else {
                    echo "<h1> Login failed. Invalid email or password.</h1>";
                }
            } else {
                echo "adresse mail n'est pas valide !";
            }
        }
    } else {
        echo "Tous les champs doivent être complétés !";
    }

    // Close connection
    mysqli_close($con);
}
?>


