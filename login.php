<?php

session_start();

$connect = mysqli_connect("localhost", "root", "", "voting-system") or die("connection fail!");

// Retrieve form data
$mobile = $_POST['mobile'];
$pass = $_POST['password'];
$role = $_POST['role'];

// Validate mobile number length
if (strlen($mobile) != 10) {
    echo '
    <script>
    alert("Invalid Mobile Number!");
    window.location="../";
    </script>';
    exit; // Terminate script execution
}

// Validate password minimum length
if (strlen($pass) < 6) {
    echo '
    <script>
    alert("Password should be at least 6 characters long!");
    window.location="../";
    </script>';
    exit; // Terminate script execution
}

// Query database
$check = mysqli_query($connect, "SELECT * FROM User WHERE mobile='$mobile' AND password='$pass' AND role='$role'");
if (mysqli_num_rows($check) > 0) {
    $userdata = mysqli_fetch_array($check);
    $nominy = mysqli_query($connect, "SELECT * FROM User WHERE role=2");
    $nominydata = mysqli_fetch_all($nominy, MYSQLI_ASSOC);

    $_SESSION['userdata'] = $userdata;
    $_SESSION['nominydata'] = $nominydata;

    echo '
    <script>
    window.location="../routes/dashboard.php";
    </script>';
} else {
    echo '
    <script>
    alert("Invalid User!");
    window.location="../";
    </script>';
}
?>
