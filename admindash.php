<?php
session_start();
if (!isset($_SESSION['admindata'])) {
    echo '
    <script>
    window.location="../connect/adminlog.php";
    </script>';
}
$admin = $_SESSION['admindata'];
$nominees = $_SESSION['nomineedata'];

// Calculate total votes
$total_votes = 0;
foreach ($nominees as $nominee) {
    $total_votes += $nominee['votes'];
}
/* Total Votes */
$grandt=0;

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
    </style>
    <title>Admin Dashboard</title>
</head>
<body>
    <div id="head">
        <a href="../"><button id="back">Back</button></a>
        <h1>Admin Dashboard</h1>
        <a href="../connect/logout.php"><button id="logout">Logout</button></a>
    </div>
    <hr>
    <div id="profile" style="width: 500px; float:right;">
        <div>
            <div><b>Name:</b> <?php echo $admin['name']; ?></div><br>
            <div><b>Email:</b> <?php echo $admin['mobile']; ?></div><br>
            <div id="declare-result">
                <form action="../connect/declare_result.php" method="POST">
                    <b>Result:</b>
                    <input type="submit" name="declare_result" style="justify-content: center; margin: 0 10px; padding: 10px 20px; background-color: #4a148c; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; transition: background-color 0.3s, transform 0.3s;" value="Declare Result">
                </form>
            </div>
        </div>
    </div>

    <div id="nominees">
        <?php 
        if ($nominees) {
            foreach ($nominees as $nominee) {
                $percentage = $total_votes > 0 ? round(($nominee['votes'] / $total_votes) * 100, 2) : 0;
                ?>
                <div class="nominee-item">
                    <div class="nominee-item-content">
                        <h2><b>Party Name: </b><?php echo $nominee['name']; ?></h2><br>
                        <p><b>Votes: </b><?php echo $nominee['votes']; ?></p><br>
                        <div class="written-winning-percentage">
                            <b>Winning Possibility:</b> <?php echo $percentage; ?>%
                        </div>
                    </div>
                    <img src="../Upload/<?php echo $nominee['photo']; ?>" alt="Party Photo">
                </div>
                <?php
            }
        }
        ?>
    </div>
</body>
</html>
