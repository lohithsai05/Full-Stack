<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id']) || !isset($_SESSION['quiz'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$topic_id = $_SESSION['topic_id']; // from quiz.php

$quiz = $_SESSION['quiz'];
$user_answers = $_SESSION['user_answers'];

$total = count($quiz);
$score = 0;

/* CALCULATE SCORE */
for($i=0; $i<$total; $i++){
    if(isset($user_answers[$i]) && 
       $user_answers[$i] == $quiz[$i]['correct_answer']){
        $score++;
    }
}

$percentage = round(($score/$total)*100);
$certificate_id = "QUIZ-2026-" . rand(1000,9999);

/* INSERT RESULT ONLY ON FIRST LOAD */
if(!isset($_SESSION['result_saved'])){

    mysqli_query($conn,"
        INSERT INTO results 
        (user_id, topic_id, score, total_questions, percentage, certificate_id)
        VALUES 
        ('$user_id','$topic_id','$score','$total','$percentage','$certificate_id')
    ");

    $_SESSION['result_saved'] = true;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Result</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{
    min-height:100vh;
    background:linear-gradient(-45deg,#6a11cb,#2575fc,#ff512f,#dd2476);
    background-size:400% 400%;
    animation:gradientMove 12s ease infinite;
    padding:50px;
    color:white;
}
@keyframes gradientMove{
    0%{background-position:0% 50%}
    50%{background-position:100% 50%}
    100%{background-position:0% 50%}
}
.container{max-width:900px;margin:auto;}
.score-card{
    text-align:center;
    padding:40px;
    border-radius:30px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(20px);
    box-shadow:0 20px 60px rgba(0,0,0,0.4);
    margin-bottom:40px;
}
.circle{
    width:160px;
    height:160px;
    margin:20px auto;
    border-radius:50%;
    background:conic-gradient(#00f2fe <?php echo $percentage*3.6; ?>deg, rgba(255,255,255,0.2) 0deg);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:30px;
    font-weight:bold;
}
.question-box{
    margin-bottom:25px;
    padding:25px;
    border-radius:20px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(15px);
}
.correct{color:#00ff99;font-weight:600;}
.wrong{color:#ff4b5c;font-weight:600;}
button{
    padding:12px 30px;
    border:none;
    border-radius:40px;
    font-weight:600;
    cursor:pointer;
    background:linear-gradient(to right,#ff512f,#dd2476);
    color:white;
    transition:0.3s;
}
button:hover{
    transform:scale(1.1);
    box-shadow:0 0 20px rgba(255,255,255,0.8);
}
</style>
</head>
<body>

<div class="container">

<div class="score-card">
    <h1>Quiz Completed 🎉</h1>
    <div class="circle">
        <?php echo $percentage; ?>%
    </div>
    <h2>Your Score: <?php echo $score; ?> / <?php echo $total; ?></h2>
    <br>

    <form action="certificate.php" method="POST">
        <input type="hidden" name="percentage" value="<?php echo $percentage; ?>">
        <button type="submit">Download Certificate</button>
    </form>

    <br><br>

    <a href="dashboard.php">
        <button>Back to Dashboard</button>
    </a>
</div>

<?php
for($i=0; $i<$total; $i++){
    echo "<div class='question-box'>";
    echo "<p><b>Q".($i+1).": ".$quiz[$i]['question']."</b></p><br>";

    $correct = $quiz[$i]['correct_answer'];
    $user = isset($user_answers[$i]) ? $user_answers[$i] : "Not Answered";

    if($user == $correct){
        echo "<p class='correct'>✔ Your Answer: $user</p>";
    } else {
        echo "<p class='wrong'>✖ Your Answer: $user</p>";
        echo "<p class='correct'>✔ Correct Answer: $correct</p>";
    }

    echo "</div>";
}
?>

</div>

</body>
</html>

<?php
/* CLEAR QUIZ SESSION AFTER DISPLAY */
unset($_SESSION['quiz']);
unset($_SESSION['current_q']);
unset($_SESSION['user_answers']);
unset($_SESSION['topic_id']);
unset($_SESSION['result_saved']);
?>