<?php
session_start(); // Starting Session

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
<?php
$error=''; // Variable To Store Error Message

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_a6g0b", "a28558147", "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");
$username = $password = $select = '';

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

// Connect Oracle...
if ($db_conn) {

    if (array_key_exists('submit', $_POST)) {

        if (empty($_POST['username']) || empty($_POST['password'])) {
            $error = 'Invalid login information';
        } else {
            $username = stripslashes($_POST['username']);
            $profid = stripslashes($_POST['profid']);
            $password = stripslashes($_POST['password']);
            $query = executePlainSQL("select * from ta t, student s where s.username='$username' and s.password='$password' and t.taid='$profid'");
            $success = false;
            while($row = oci_fetch_array($query)){
                $success = true;
            }
            if($success == true) {
                $_SESSION['tauser'] = $username;
                header("www.ugrad.cs.ubc.ca/~a6g0b");
                exit();
            } else {
                $error = 'Invalid login information';
            }
        }
    }

    //Commit to save changes...
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);

}
?>
<div class="large-12 columns text-center">
    <img src="css/images/g4logo@.5x.png" id="logo" alt="School Welcome">
    <h1>Login</h1>
</div>
<div class="large-6 medium-6 small-6 text-left small-centered columns">
    <form method="post" action="talogin.php">
        <input type="text" id="code" placeholder="Username" name="username"/>
        <input type="text" id="code" placeholder="ID" name="profid"/>
        <input type="password" id="password" name="password" placeholder="*****"/>
        <div class="large-6 medium-6 small-6 columns small-centered text-center">
            <input type="submit" name="submit" id="btnSubmit" value="Login">
    </form>
    <p><?php echo $error?></p>
</div>

</body>
</html>