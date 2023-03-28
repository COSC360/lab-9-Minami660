<!DOCTYPE html>
<html>
<?php

$host = "localhost";
$database = "lab9";
$user = "webuser";
$password = "P@ssw0rd";

$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null)
{
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $username = $_POST["username"];

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($connection, "SELECT username, firstname, lastname, email FROM users WHERE username = ?");
  mysqli_stmt_bind_param($stmt, "s", $username);
  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  // check for errors
  if (mysqli_stmt_error($stmt)) {
    $output = "Error: " . mysqli_stmt_error($stmt);
    exit($output);
  } else {
    print "<table><fieldset>";
    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
      
      foreach ($row as $r) {
          print "$r \n";
      }
      print "\n";
    }
    print "</table></ieldset>";
  }

  // close statement
  mysqli_stmt_close($stmt);
}

// close database connection
mysqli_close($connection);
?>

</html>
