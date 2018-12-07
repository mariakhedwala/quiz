<?php session_start(); 
$counter = 0;
  $conn = new mysqli('localhost', 'root', 'root','quiz');
  $currentQuestionList = array(1,2,3,4,5,6,7,8,9,10);
  shuffle($currentQuestionList);
  if (isset($_GET['start-quiz'])) {
    $_SESSION['attemptedCount'] = 0;
    $_SESSION['quiz'] = "Quiz Started";
    $_SESSION['que'] = $currentQuestionList;
    static $startTime;
    $date = new DateTime();
    $_SESSION['startTimeObj'] = $date;
    $startTime = $date->format('Y-m-d H:i:s') . "\n";
    $_SESSION['startTime'] = $startTime;
 
  }

  if(isset($_POST['next'])) {
    $counter = $_SESSION['counter1'] += 1;
  } else {
    $counter = $_SESSION['counter1'] = 0;
  }
  if(isset($_POST['next']) || isset($_POST['end'])) {
    $_SESSION['$currentQuestionNumber'] = array();
    $_SESSION['$currentQuestionNumber'][] = $_POST['currentQuestionNumber'];
    // print_r($result);
    $currentQuestion = $_SESSION['que'];
    
    if(!empty($_POST['quizcheck'])) {
      $_SESSION['attemptedCount'] += 1; 
      // echo "<h1>attempted ". $_SESSION['attemptedCount'] ."</h1>";
      $_SESSION['attempted'][$counter] = $_POST['quizcheck'];
      // print_r($_SESSION['attempted']) ;
      
    } else {
      $_SESSION['attempted'][$counter] = array(0 => 0);
      // print_r($_SESSION['attempted']);
    }
    // print_r($currentQuestion);
  }
  if (!isset($_SESSION['quiz'])) {
    $_SESSION['quiz'] = "You must log in first";
    unset($_SESSION['currentQuestion']);
    unset($_SESSION['attempted']);
    unset($_SESSION['attemptedCount']);
    unset($_SESSION['quiz']);
    header('location: login.php');
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
<?php  if (isset($_SESSION['username']) && ($_SESSION['role']== 'student') && isset($_SESSION['quiz'])) : ?>
  you are here
  <span id="timer"></span>
  <?php echo $_SESSION['quiz']; ?>
    <p> <a href="index.php?logout='1'" style="color: red;" >logout</a> </p>
    <?php

    $currentQuestion = $_SESSION['que'];
  
    function displayQuest(array $currentQuestion) {
      global $conn; 
      global $counter;
      global $currentQuestion;
      echo "<h3>Question no : ". $counter . "</h3>";?>
      <?php
      $questions = mysqli_query($conn, "SELECT * FROM question WHERE q_id = '$currentQuestion[$counter]';");
      while($row = mysqli_fetch_array($questions)) {
        $question[] = $row['question'];
        echo "<h4>" . $row['question'] . "</h4>";
        $answer = mysqli_query($conn,"SELECT * FROM answer WHERE a_id IN (SELECT ans_id FROM map_ans WHERE q_id = '$currentQuestion[$counter]')");
        while($rows = mysqli_fetch_array($answer)) { ?>
          <div class="ans-list">
            <input type="radio" name="quizcheck[<?php echo $row['q_id'] ?>]" value="<?php echo $rows['a_id'] ?>">
            <?php echo $rows['ans']; ?>
          </div>
          <?php } ?>
          <input type="hidden" name="indexOfArray" value= <?php echo $counter; ?> />
          <input type="hidden" name="currentQuestionNumber" value= <?php echo $currentQuestion[$counter]; ?> />
          <input type="hidden" class="startTimeInput" value= "<?php echo $_SESSION['startTime']; ?>" />
      <?php } 
    }
    ?>
      <?php if($counter<0){ ?>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="submit" name="start" value="start" class="btn" />
          <p>your time will be started as soon as you click start</p>
        </form>

      <?php } else if($counter>=0 && $counter<9) { ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <?php displayQuest($currentQuestion); ?>
          <input type="submit" name="next" value="next" class="btn" />
        </form>
        
      <?php }

      if($counter == 9){ ?>
        <form action="result.php" method="post">
        <?php displayQuest($currentQuestion); ?>
          <input type="submit" name="end" value="submit test" onclick="end()" class="btn" />
        </form>

      <?php } ?>
      <?php
    ?>
  <?php endif ?>
    <!-- <script text="javascript">
    window.onload = function() {
      var startT = document.querySelector(".startTimeInput").value;
      
      var now = <?php //echo time() ?> * 1000;

    // Update the count down every 1 second
    var x = setInterval(function() {
      // var months = [1,2,3,4,5,6,7,8,9,10];
      // var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        // Get todays date and time
        // 1. JavaScript
        now = now+1000;
        var now1 = new Date(now);

        var yearr = now1.getFullYear();
        var monthh = now1.getMonth()+1;
        var datee = ("0" + now1.getDate()).slice(-2);
        var hourr = now1.getHours();
        var minn = now1.getMinutes();
        var secs = now1.getSeconds(); 
        // console.log(yearr,monthh,datee,hourr,minn,secs);
        var myJSTime = yearr +"-"+ monthh +"-"+ datee +" "+ hourr+":"+ minn +":"+ secs;
        // 2. PHP
      console.log(Date.parse(startT+"-0500")/1000);
      var startNewT = Date.parse(startT+"-0500")/1000;
        console.log(Date.parse(myJSTime+"-0500")/1000);
        var JST = Date.parse(myJSTime+"-0500")/1000;
        // console.log(now1.toString());
        $interval = $date1 -> diff($_SESSION['startTimeObj']);
        <?php 
        //$differ = 1544232952 -> diff(1544232952);
        ?>

        // Find the distance between now an the count down date
        var distance =  myJSTime - startT;
        console.log(distance);

        // Time calculations for days, hours, minutes and seconds
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        console.log(hours,minutes,seconds);
        // Output the result in an element with id="demo"
        document.getElementById("timer").innerHTML = hours + "h " +
            minutes + "m " + seconds + "s ";

        // If the count down is over, write some text 
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "EXPIRED";
        }
    }, 1000);
    };
      
    </script> -->
    <script text="javascript">
      var startT = document.querySelector(".startTimeInput").value;
    var countDownDate = new Date(startT).getTime();
console.log(countDownDate);
  // Update the count down every 1 second
  var x = setInterval(function() {

  // Get todays date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("timer").innerHTML = days + "d " + hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is finished, write some text 
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("timer").innerHTML = "EXPIRED";
  }
}, 1000);

    </script>

</body>
</html>