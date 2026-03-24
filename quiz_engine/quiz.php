<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/* START QUIZ */
if(isset($_GET['topic_id']) && !isset($_SESSION['quiz'])){
    $topic_id = intval($_GET['topic_id']);
    $_SESSION['topic_id'] = $topic_id;

    $query = mysqli_query($conn,"
        SELECT * FROM questions
        WHERE topic_id = '$topic_id'
        ORDER BY RAND()
        LIMIT 20
    ");

    $_SESSION['quiz'] = mysqli_fetch_all($query, MYSQLI_ASSOC);
    $_SESSION['current_q'] = 0;
    $_SESSION['user_answers'] = [];
}

/* IF QUIZ NOT STARTED */
if(!isset($_SESSION['quiz'])){
    header("Location: dashboard.php");
    exit();
}

/* SAVE ANSWER */
if(isset($_POST['answer'])){
    $_SESSION['user_answers'][$_SESSION['current_q']] = $_POST['answer'];
}

/* HANDLE BUTTON ACTION */
if(isset($_POST['action'])){

    if($_POST['action'] == 'next'){
        if($_SESSION['current_q'] < count($_SESSION['quiz']) - 1){
            $_SESSION['current_q']++;
        }
    }

    if($_POST['action'] == 'prev'){
        if($_SESSION['current_q'] > 0){
            $_SESSION['current_q']--;
        }
    }

    if($_POST['action'] == 'finish'){

        $score = 0;
        $total = count($_SESSION['quiz']);

        foreach($_SESSION['quiz'] as $index => $q){
            if(isset($_SESSION['user_answers'][$index]) &&
               $_SESSION['user_answers'][$index] == $q['correct_answer']){
                $score++;
            }
        }

        $_SESSION['final_score'] = $score;
        $_SESSION['total_questions'] = $total;

        header("Location: result.php");
        exit();
    }
}

$current = $_SESSION['current_q'];
$total = count($_SESSION['quiz']);
$question = $_SESSION['quiz'][$current];
?>

<!DOCTYPE html>
<html>
<head>
<title>Quiz</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
/* KEEP YOUR DESIGN SAME */
<?php /* I kept your exact CSS same below */ ?>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{height:100vh;display:flex;justify-content:center;align-items:center;overflow:hidden;background:linear-gradient(-45deg,#6a11cb,#2575fc,#ff512f,#dd2476);background-size:400% 400%;animation:gradientMove 12s ease infinite;}
@keyframes gradientMove{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.floating-card{width:780px;padding:45px;border-radius:30px;background:rgba(255,255,255,0.15);backdrop-filter:blur(25px);box-shadow:0 25px 70px rgba(0,0,0,0.4);color:white;}
h2{font-weight:600;margin-bottom:25px;}
.progress{height:12px;border-radius:20px;background:rgba(255,255,255,0.3);margin-bottom:30px;overflow:hidden;}
.progress-bar{height:12px;border-radius:20px;background:linear-gradient(to right,#00f2fe,#4facfe);}
.question{font-size:20px;margin-bottom:25px;}
.option{display:block;padding:15px 18px;margin:12px 0;border-radius:18px;background:rgba(255,255,255,0.2);cursor:pointer;border:1px solid rgba(255,255,255,0.2);}
.buttons{margin-top:30px;}
button{padding:12px 32px;border:none;border-radius:40px;font-weight:600;cursor:pointer;margin-right:15px;background:linear-gradient(to right,#ff512f,#dd2476);color:white;}
</style>
</head>
<body>

<div class="floating-card">

<h2>Question <?php echo $current+1; ?> / <?php echo $total; ?></h2>

<div class="progress">
<div class="progress-bar" style="width:<?php echo (($current+1)/$total)*100; ?>%"></div>
</div>

<form method="POST">

<div class="question">
<?php echo $question['question']; ?>
</div>

<?php
$options = ['option1','option2','option3','option4'];
foreach($options as $opt){
    $checked = (isset($_SESSION['user_answers'][$current]) &&
                $_SESSION['user_answers'][$current] == $question[$opt]) ? "checked" : "";

    echo "<label class='option'>
            <input type='radio' name='answer' value='{$question[$opt]}' $checked>
            {$question[$opt]}
          </label>";
}
?>

<div class="buttons">

<?php if($current > 0){ ?>
<button type="submit" name="action" value="prev">Previous</button>
<?php } ?>

<?php if($current < $total-1){ ?>
<button type="submit" name="action" value="next">Next</button>
<?php } else { ?>
<button type="submit" name="action" value="finish">Finish</button>
<?php } ?>

</div>

</form>

</div>

</body>
</html>