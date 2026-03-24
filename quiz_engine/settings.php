<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['change'])){
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    $check = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id' AND password='$old'");
    
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn,"UPDATE users SET password='$new' WHERE id='$user_id'");
        $msg = "Password Updated Successfully!";
    } else {
        $msg = "Old Password Incorrect!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Settings</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">

<style>
body{
    display:flex;
    min-height:100vh;
    font-family:'Montserrat',sans-serif;
    background:linear-gradient(-45deg,#1d2671,#c33764,#11998e,#38ef7d);
    background-size:400% 400%;
    animation:gradientBG 15s ease infinite;
    color:white;
}
@keyframes gradientBG{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}

.sidebar{
    width:240px;
    background:rgba(0,0,0,0.4);
    padding:30px;
}
.sidebar h2{margin-bottom:40px;}
.sidebar a{
    display:block;
    padding:14px;
    margin:12px 0;
    border-radius:12px;
    text-decoration:none;
    color:white;
}
.sidebar a:hover{background:rgba(255,255,255,0.2);}

.main{
    flex:1;
    padding:60px;
}

.card{
    background:rgba(255,255,255,0.15);
    padding:40px;
    border-radius:25px;
    backdrop-filter:blur(20px);
    width:450px;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border-radius:10px;
    border:none;
}

button{
    padding:12px 30px;
    border:none;
    border-radius:30px;
    background:linear-gradient(to right,#ff9966,#ff5e62);
    color:white;
    cursor:pointer;
}
.message{
    margin-bottom:15px;
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>🚀 Quiz Engine</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="profile.php">Profile</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php" style="margin-top:150px;">Logout</a>
</div>

<div class="main">
    <div class="card">
        <h2>Change Password</h2>

        <?php if(isset($msg)) echo "<div class='message'>$msg</div>"; ?>

        <form method="POST">
            <input type="password" name="old_password" placeholder="Old Password" required>
            <input type="password" name="new_password" placeholder="New Password" required>
            <br><br>
            <button type="submit" name="change">Update Password</button>
        </form>
    </div>
</div>

</body>
</html>