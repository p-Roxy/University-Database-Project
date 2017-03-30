<!doctype html>
<html class="no-js" lang="en" dir="ltr">
<head>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "Ch0c0l4t3s!";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
        echo "Connected successfully";
    ?>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>304 Project Final</title>
    <link rel="stylesheet" href="css/app.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/project.js" rel="script"></script>
</head>
<body>
<div class="row nav">
    <div class="large-4, medium-4, small-4 columns">
        <img src="css/images/g4logo.png" id="nav" alt="School Welcome">
    </div>
    <div class="large-8, medium-8, small-8 columns">
        <ul>
            <a href="/index.html" class="nav"><li>Sign out</li></a>
            <a href="/index.html" class="nav"><li>Research</li></a>
            <a href="/index.html" class="nav"><li>Courses</li></a>
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
                        <h3 id="tuition"></h3>
                    </div>
                    <h5>Total tuition </h5>
                </div>
                <div class="large-4 small-4 columns">
                    <div class="numIncrease">
                        <h3 id="amountPaid"></h3>
                    </div>
                    <h5>Amount paid</h5>
                </div>
                <div class="large-4 small-4 columns">
                    <div class="numIncrease">
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
                <label>Debit / Credit</label>
                <input type="range" min="0" max="1" step="1">
                <form>
                    <div class="row">
                        <div class="large-12 column">
                            <input type="text" placeholder="Card Number">
                            <input type="text" placeholder="Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="large-8 columns small-centered">
                        <div class="large-6 small-6 columns">
                            <input type="text" placeholder="MM/YY">
                        </div>
                        <div class="large-6 small-6 columns">
                           <input type="text" placeholder="CCV">
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