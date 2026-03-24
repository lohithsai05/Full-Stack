<?php
session_start();
include "config.php";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($query) == 1){

        $row = mysqli_fetch_assoc($query);

        if(password_verify($password, $row['password'])){

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['college'] = $row['college_name'];

            header("Location: dashboard.php");
            exit();

        } else {
            header("Location: login.php?error=1");
            exit();
        }

    } else {
        header("Location: login.php?error=1");
        exit();
    }
}
?>