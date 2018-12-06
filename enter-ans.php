<?php
$ans = "";
$ans_id    = "";

$servername = "localhost";
$localusername = "root";
$localpassword = "root";

// Create connection
$conn = new mysqli($servername, $localusername, $localpassword,'quiz');

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} else { echo "connected"; }

if (isset($_POST['enter'])) {
    // echo "here";
    // // receive all input values from the form
    // $ans = mysqli_real_escape_string($conn, $_POST['ans']);
    // $ans_id = mysqli_real_escape_string($conn, $_POST['options']);
    // // connect to the database
    // $query = "INSERT INTO answer (ans,options) 
    // VALUES('$ans','$ans_id')";
    // mysqli_query($db, $query);

    $ans =  $_POST['ans'];
    // $ans_id = $_POST['options'];
    $query = "INSERT INTO answer (ans) VALUES('$ans')";
    $result = mysqli_query($conn, $query);
    if(!result) {
        die('Query FAILED' . mysqli_error());
    } else {
        echo $result;
    } 
}
?>
<html>
    <form action="enter-ans.php" method="post">
        <input type="text" name="ans" placeholder="answers">
        <!-- <input type="number" name="options" placeholder="answer id"> -->
        <input type="submit" name="enter" placeholder="enter" value="enter">
    </form>
</html>