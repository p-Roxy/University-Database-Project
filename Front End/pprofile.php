<?php
/**
 * Created by PhpStorm.
 * User: Roxy
 * Date: 2017-03-31
 * Time: 1:54 PM
 */
$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = OCILogon("ora_a6g0b", "a28558147", "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = dbhost.ugrad.cs.ubc.ca)(PORT = 1522)))(CONNECT_DATA=(SID=ug)))");

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

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Connect Oracle...
if ($db_conn) {
    $username = "gPotts";//$_SESSION["login_name"];
    $res = executePlainSQL("SELECT StudentID, StudentName FROM Student WHERE Username = '$username'");
    $array = oci_fetch_array($res);
    $studentID = (int)$array[0];
    $sname = $array[1];

    $res0 = executePlainSQL( "SELECT amountDue, amountPaid, (amountDue-amountPaid) FROM Pays WHERE StudentID =".$studentID);
    $array1 = oci_fetch_array($res0);
    $tuition = $array1[0];
    $amountPaid = $array1[1];
    $amountDue = $array1[2];

    $cardnumErr = $ccvErr = $nameErr = $expErr = $paymentErr = '';

    if (array_key_exists('submit', $_POST)) {

        if (empty($_POST['cardnum'])) {
            $cardnumErr = "Invalid card number";
        } else {
            $cardnum = test_input($_POST['cardnum']);
            if ($_POST['cardtype'] == 0) {
                if (!preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $cardnum)) {
                    $cardnumErr = "invalid VISA number";
                }
            } else {
                if (!preg_match("/^5[1-5][0-9]{14}$/", $cardnum)) {
                    $cardnumErr = "invalid MasterCard number";
                }
            }
        }
        if (empty($_POST['name'])){
            $nameErr = 'Need a name';
        } else {
            $name = $_POST['name'];
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }
        if (empty($_POST['exp'])){
            $expErr = 'Need an expiration date';
        } else {
            $exp = $_POST['exp'];
            if (!preg_match("/([0-9]{2})\/([0-9]{2})/",$exp)) {
                $expErr = "Only numbers and a slash allowed";
            }
        }
        if (empty($_POST['ccv'])){
            $ccvErr = 'Need a CCV';
        } else {
            $ccv = $_POST['ccv'];
            if (!preg_match("/[0-9]{3}/",$ccv)) {
                $ccvErr = "invalid ccv number";
            }
        }

        if ($cardnumErr = '' && $ccvErr = '' && $nameErr = '' && $expErr = '' && $paymentErr = '' && ($_POST['payment'] >= 0)) {
            $tuple = array (
                ":bind1" => $_POST['payment'] + $amountPaid,
                ":bind2" => $amountDue - $_POST['payment'],
                ":bind3" => $studentID
            );
            $alltuples = array (
                $tuple
            );
            executeBoundSQL("update pays set amountDue=:bind2, amountPaid=:bind1 where studentid=:bind3", $alltuples);
            OCICommit($db_conn);
            $res0 = executePlainSQL( "SELECT amountDue, amountPaid, (amountDue-amountPaid) FROM Pays WHERE StudentID =".$studentID);
            $array1 = oci_fetch_array($res0);
            $tuition = $array1[0];
            $amountPaid = $array1[1];
            $amountDue = $array1[2];

        }
    }



    if ($_POST && $success) {
        //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
        header("location: interface3.php");
    } else {
        // Select data...
        $result = executePlainSQL("select * from pays");
    }

    //Commit to save changes...
    OCILogoff($db_conn);
} else {
    echo "cannot connect";
    $e = OCI_Error(); // For OCILogon errors pass no handle
    echo htmlentities($e['message']);
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
</head>
<body>

<div class="row nav">
    <div class="large-4, medium-4, small-4 columns">

        <img src="css/images/g4logo.png" id="nav" alt="School Welcome">
        <p>Welcome <?php echo $sname;?></p>
    </div>
    <div class="large-8, medium-8, small-8 columns">
        <ul>
            <li><a href="/index.html" class="nav">Sign out</a></li>
            <li><a href="/interface3.html" class="nav">Payment</a></li>
            <li><a href="/interface4.html" class="nav">Research</a></li>
        </ul>
    </div>
</div>
<div class="large-12 columns text-center">
    <h1>Course information</h1>
    <h6>The table will be shown below:</h6>
    <table id = "tblResults"></table>
</div>
<script>


    function  generateTable(data) {
        var tbl_body = document.createElement("tbody");
        var odd_even = false;
        console.log("DATA",data);
        $.each(data,function () {
            var tbl_row = tbl_body.insertRow();
            tbl_row.className = odd_even ? "odd" : "even";
            $.each(this, function (k, v) {
                var cell = tbl_row.insertCell();
                cell.appendChild(document.createTextNode(v.toString()));
            })
            odd_even = !odd_even;
        })
        document.getElementById("tblResults").appendChild(tbl_body);
    }</script>
</body>
</html>
