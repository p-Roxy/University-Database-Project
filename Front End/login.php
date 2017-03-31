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
<?php
session_start(); // Starting Session
$error=''; // Variable To Store Error Message

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
//echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr); //There is a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the
// connection handle
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        $success = False;
    } else {

    }
    return $statement;

}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes a same statement will be excuted for severl times, only
    the value of variables need to be changed.
    In this case you don't need to create the statement several times;
    using bind variables can make the statement be shared and just
    parsed once. This is also very useful in protecting against SQL injection. See example code below for       how this functions is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
//echo $val;
//echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype

        }
        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }

}

function printResult($result) { //prints results from a select statement
    echo "<br>Got data from table tab1:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }
    echo "</table>";

}

if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    }
    else
    {
// Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = OCILogon("ora_a6g0b", "a28558147", "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");
// To protect MySQL injection for Security purpose
        $username = stripslashes($username);
        $password = stripslashes($password);
        $username = prepare($username);
        $password = prepare($password);
// Selecting Database
        if ($db_conn) {
// SQL query to fetch information of registerd users and finds user match.
            global $query;
            $select = $_POST['type'];
            $student = "Student";
            if (strcmp($select, $student) !== 0) {
                $query = executePlainSQL("select * from student where password='$password' AND username='$username'");
            } else {
                $query = executePlainSQL("select * from professor where password='$password' AND username='$username'");
            }
            $rows = oci_fetch_array($query);
            if ($rows == 1) {
                $_SESSION['login_user'] = $username; // Initializing Session
                header("location: profile.php"); // Redirecting To Other Page
            } else {
                $error = "Username or Password is invalid";
            }
            OCILogoff($db_conn);
        }
        else {
            echo "cannot connect";
            $e = OCI_Error(); // For OCILogon errors pass no handle
            echo htmlentities($e['message']);
        }
    }
}
?>
<div class="large-12 columns text-center">
    <img src="css/images/g4logo@.5x.png" id="logo" alt="School Welcome">
    <h1>Login</h1>
</div>
<div class="large-6 medium-6 small-6 text-left small-centered columns">
    <form method="post" action="login.php">
        <input type="text" id="code" placeholder="Username" name="username"/>
        <input type="password" id="password" name="password" placeholder="*****"/>
        <div class="large-6 medium-6 small-6 columns small-centered text-center">
            I am a <select name="type">

                <option name="select" value="select"> Select</option>
                <option name="student" value="Student">Student</option>
                <option name="professor" value="Instructor">Instructor</option>
            </select>
        </div>
        <input type="submit" name="submit" id="btnSubmit" value="Login">
    </form>
    <p><?php echo $error?></p>
</div>

</body>
</html>