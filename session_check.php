<?php
session_start();
if (!isset($_SESSION['UserName']))
{
    echo "<script> window.location='login.php'</script>";
    session_destroy();
}
//echo $_SESSION['UserName'];
?>