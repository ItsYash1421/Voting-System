<?php
session_start();
require_once "../connect/connect.php"; // Include your database connection file

// Redirect to admin login if session is not set
if (!isset($_SESSION['admindata'])) {
    echo '
    <script>
    window.location="../connect/adminlog.php";
    </script>';
    exit; // Stop further execution
}

// Update resultstatus to 1 in the Admin table
$update_query = "UPDATE Admin SET resultstatus = 1 WHERE id = 1"; // Assuming 'id' is the primary key of the Admin table
mysqli_query($connect, $update_query);

// Fetch admin and nominees data from session
$admin = $_SESSION['admindata'];
$nominees = $_SESSION['nomineedata'];

// Calculate total votes
$total_votes = 0;
foreach ($nominees as $nominee) {
    $total_votes += $nominee['votes'];
}

// Find the nominee with the highest votes (winner)
$winner = null;
$max_votes = 0;
foreach ($nominees as $nominee) {
    if ($nominee['votes'] > $max_votes) {
        $max_votes = $nominee['votes'];
        $winner = $nominee;
    }
}
$_SESSION['winner']=$winner;
// Sort nominees by votes in descending order
usort($nominees, function($a, $b) {
    return $b['votes'] - $a['votes'];
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styledash.css">
    <style>
        /* CSS for button */
        input[type="submit"]:hover {
            background-color: #7b1fa2;
            transform: scale(1.05);
        }

        /* CSS for body */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Header styling */
        #head {
            padding-top: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 80%;
        }

        h1 {
            font-size: 36px;
            color: #fff;
        }

        /* Horizontal rule styling */
        hr {
            width: 80%;
            border: none;
            border-top: 2px solid #fff;
            margin: 20px 0;
        }

        /* Button styling */
        #head button {
            background-color: #4a148c;
            border: none;
            padding: 10px 20px;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #head button:hover {
            background-color: #7b1fa2;
        }

        /* Winner section styling */
        #winner {
            width: 600px;
            background-color: #000;
            color: #fff;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            text-align: center;
        }

        #winner img {
            padding-top: 20px;
            padding-bottom: 20px;
            max-width: 150px;
            border-radius: 30%;
        }

        /* Nominees section styling */
        #nominees {
            margin: 50px;
        }

        .nominee-item {
            background-color: #fff;
            padding: 20px;
            margin: 10px 0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .nominee-item img {
            max-width: 200px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .nominee-item-content {
            flex: 1;
        }

        h1,
        h2 {
            margin: 0;
        }

        .written-winning-percentage {
            color: #7b1fa2;
            font-weight: bold;
        }
    </style>
    <title>Election Result</title>
</head>
<body>
    <div id="head">
        <a href="../"><button id="back">Back</button></a>
        <h1>Admin Dashboard</h1>
        <a href="../connect/logout.php"><button id="logout">Logout</button></a>
    </div>
    <hr>

    <?php if ($winner): ?>
    <div id="winner">
        <h2>Winner: <?php echo $winner['name']; ?></h2>
        <img src="../Upload/<?php echo $winner['photo']; ?>" alt="Winner Photo">
        <p><b>Votes:</b> <?php echo $winner['votes']; ?></p>
    </div>
    <?php endif; ?>

    <div id="nominees">
        <h2>Nominees</h2>
        <?php if ($nominees): ?>
            <?php foreach ($nominees as $nominee): ?>
            <div class="nominee-item">
                <img src="../Upload/<?php echo $nominee['photo']; ?>" alt="Nominee Photo">
                <div class="nominee-item-content">
                    <h3><?php echo $nominee['name']; ?></h3>
                    <p><b>Votes:</b> <?php echo $nominee['votes']; ?></p>
                    <div class="written-winning-percentage">
                        Votes Gained: <?php echo round(($nominee['votes'] / $total_votes) * 100, 2); ?>%
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
