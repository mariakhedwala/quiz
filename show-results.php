<?php 
  session_start(); 
  $conn = new mysqli('localhost', 'root', 'root','quiz'); 
  if (isset($_GET['show-results'])) {
    $_SESSION['results'] = "viewing results";
  } else { echo "fail"; }
  if (!isset($_SESSION['results'])) {
    $_SESSION['results'] = "You must log in first";
    header('location: index.php');
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['results']);
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
  <div class="wrapper">
    <?php  if (isset($_SESSION['username']) && ($_SESSION['role'] == 'admin') ) : ?>
      <div class="top-panel">
        <p class="user-name">Hi 
          <?php echo $_SESSION['username'] ?>
        </p>
        <p class="logout"> <a href="index.php?logout='1'">Log-out</a> </p>
      </div>
        <table class="results">
        <thead>
          <tr>
            <th>Name</th>
            <th>score</th>
            <th>time</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          global $conn;
          $query = "SELECT * FROM result ORDER BY correct_ans DESC, timeTaken";
          $results = mysqli_query($conn,$query);
            while($row = mysqli_fetch_assoc($results)) { ?>
              <tr>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['correct_ans']; ?></td>
                <td><?php echo $row['timeTaken']; ?></td>
              </tr>
            <?php } ?> 
          </tbody>
        </table>
      <p style="text-align:center;margin:10px 0;color:green;">SORTED BY HIGHSCORE AND LEAST TIME!!!</p>
      </form>
    <?php endif ?>
  </div>
</body>
</html>