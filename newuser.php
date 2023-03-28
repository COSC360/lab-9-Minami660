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
  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];


  if(!($firstname && $lastname && $username && $email && $password)) {
    $output = "<p>Not all fields were set. Please fill in all fields.</p>";
    exit($output);
  }

  $stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE username = ? OR email = ?");
  mysqli_stmt_bind_param($stmt, "ss", $username, $email);
  mysqli_stmt_execute($stmt);

  if(mysqli_num_rows(mysqli_stmt_get_result($stmt)) > 0) {
    $output = "<p>User already exists with this name and/or email</p>
    <a href=\"./lab9-1.html\">Return to user entry</a>";
    exit($output);
  }

  // prepare and execute SQL statement
  $stmt = mysqli_prepare($connection, "INSERT INTO users (username, firstname, lastname, email, password) VALUES (?, ?, ?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "sssss", $username, $firstname, $lastname, $email, md5($password));
  mysqli_stmt_execute($stmt);

  // check for errors
  if (mysqli_stmt_error($stmt)) {
    $output = "Error: " . mysqli_stmt_error($stmt);
  } else {
    $output = "User registered successfully!";
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
