<?php session_start(); 
    $conn = new mysqli('localhost', 'root', 'root','quiz');
    if (isset($_GET['start-quiz'])) {
    $_SESSION['quiz'] = "Quiz Started";
  }
  if (!isset($_SESSION['quiz'])) {
    $_SESSION['quiz'] = "You must log in first";
    header('location: index.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['results']);
    header("location: login.php");
  }
  if(isset($_POST['end'])) {
    unset($_SESSION['currentQuestion']);
    unset($_SESSION['quiz']);
  }
 
?>
<!DOCTYPE html>
<html>
<head>
	<title>start quiz</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php  if (isset($_SESSION['username']) && ($_SESSION['role']== 'student') ) : ?>
  you are here
  <?php echo $_SESSION['quiz']; ?>
    <p> <a href="index.php?logout='1'" style="color: red;" >logout</a> </p>
    <?php
    static $startTime;
    $date = new DateTime();
    echo "start time = " . $startTime = $date->format('Y-m-d H:i:s') . "\n";
    // echo "<p>". $startTime. "</p>";

    global $currentQuestion ;
    $currentQuestion = 0;
    
    function displayQuest($currentQuestion) {
      global $conn; 
      echo "<h3>Question no : ". $currentQuestion . "</h3>";?>
      <?php
      $questions = mysqli_query($conn, "SELECT * FROM question WHERE q_id = $currentQuestion;");
      while($row = mysqli_fetch_array($questions)) {
        $question[] = $row['question'];
        echo "<h4>" . $row['question'] . "</h4>";

        $answer = mysqli_query($conn,"SELECT * FROM answer WHERE a_id IN (SELECT ans_id FROM map_ans WHERE q_id = $currentQuestion)");
        while($rows = mysqli_fetch_array($answer)) {
          ?>
          <div class="ans-list">
            <input type="radio" name="quizcheck[<?php echo $row['q_id'] ?>]" value="<?php echo $rows['a_id'] ?>">
            <?php echo $rows['ans']; ?>
          </div>
          <?php
        }
      } 
      
      // $currentQuestion += 1;
    }
    session_start();
      $_SESSION['currentQuestion'] = ((isset($_SESSION['currentQuestion'])) ? $_SESSION['currentQuestion'] : 1);
      if(isset($_POST['start']) || isset($_POST['next'])) {
        $currentQuestion = $_SESSION['currentQuestion']++;
      }
      ?>
        <?php if($currentQuestion==0){ ?>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <input type="submit" name="start" value="start" class="btn" />
            <p>your time will be started as soon as you click start</p>
          </form>

        <?php } else if($currentQuestion>0 && $currentQuestion<10) { ?>

          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <?php displayQuest($currentQuestion); ?>
            <input type="submit" name="next" value="next" class="btn" />
          </form>
          
        <?php }
        if($currentQuestion == 10){ ?>

          <form action="result.php" method="post">
          <?php displayQuest($currentQuestion); ?>
            <input type="submit" name="end" value="submit test" class="btn" />
          </form>

        <?php } ?>
        <?php
    ?>
  <?php endif ?>

</body>
</html>