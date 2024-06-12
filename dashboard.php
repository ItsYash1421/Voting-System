<?php
session_start();
if (!isset($_SESSION['userdata'])) {
    header("location:../");
}
$user = $_SESSION['userdata'];
$nominy = $_SESSION['nominydata'];

// Calculate total votes
$total_votes = 0;
foreach ($nominy as $n) {
    $total_votes += $n['votes'];
}

$status = $user['status'] == 0 ? '<b style="color:red">Not Voted</b>' : '<b style="color:green">Voted</b>';

// Check if the result has been declared
$result_declared = false; // Assume result not declared initially
require_once "../connect/connect.php"; // Include your database connection file
$result_query = "SELECT resultstatus FROM Admin WHERE id = 1"; // Assuming 'id' is the primary key of the Admin table
$result_result = mysqli_query($connect, $result_query);
if ($result_result) {
    $result_row = mysqli_fetch_assoc($result_result);
    if ($result_row['resultstatus'] == 1) {
        $result_declared = true;
    }
}

// Initialize winner information
$winner_name = "";
$winner_photo = "";
$winner_votes = 0;

// Calculate winner if result is declared
if ($result_declared) {
    // Find the nominee with the highest votes (winner)
    foreach ($nominy as $nominee) {
        if ($nominee['votes'] > $winner_votes) {
            $winner_name = $nominee['name'];
            $winner_photo = $nominee['photo'];
            $winner_votes = $nominee['votes'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styledash.css">
    <title>Dashboard</title>
    <style>
        /* Inline CSS for styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        /* Add your existing styles here */

    </style>
</head>
<body>
    <div id="head">
        <!-- Header section -->
        <a href="../"><button id="back">Back</button></a>
        <h1>Online Voting System</h1>
        <a href="../connect/logout.php"><button id="logout">Logout</button></a>
    </div>
    <hr>
    <div id="profile" style="width: 500px;float:right;">
        <!-- Profile section -->
        <img src="../Upload/<?php echo $user['photo']; ?>" alt="Profile Photo">
        <div>
            <div><b>Name:</b> <?php echo $user['name']; ?></div><br>
            <div><b>Mobile:</b> <?php echo $user['mobile']; ?></div><br>
            <div><b>Address:</b> <?php echo $user['address']; ?></div><br>
            <div><b>Status:</b> <?php echo $status; ?></div>
            <!-- Display winner information if result is declared -->
        </div>
    </div>
    <div >
    <?php if ($result_declared): ?>
        <div id="winshow">
                <div><b>Winner: <?php echo $winner_name; ?></b></div>
                <img src="../Upload/<?php echo $winner_photo; ?>" alt="Winner Photo">
                <div><b>Votes: <?php echo $winner_votes; ?></b>
                </div></div>
            <?php else: ?>
                <div id="winshow2"><b style="color:black;">Result : </b>Not declared yet</div>
            <?php endif; ?>
    </div>

    <div id="nominy">
        <?php 
        if ($nominy) {
            foreach ($nominy as $n) {
                $percentage = $total_votes > 0 ? round(($n['votes'] / $total_votes) * 100, 2) : 0;
                ?>
                <div class="nominee-item">
                    <div class="nominee-item-content">
                        <h2><b>Party Name: </b><?php echo $n['name']; ?></h2><br>
                        <p><b>Votes: </b><?php echo $n['votes']; ?></p><br>
                        <div class="written-winning-percentage">
                        <b>Winning Possibility:</b> <?php echo $percentage; ?>
                        </div>
                    </div>
                    <form action="../connect/vote.php" method="POST">
                        <input type="hidden" name="gvotes" value="<?php echo $n['votes']; ?>">
                        <input type="hidden" name="gid" value="<?php echo $n['id']; ?>">
                        <?php if ($user['status'] == 0) { ?>
                            <input type="submit" name="votebtn" value="Vote" id="votebt">
                        <?php } else { ?>
                            <button disabled type="button" name="votebtn" value="Vote" id="voted">VOTED</button>
                        <?php } ?>
                        <img src="../Upload/<?php echo $n['photo']; ?>" alt="Party Photo">
                    </form>
                </div>
                <hr>
                <?php
            }
        }
        ?>
    </div>

    <div id="winning-possibility">
        <h2>Winning Possibility</h2>
        <?php
        if ($nominy) {
            foreach ($nominy as $n) {
                $percentage = $total_votes > 0 ? round(($n['votes'] / $total_votes) * 100, 2) : 0;
                ?>
                <div class="winning-bar">
                    <img src="../Upload/<?php echo $n['photo']; ?>" alt="Party Logo">
                    <div class="party-name"><?php echo $n['name']; ?></div>
                    <div class="progress-bar">
                        <div class="progress-bar-inner" style="width: <?php echo $percentage; ?>%;">
                            <?php echo $percentage; ?>%
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
    
</body>
</html>
