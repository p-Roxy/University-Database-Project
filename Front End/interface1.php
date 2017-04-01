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
    <form method="POST" action="interface1.php">
        <h1>Course information</h1>
        <p>Find course with credits:</p><br>

        <p><input type="text" name="credits" size="6"></p>
        <p><input type="submit" name="submit" id="submit" value="find"></p>
        <h6>The table will be shown below:</h6>
    </form>



</div>



<table >
    <style>

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        table{width:100%; border: 2px solid #ddd;
            text-align: left;}
        th{color:blue;   font-size: 140%; font-weight: bold;border: 1px solid #ddd;
            text-align: left; padding: 15px;}
        td{color:black; font-size: 140%; font-weight:bold; border: 1px solid #ddd;
            text-align: center; padding: 10px;}

    </style>
    <thead>


    <th>Course ID	</th>
    <th>Subject	</th>
    <th>Credits		</th>
    <th>profID	</th>
    <th>prof name	</th>
    <th>office	</th>
    <th>prof Rating	</th>



    </thead>
    <tbody>



    </tbody>
</table>

<?php
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

function printResult($res1) { //prints results from a select statement

    echo "<table>";


    while ($array1 = OCI_Fetch_Array($res1, OCI_BOTH)) {
        echo "<td>".$array1[0]."</td>";
        echo "<td>".$array1[1]."</td>";
        echo "<td>".$array1[2]."</td>";
        echo "<td>".$array1[4]."</td>";
        echo "<td>".$array1[5]."</td>";
        echo "<td>".$array1[6]."</td>";
        echo "<td>".$array1[7]."</td>";
    }
    echo "</table>";

}

// Connect Oracle...
if ($db_conn) {
    $t = $_POST['credits'];
    $int = (int)$t;
    if ($int == 0){
        $prof = executePlainSQL("SELECT CourseID FROM Course");}
    else {
        $prof = executePlainSQL("SELECT CourseID FROM Course WHERE Course.Credits=$int");
    }
    $counter = 0;
    while ($array = OCI_Fetch_Array($prof, OCI_BOTH)) {
        $newarray[$counter] = $array[0];

        $counter = $counter + 1;
    }
    if ($counter == 0){
        echo "No courses found!";
    }

    if (array_key_exists('submit', $_POST)){
        echo array_key_exists('submit');
        $i=0;

        while($counter>0)
        {   $num = $newarray[$i];
            $res1 = executePlainSQL("SELECT * FROM Course c LEFT OUTER JOIN Professor t ON t.profID = c.profID WHERE c.CourseID='$num'");
            printResult($res1);
            $counter=$counter-1;
            $i=$i+1;
        }

        OCICommit($db_conn);
    }


    if ($_POST && $success) {
//        && array_key_exists('submit', $_POST)
//        //POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
//        header("location: interface1.php");
    } else {
        // Select data...
        $t = $_POST['credits'];
        $int = (int)$t;
        if ($int == 0){
            $prof = executePlainSQL("SELECT CourseID FROM Course");}
        else {
            $prof = executePlainSQL("SELECT CourseID FROM Course WHERE Course.Credits=$int");
        }

        $counter = 0;
        while ($array = OCI_Fetch_Array($prof, OCI_BOTH)) {
            $newarray[$counter] = $array[0];

            $counter = $counter + 1;
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
</body>
</html>
