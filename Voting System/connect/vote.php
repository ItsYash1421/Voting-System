<?php
session_start();
$connect = mysqli_connect("localhost", "root", "", "voting-system") or die("connection fail!");

$vote = $_POST['gvotes'];
$total = $vote + 1;
$gid = $_POST['gid'];
$uid = $_SESSION['userdata']['id'];

$update_votes = mysqli_query($connect, "UPDATE User SET votes='$total' WHERE id='$gid'");
$update_status = mysqli_query($connect, "UPDATE User SET status=1 WHERE id='$uid'");

if ($update_votes && $update_status) {
    $nominy = mysqli_query($connect, "SELECT * FROM User WHERE role=2");
    $nominydata = mysqli_fetch_all($nominy, MYSQLI_ASSOC);

    $_SESSION['userdata']['status'] = 1;
    $_SESSION['nominydata'] = $nominydata;

    echo '
    <script>
    alert("Voting Successful!");
    window.location="../routes/dashboard.php";
    </script>';
} else {
    echo '
    <script>
    alert("Some Error Occurred!");
    window.location="../routes/dashboard.php";
    </script>';
}
?>
