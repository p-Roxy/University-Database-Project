<?php
include('loginHelper.php');

if (isset($_SESSION['login_user'])) {
    header("location: profile.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>304 Project Final</title>
    <link rel="stylesheet" href="css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<div class="large-12 columns text-center">
    <img src="css/images/g4logo@.5x.png" id="logo" alt="School Welcome">
    <h1>Login</h1>
</div>
<div class="large-6 medium-6 small-6 text-left small-centered columns">
    <form method="post" action="loginHelper.php">
    <input type="text" id="code" placeholder="Username" name="username"/>
    <input type="password" id="password" name="password" placeholder="*****"/>
        <div class="large-6 medium-6 small-6 columns small-centered text-center">
        I am a <select name="type">
            <option name="student">Student</option>
            <option name="professor">Instructor</option>
        </select>
        </div>
    <input type="submit" name="submit" id="btnSubmit" value="Login">
    </form>
</div>

</body>
</html>