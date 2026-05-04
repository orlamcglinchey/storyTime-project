<?php
session_start();
if (isset($_SESSION['user'])) {//user exists
     header('Location: dashboard.php');
      exit; 
      }

$users_file = 'data/users.json';
$users = file_exists($users_file) ? json_decode(file_get_contents($users_file), true) : []; //add to users
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];


    if ($_POST['action'] == 'login') {
        if (isset($users[$username]) && $users[$username] == $password) {
            $_SESSION['user'] = $username;
            header('Location: dashboard.php'); 

            exit;
            //error handling for wrong details
        } else {
            $error = 'Wrong username or password.';
        }
    } else {
        if (isset($users[$username])) {
            $error = 'Username taken.';
        } 
        else {
            $users[$username] = $password;
            file_put_contents($users_file, json_encode($users));
            $_SESSION['user'] = $username;
            header('Location: dashboard.php'); 
            exit;
        }
    }
}
?>
<link rel="stylesheet" href="style.css">
<div class="page">

<h1>StoryTime</h1>
<?php if ($error) echo "<p class='error'>$error</p>"; ?>

<div class = "login">
    <div class="loginBox">
<h2>Login</h2>
<form method="POST">
  <input type="hidden" name="action" value="login">
  Username: <input type="text" name="username"><br><br>
  Password: <input type="password" name="password"><br><br>
  <button type="submit">Login</button>
</form>
</div>
<div class = "loginBox">
<h2>Register</h2>
<form method="POST">
  <input type="hidden" name="action" value="register">
  Username: <input type="text" name="username"><br><br>
  Password: <input type="password" name="password"><br><br>
  <button type="submit">Register</button>
</form>
</div>
</div>
</div>