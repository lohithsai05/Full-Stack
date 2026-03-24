<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $college = $_POST['college'];

    mysqli_query($conn,"
        UPDATE users 
        SET name='$name', email='$email', college='$college'
        WHERE id='$user_id'
    ");

    $_SESSION['user_name'] = $name;
    $_SESSION['college'] = $college;

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
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
        <h2>Edit Profile</h2>

        <form method="POST">
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
            <input type="text" name="college" value="<?php echo $user['college']; ?>" required>
            <br><br>
            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>
</div>

</body>
</html>