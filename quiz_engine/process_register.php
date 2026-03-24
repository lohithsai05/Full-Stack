<?php
include "config.php";

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $college = $_POST['college_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // CHECK EMAIL
    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        echo "Email already exists";
    } 
    else{

        $sql = "INSERT INTO users (name, email, password, college_name, created_at) 
                VALUES ('$name', '$email', '$password', '$college', NOW())";

        if(mysqli_query($conn, $sql)){
            header("Location: login.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>