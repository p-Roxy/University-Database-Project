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
$servername = "localhost";
$username = "root";
$password = "Ch0c0l4t3s";

$conn = new mysql($servername, $username, $password);

$db = mysql_select_db("SchoolDB_group4", $conn);

$username = $_SESSION["login_name"];
$studentID = mysql_query("SELECT StudentID FROM Student WHERE Username = '$username'", $conn);
$tuition = mysql_query( "SELECT amountDue FROM Pays WHERE StudentID = $studentID", $conn);
$amountPaid = mysql_query( "SELECT amountPaid FROM Pays WHERE StudentID = $studentID", $conn);
$amountDue = mysql_query("SELECT (amountDue - amountPaid) FROM Pays WHERE StudentID = $studentID", $conn);



if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty($_POST['cardnum'])){
        $cardnumErr = 'Need a Card Number';
    } else {
        $cardnum = preg_replace('/\D/', $_POST['cardnum']);
        if ($_POST['cardtype'] == 0) {
            if (!preg_match("(4\d{12}(?:\d{3})?)")) {
                $cardnumErr = "invalid VISA number";
            }
        } else {
            if (!preg_match("(5[1-5]\d{14})")) {
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
        if (!preg_match("\d{2}/\d{2}",$exp)) {
            $expErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST['ccv'])){
        $ccvErr = 'Need a CCV';
    } else {
        $ccv = $_POST['ccv'];
        if (!preg_match("\d{3}",$ccv)) {
            $ccvErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST['payment'])){
        $paymentErr = 'Specify amount you would like to pay off';
    } else {
        $payment = $_POST['payment'] + $amountPaid;
        mysql_query( "UPDATE Pays Set amountPaid = $payment WHERE StudentID = $studentID", $conn);
        $amountDue = $tuition - $payment;
    }
}
?>
<div class="row nav">
    <div class="large-4, medium-4, small-4 columns">
        <img src="css/images/g4logo.png" id="nav" alt="School Welcome">
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
                        <?php
                        echo $tuition;
                        ?>
                        <h3 id="tuition"></h3>
                    </div>
                    <h5>Total tuition </h5>
                </div>
                <div class="large-4 small-4 columns">
                    <div class="numIncrease">
                        <?php
                        echo $amountPaid;
                        ?>
                        <h3 id="amountPaid"></h3>
                    </div>
                    <h5>Amount paid</h5>
                </div>
                <div class="large-4 small-4 columns">
                    <div class="numIncrease">
                        <?php
                        echo $amountDue;
                        ?>
                        <h3 id="amountDue"></h3>
                    </div>
                    <h5>Remaining balance</h5>
                </div>
                </div>
            </div>
        </div>
        <div class="large-4 columns text-center paymentCard">
                <h4>Payment Method</h4>
                <p>To complete the payment, please insert card information below.</p>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <input type="text" name="payment" placeholder="amount">
                <label>Visa / MasterCard</label>
                <input type="range" min="0" max="1" step="1" name="cardtype">
                    <div class="row">
                        <div class="large-12 column">
                            <input type="text" name="cardnum" placeholder="Card Number">
                            <input type="text" name="name" placeholder="Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-8 columns small-centered">
                        <div class="large-6 small-6 columns">
                            <input type="text" placeholder="MM/YY" name="exp">
                        </div>
                        <div class="large-6 small-6 columns">
                           <input type="text" placeholder="CCV" name="ccv">
                        </div>
                        </div>
                    </div>
                </form>
                <button>Submit</button>
        </div>
    </div>
</div>
</body>
</html>