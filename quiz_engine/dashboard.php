<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$topics = mysqli_query($conn,"SELECT * FROM topics");

/* Fetch user results */
$results = mysqli_query($conn,"
SELECT r.*, t.topic_name 
FROM results r
JOIN topics t ON r.topic_id = t.id
WHERE r.user_id='$user_id'
ORDER BY r.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;500;700&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Montserrat',sans-serif;}

body{
    display:flex;
    min-height:100vh;
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

/* Sidebar */
.sidebar{
    width:260px;
    background:rgba(0,0,0,0.6);
    padding:30px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}

.sidebar h2{margin-bottom:30px;}

.menu a{
    display:block;
    padding:14px;
    margin:10px 0;
    border-radius:10px;
    color:white;
    text-decoration:none;
    transition:.3s;
}

.menu a:hover{
    background:rgba(255,255,255,0.2);
}

.logout{
    padding:12px;
    background:linear-gradient(45deg,#ff9966,#ff5e62);
    border-radius:30px;
    text-align:center;
    text-decoration:none;
    color:white;
}

/* Main */
.main{
    flex:1;
    padding:50px;
}

/* Cards */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:25px;
    margin-top:30px;
}

.card{
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(25px);
    padding:25px;
    border-radius:20px;
}

.card button{
    width:100%;
    padding:10px;
    margin-top:10px;
    border:none;
    border-radius:30px;
    background:linear-gradient(to right,#ff9966,#ff5e62);
    color:white;
}

/* Result Circle */
.result-section{
    margin-top:60px;
}

.result-card{
    background:rgba(255,255,255,0.15);
    padding:30px;
    border-radius:20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    margin-bottom:20px;
}

.circle{
    width:130px;
    height:130px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
    font-size:20px;
    position:relative;
}

.circle span{
    position:absolute;
    color:white;
    font-size:22px;
}
</style>
</head>
<body>

<div class="sidebar">
    <div>
        <h2>🚀 Quiz Engine</h2>
        <div class="menu">
            <a href="dashboard.php">Dashboard</a>
            <a href="profile.php">Profile</a>
            <a href="settings.php">Settings</a>
        </div>
    </div>

    <a class="logout" href="logout.php">Logout</a>
</div>

<div class="main">

<h1>Welcome <?php echo $_SESSION['user_name']; ?> 👋</h1>
<p><?php echo $_SESSION['college']; ?></p>

<!-- SUBJECT CARDS -->
<div class="cards">
<?php while($row = mysqli_fetch_assoc($topics)){ ?>
    <div class="card">
        <h3><?php echo $row['topic_name']; ?></h3>
        <p>20 Questions • Random Every Attempt</p>
        <a href="quiz.php?topic_id=<?php echo $row['id']; ?>">
            <button>Start Quiz</button>
        </a>
    </div>
<?php } ?>
</div>

<!-- RESULTS SECTION -->
<div class="result-section">
<h2>Your Quiz Attempts</h2>

<?php while($r = mysqli_fetch_assoc($results)){
    $correct = $r['score'];
    $total = $r['total_questions'];
    $wrong = $total - $correct;
    $percent = $r['percentage'];
?>
<div class="result-card">
    <div>
        <h3><?php echo $r['topic_name']; ?></h3>
        <p>Correct: <?php echo $correct; ?> | Wrong: <?php echo $wrong; ?></p>
        <p>Date: <?php echo $r['attempt_date']; ?></p>
    </div>

    <div class="circle" style="
        background: conic-gradient(
            #4CAF50 <?php echo $percent; ?>%,
            #ff5e62 <?php echo $percent; ?>%
        );
    ">
        <span><?php echo $percent; ?>%</span>
    </div>
</div>
<?php } ?>

</div>

</div>
</body>
</html>