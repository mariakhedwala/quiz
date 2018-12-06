<?php 
  session_start(); 
  global $conn;
  $conn = new mysqli('localhost', 'root', 'root','quiz');
  if (isset($_GET['start-quiz'])) {
    $_SESSION['quiz'] = "Quiz completed";
    unset($_SESSION['currentQuestion']);
    unset($_SESSION['attempted']);
    unset($_SESSION['attemptedCount']);
  }
  if (!isset($_SESSION['quiz'])) {
    $_SESSION['quiz'] = "You completed the quiz";
    unset($_SESSION['currentQuestion']);
    unset($_SESSION['attempted']);
    unset($_SESSION['attemptedCount']);
    header('location: login.php');
    session_destroy();
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['results']);
    unset($_SESSION['currentQuestion']);
    unset($_SESSION['quiz']);
    unset($_SESSION['attempted']);
    unset($_SESSION['attemptedCount']);
    header("location: login.php");
  }
  $counter =10;
  if(isset($_POST['end'])) {
    unset($_SESSION['currentQuestion']);
    unset($_SESSION['quiz']);
    echo $_SESSION['startTime'];
    $startTime = $_SESSION['startTime'];

    $_SESSION['quizStatus'] = "something";
    if(!empty($_POST['quizcheck'])) {
      $query = "SELECT * FROM question WHERE ans_id";
      $_SESSION['attemptedCount'] += 1; 
      echo "<h1>you attempted ". $_SESSION['attemptedCount'] ." questions</h1>";
      $_SESSION['attempted'][$counter] = $_POST['quizcheck'];
      foreach($_SESSION['attempted'] as $key=>$value){
        if(is_array($value)){
          foreach($value as $v => $vv){
            $query = "SELECT ans_id FROM question WHERE q_id = '$v';";
            $result = mysqli_query($conn, $query);
            while($finalR = mysqli_fetch_array($result)) {
              $correctAnsAll = $finalR[ans_id];
            }
            if ($correctAnsAll == $vv) {
              $correctCount +=1;
            }
          }
        }
      } 
      echo "<h3>Your score out of 10 is ".$correctCount.". Heartly congratulations :P</h3>";
    } else {
      echo "<h1>attempted ". $_SESSION['attemptedCount'] ."</h1>";
      $_SESSION['attempted'][$counter] = array(0 => 0);
      // print_r($_SESSION['attempted']);
      foreach($_SESSION['attempted'] as $key=>$value){
        if(is_array($value)){
          foreach($value as $v => $vv){
            $query = "SELECT ans_id FROM question WHERE q_id = '$v';";
            $result = mysqli_query($conn, $query);
            while($finalR = mysqli_fetch_array($result)) {
              $correctAnsAll = $finalR[ans_id];
            }
            if ($correctAnsAll == $vv) {
              $correctCount +=1;
            }
          }
        }
      } 
      echo "<h3>Your score out of 10 is ".$correctCount.". Heartly congratulations :P</h3>";
    }
    static $endTime;
    $date1 = new DateTime();
    $endTime = $date1->format('Y-m-d H:i:s') . "\n";
    $_SESSION['endTime'] = $endTime;
    echo $_SESSION['endTime'];
    static $interval;
    $interval = $date1 -> diff($_SESSION['startTimeObj']);
    $totalTimeTaken = $interval->format("%I:%S");
    echo "You took <strong>".$totalTimeTaken. "</strong> to complete the quiz";

    $name = $_SESSION['username'];
    $finalResult = "INSERT into result(username,total_q,correct_ans,timeTaken) VALUES ('$name',10,'$correctCount','$totalTimeTaken')";
    $finalResultFire = mysqli_query($conn,$finalResult);

  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
  <?php  if (isset($_SESSION['username']) && ($_SESSION['role']== 'student') && !isset($_SESSION['quiz'])) : ?>
    <p> <a href="index.php?logout='1'" style="color: red;" >logout</a> </p>
  <?php endif ?>
  </body>
</html>