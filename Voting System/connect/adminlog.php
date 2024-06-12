<?php
session_start();
error_reporting(E_ALL);

// Establish database connection
$connect = mysqli_connect("localhost", "root", "", "voting-system") or die("Connection failed!");

// Retrieve form data
$name= $_POST['name'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];

// Query database
$check = mysqli_query($connect, "SELECT * FROM Admin WHERE mobile='$mobile' AND password='$password'");
if (mysqli_num_rows($check) > 0) {
    $admindata = mysqli_fetch_array($check);

    // Fetch nominees data
    $nominees_query = mysqli_query($connect, "SELECT * FROM User WHERE role = 2");
    $nominees_data = mysqli_fetch_all($nominees_query, MYSQLI_ASSOC);

    $_SESSION['admindata'] = $admindata;
    $_SESSION['nomineedata'] = $nominees_data;
    echo '
    <script>
    window.location="../routes/admindash.php";
    </script>';
   
} else {
    echo '
    <script>
    alert("Invalid User!");
    window.location="../";
    </script>';
}

// Close database connection
mysqli_close($connect);
?>
