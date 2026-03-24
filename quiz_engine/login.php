<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Login - Quiz Engine</title>

<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg,#1abc9c,#16a085);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* Glass Card */
.card{
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(15px);
    padding:40px;
    width:420px;
    border-radius:20px;
    box-shadow:0 20px 40px rgba(0,0,0,0.25);
    color:white;
}

/* Title */
h2{
    text-align:center;
    margin-bottom:25px;
    font-size:26px;
}

/* Inputs */
input{
    width:100%;
    padding:14px;
    margin:10px 0;
    border-radius:12px;
    border:none;
    outline:none;
    font-size:15px;
}

/* Button */
button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:25px;
    background: linear-gradient(45deg,#ff9a9e,#ff6b6b);
    color:white;
    font-size:16px;
    cursor:pointer;
    margin-top:10px;
    transition:0.3s;
}

button:hover{
    transform:scale(1.05);
}

/* Error Box */
.error{
    background:rgba(255,0,0,0.2);
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
    text-align:center;
}

/* Link */
.link{
    text-align:center;
    margin-top:18px;
}

.link a{
    color:#ffeaa7;
    text-decoration:none;
    font-weight:bold;
}
</style>

</head>
<body>

<div class="card">

<h2>🚀 Quiz Engine Login</h2>

<?php
if(isset($_GET['error'])){
    echo "<div class='error'>Invalid Email or Password</div>";
}
?>

<form action="process_login.php" method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit" name="login">Login</button>
</form>

<div class="link">
Don't have account? <a href="register.php">Register</a>
</div>

</div>

</body>
</html>