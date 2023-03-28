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
  $password = $_POST["password"];

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($connection, "SELECT username, password FROM users WHERE username = ? AND password = ?");
  mysqli_stmt_bind_param($stmt, "ss", $username, md5($password));
  mysqli_stmt_execute($stmt);

  // check for errors
  if (mysqli_num_rows(mysqli_stmt_get_result($stmt)) === 0) {
    $output = "username and/or password are invalid";
  } else {
    $output = "User logged in successfully! User has a valid account.";
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
