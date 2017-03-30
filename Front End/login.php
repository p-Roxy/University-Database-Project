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
<div class="large-6 medium-8 small-6 text-left small-centered columns">
    <input type="text" id="code" placeholder="Username" name="room code"/>
    <input type="password" id="password" placeholder="*****"/>
    <div class="large-6 medium-8 small-6 small-centered columns"> <input type="radio" id="student" name="role" value="stud">I am a student </div>
    <div class="large-6 medium-8 small-6 small-centered columns"><input type="radio" id="instructor" name="role" value="ins">I am an instructor</div>


    <button id="btnSubmit">Submit</button>
</div>
<script>
    $('#btnSubmit').click(function () {
        var mysql = require('mysql');

        var connection = mysql.createConnection(
            {
                host     : 'localhost',
                user     : 'root',
                password : '123321',
                database : 'SchoolDB_group4'
            }
        );
        connection.connect();

        var queryString = 'SELECT * FROM Student';

        connection.query(queryString, function(err, rows, fields) {
            if (err){ throw err;
            alert("query fail");
            }

            alert(typeof rows);

        connection.end();
    })
    });
</script>
</body>
</html>