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
  $oldpassword = $_POST["oldpassword"];
  $newpassword = $_POST["newpassword"];


  if(!($username && $oldpassword && $newpassword)) {
    $output = "<p>Not all fields were set. Please fill in all fields.</p>";
    exit($output);
  }

  $stmt = mysqli_prepare($connection, "SELECT password FROM users WHERE username = ? AND password = ?");
  mysqli_stmt_bind_param($stmt, "ss", $username, md5($password));
  mysqli_stmt_execute($stmt);

  if(mysqli_num_rows(mysqli_stmt_get_result($stmt)) === 0) {
    $output = "<p>username and/or password are invalid</a>";
    exit($output);
  }

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($connection, "UPDATE users SET password = ? WHERE username = ?");
  mysqli_stmt_bind_param($stmt, "ss", md5($password), $username);
  mysqli_stmt_execute($stmt);

  // check for errors
  if (mysqli_stmt_error($stmt)) {
    $output = "Error: " . mysqli_stmt_error($stmt);
  } else {
    $output = "Password updated!";
  }

  // close statement
  mysqli_stmt_close($stmt);
}

// close database connection
mysqli_close($connection);
?>

<p>Server Message:<?php if (!empty($output)): ?></p>
  <?php echo $output; ?>
<?php endif; ?>
</html>
