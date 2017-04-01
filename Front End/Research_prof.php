<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Researches</title>
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<div class="large-12 columns">
    <div class="row  mobile-open" >
        <div class="large-10 columns text-center">
            <h1>My Researches</h1>
            <h2>-----------------------------------------------------------------------</h2>
        </div>
    </div>
    <body>
    <table >
        <style>
	

	    table{width:100%;
    text-align: left;}
            th{color:blue;   font-size: 140%; font-weight: bold;
    text-align: left; padding: 15px;}
            td{color:black; font-size: 140%; font-weight:bold; 
    text-align: left; padding: 10px;}

        </style>
        <thead>

       
            <th>Research ID	</th>
            <th>Thesis		</th>
            <th>University Grant</th>
            <th>Location	</th>
	    <th>TA involved	</th>
       


        </thead>
	<tbody>
		
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
	
	
	

	while ($array1 = OCI_Fetch_Array($res1, OCI_BOTH)) {
 	echo "<td>".$array1[0]."</td>";
 	echo "<td>".$array1[1]."</td>";
 	echo "<td>".$array1[2]."</td>";
 	echo "<td>".$array1[3]."</td>";
 	echo "<td>".$array1[4]."</td>";
	}
	

}

// Connect Oracle...
if ($db_conn) {

	
		OCICommit($db_conn);
	//$username = "gPotts";//$_SESSION["login_name"];
    //$res = executePlainSQL("SELECT profID FROM Professor WHERE Username = '$username'");
    //$array = oci_fetch_array($res);
    $prof = 22;
    //echo $prof;
	

	if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		header("location: research.php");
	} else {
		// Join data...
		$res1 = executePlainSQL("SELECT Research.rID,Research.Thesis, Research.reGrant, Research.labLocation, TA_Helps_Research.TAID FROM Research INNER JOIN TA_Helps_Research ON Research.rID=TA_Helps_Research.rID WHERE Research.profID=".$prof);
		 
		 printResult($res1);
}
    

    
	

	//Commit to save changes...
	OCILogoff($db_conn);
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}
?>

</tbody>
</table>
</body>
</div>
</body>
</html>