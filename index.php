<?php 
  session_start(); 
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
	<h2>Home Page</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
      <p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
      <?php if($_SESSION['role'] == 'admin') { ?>
        <a href="show-results.php?show-results='1'" title="show results" class="btn" style="display:block;text-align:center;">Show results</a>
      <?php } else if($_SESSION['role'] == 'student'){ ?>
        <a href="start-quiz.php?start-quiz='1'" title="start quiz" class="btn">start quiz</a>
        <ul>
          <li>You will get total time of <strong>20 minutes</strong></li>
          <li>Do not refresh else your question will be skipped</li>
          <li>If your quiz is left incomplete your result will not be evaluated</li>
          <li>Do not logout in between</li>
          <li>If you have already given the test, giving it again will not be counted</li>
        </ul>
        <p>GOOD LUCK!</p>
      <?php  }  ?>
    	<p class="logout"> <a href="index.php?logout='1'" style="width:100%; display:inline-block; text-align:left;">logout</a> </p>
    <?php endif ?>
</div>
</body>
</html>