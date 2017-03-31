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
<?php

//this tells the system that it's no longer just parsing
//html; it's now parsing PHP

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
//Commit to save changes...
OCILogoff($db_conn);
} else {
echo "cannot connect";
$e = OCI_Error(); // For OCILogon errors pass no handle
echo htmlentities($e['message']);
}
?>
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