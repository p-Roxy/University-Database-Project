<!doctype html>
<html class="no-js" lang="en" dir="ltr">
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
$username = "khughes";//$_SESSION["login_name"];

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
		$paymentErr = "Cannot have negative amount paid";
	}

	$r = OCIExecute($statement, OCI_DEFAULT);
	if (!$r) {
		echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
		$e = oci_error($statement); // For OCIExecute errors pass the statementhandle
		echo htmlentities($e['message']);
		$success = False;
		$paymentErr = "Cannot have negative amount paid";
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
			$paymentErr = "Cannot have negative amount paid";
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
			$paymentErr = "Cannot have negative amount paid";
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
	
	global $username;
$res = executePlainSQL("SELECT StudentID, StudentName FROM Student WHERE Username = '$username'");
$array = oci_fetch_array($res);
$studentID = (int)$array[0];
$sname = $array[1];

$res1 = executePlainSQL("select sum(f.amountdue) from fees f, takes t, course c where t.studentid = $studentID and c.courseid = t.courseid and f.courseid = t.courseid group by t.studentid");
$array2 = oci_fetch_array($res1);
$tuition = $array2[0];

$res0 = executePlainSQL("SELECT amountPaid from pays where studentid = $studentID");
$array1 = oci_fetch_array($res0);
$amountPaid = $array1[0];
$amountDue = $tuition - $amountPaid;


	if (array_key_exists('submit', $_POST)) {
			if (empty($_POST['cardnum'])) {
				$cardnumErr = "Invalid card number";
				$success = False;
			} else {
				$cardnum = test_input($_POST['cardnum']);
				if ($_POST['cardtype'] == 0) {
					if (!preg_match("/^4[0-9]{12}(?:[0-9]{3})?$/", $cardnum)) {
						$cardnumErr = "invalid VISA number";			
						$success = False;
					} 
				} else {
					if (!preg_match("/^5[1-5][0-9]{14}$/", $cardnum)) {
						$cardnumErr = "invalid MasterCard number";
						$success = False;
					}
				}
				
			}
			if (empty($_POST['name'])){
				$nameErr = 'Need a name';
				$success = False;
			} else {
				$name = $_POST['name'];
				if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
					$nameErr = "Only letters and white space allowed";
					$success = False;
				}
			}
			if (empty($_POST['exp'])){
				$expErr = 'Need an expiration date';
				$success = False;
			} else {
				$exp = $_POST['exp'];
				if (!preg_match("/([0-9]{2})\/([0-9]{2})/",$exp)) {
					$expErr = "Only numbers and a slash allowed";
					$success = False;
				}
			}
			if (empty($_POST['ccv'])){
				$ccvErr = 'Need a CCV';
				$success = False;
			} else {
				$ccv = $_POST['ccv'];
				if (!preg_match("/[0-9]{3}/",$ccv)) {
					$ccvErr = "invalid ccv number";
					$success = False;
				}
			}
			
			if ($success) {
                if ($_POST['payment'] < 0) {
                    $paymentErr = 'Invalid payment';
                }

				$tuple = array (
					":bind1" => $_POST['payment'] + $amountPaid,
					":bind2" => $studentID
				);
				$alltuples = array (
					$tuple
				);
				executeBoundSQL("update pays set  amountPaid=:bind1 where studentid=:bind2", $alltuples);
				

				

			} 
				OCICommit($db_conn);
			
		}
		if ($_POST && $success) {
		//POST-REDIRECT-GET -- See http://en.wikipedia.org/wiki/Post/Redirect/Get
		$paymentErr ='';
		header("location: interface3.php");
	} else {
		
	}
} else {
	echo "cannot connect";
	$e = OCI_Error(); // For OCILogon errors pass no handle
	echo htmlentities($e['message']);
}

	//Commit to save changes...
	OCILogoff($db_conn);
	


/* OCILogon() allows you to log onto the Oracle database
     The three arguments are the username, password, and database
     You will need to replace "username" and "password" for this to
     to work. 
     all strings that start with "$" are variables; they are created
     implicitly by appearing on the left hand side of an assignment 
     statement */

/* OCIParse() Prepares Oracle statement for execution
      The two arguments are the connection and SQL query. */
/* OCIExecute() executes a previously parsed statement
      The two arguments are the statement which is a valid OCI
      statement identifier, and the mode. 
      default mode is OCI_COMMIT_ON_SUCCESS. Statement is
      automatically committed after OCIExecute() call when using this
      mode.
      Here we use OCI_DEFAULT. Statement is not committed
      automatically when using this mode */

/* OCI_Fetch_Array() Returns the next row from the result data as an  
     associative or numeric array, or both.
     The two arguments are a valid OCI statement identifier, and an 
     optinal second parameter which can be any combination of the 
     following constants:

     OCI_BOTH - return an array with both associative and numeric 
     indices (the same as OCI_ASSOC + OCI_NUM). This is the default 
     behavior.  
     OCI_ASSOC - return an associative array (as OCI_Fetch_Assoc() 
     works).  
     OCI_NUM - return a numeric array, (as OCI_Fetch_Row() works).  
     OCI_RETURN_NULLS - create empty elements for the NULL fields.  
     OCI_RETURN_LOBS - return the value of a LOB of the descriptor.  
     Default mode is OCI_BOTH.  */
?>

<div class="row nav">
    <div class="large-4, medium-4, small-4 columns">
        <img src="css/images/g4logo.png" id="nav" alt="School Welcome">
        <p>Welcome <?php echo $sname;?></p>
    </div>
    <div class="large-8, medium-8, small-8 columns">
        <ul>
            <a href="/index.html" class="nav"><li>Sign out</li></a>
            <a href="/index.html" class="nav"><li>Research</li></a>
            <a href="/interface1.html" class="nav"><li>Courses</li></a>
        </ul>
    </div>
</div>
<div class="large-12 columns">
    <div class="row " >
        <div class="large-12 columns text-center">
            <h3>Tuition Payments and Course Fees</h3>
        </div>
    </div>
    <div class="row">
        <div class="large-8 columns text-center">
                <h4>Details</h4>
            <div class="row">
                <div class="large-12 columns">
                <div class="large-4 small-4 columns">
                    <div class="numIncrease">
                        <b>
                        <?php
                        echo '$'.$tuition;
                        ?>
                        </b>
                        <h3 id="tuition"></h3>
                    </div>
                    <h5>Total tuition </h5>
                </div>
                <div class="large-4 small-4 columns">
                    <div class="numIncrease">
                    <b>
                        <?php
                        echo '$'.$amountPaid;
                        ?>
                        </b>
                        <h3 id="amountPaid"></h3>
                    </div>
                    <h5>Amount paid</h5>
                </div>
                <div class="large-4 small-4 columns">
                    <div class="numIncrease">
                        <b>
                        <?php
                        echo '$'.$amountDue;
                        ?>
                        </b>
                        <h3 id="amountDue"></h3>
                    </div>
                    <h5>Remaining balance</h5>
                </div>
                </div>
            </div>
        </div>
        <div class="large-4 columns text-center paymentCard">
                <h4 style="color: white">Payment Method</h4>
                <form method="post" action="interface3.php">
                <input type="text" name="payment" placeholder="amount">
                <p id="error"><?php echo $paymentErr; ?></p>
                <label style="color: white;">Visa  |  MasterCard</label>
                <input type="range" min="0" max="1" step="1" name="cardtype">
                    <div class="row">
                        <div class="large-12 column">
                            <input type="text" name="cardnum" placeholder="Card Number">
                <p id="error"><?php echo $cardnumErr?></p>
                            <input type="text" name="name" placeholder="Name">
                <p id="error"><?php echo $nameErr?></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-8 columns small-centered">
                        <div class="large-6 small-6 columns">
                            <input type="text" placeholder="MM/YY" name="exp">
                <p id="error"><?php echo $expErr?></p>
                        </div>
                        <div class="large-6 small-6 columns">
                           <input type="text" placeholder="CCV" name="ccv">
                <p id="error"><?php echo $ccvErr?></p>
                        </div>
                        </div>
                    </div>
                <input type="Submit" name="submit" value="submit">
                </form>
                <p id="error"><?php echo $paymentErr?></p>
        </div>
    </div>
</div>
</body>
</html>
