<?php
session_start();

// Check if the user is already logged in
if(isset($_SESSION['user_id'])) {
  header("Location: dashboard.php");
  exit;
}

// Check if the form has been submitted
if(isset($_POST['submit'])) {
  // Get the user's email/username and password from the form
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Connect to the database
  $db_host = "localhost";
  $db_user = "username";
  $db_pass = "password";
  $db_name = "database_name";

  $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

  // Check if the connection was successful
  if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Prepare the SQL query to check if the user exists
  $sql = "SELECT * FROM users WHERE email='$email' OR username='$email'";

  // Execute the query
  $result = mysqli_query($conn, $sql);

  // Check if the query was successful
  if($result && mysqli_num_rows($result) > 0) {
    // Get the user's information from the database
    $user = mysqli_fetch_assoc($result);

    // Check if the password matches
    if(password_verify($password, $user['password'])) {
      // Set session variables to indicate that the user is logged in
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      // Redirect the user to the dashboard
      header("Location: dashboard.php");
      exit;
    }
    else {
      $error = "Invalid password";
    }
  }
  else {
    $error = "Invalid email/username";
  }

  // Close the database connection
  mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <h1>Login</h1>
  <?php if(isset($error)) { ?>
    <p><?php echo $error; ?></p>
  <?php } ?>
  <form method="post" action="">
    <label>Email/Username:</label>
    <input type="text" name="email"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <input type="submit" name="submit" value="Login">
  </form>
</body>
</html>
