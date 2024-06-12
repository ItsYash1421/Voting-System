<?php

$connect = mysqli_connect("localhost", "root", "", "voting-system") or die("connection fail!");

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

$name=$_POST['name'];
$mobile=$_POST['mobile'];
$password=$_POST['password'];
$cpassword=$_POST['cpassword'];
$address=$_POST['address'];
$photo=$_FILES['photo']['name'];
$tmp_name= $_FILES['photo']['tmp_name'];
$role =$_POST['role'];
// Validate mobile number length
if (strlen($mobile) != 10) {
    echo '
    <script>
    alert("Mobile number should be of length 10!");
    window.location="../routes/register.html";
    </script>';
    exit; 
}

// Validate password minimum length
if (strlen($password) < 5) {
    echo '
    <script>
    alert("Password should be at least 6 characters long!");
    window.location="../routes/register.html";
    </script>';
    exit;
}
if($password==$cpassword)
{
 move_uploaded_file($tmp_name,"../Upload/$photo");
 $insert= mysqli_query($connect,"INSERT INTO User(name,mobile,password,address,photo,role,status,votes) VALUES('$name','$mobile','$password','$address','$photo','$role',0,0)");
 if ($insert) {
    echo '
    <script>
    alert("Registration Successful!");
    window.location="../";
    </script>';
} 
else
 {
    echo '
    <script>
    alert("Error: ' . mysqli_error($connect) . '");
    window.location="../routes/register.html";
    </script>';
}
} 
else 
{
echo '
<script>
alert("Password Not Match!");
window.location="../routes/register.html";
</script>';
}
?>