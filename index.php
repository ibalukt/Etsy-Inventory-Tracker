<?php
    session_start();
    include_once("classes/DbConfig.php");
    include_once("classes/Crud.php");
    $crud = new crud();

    //echo $_SESSION['UserName'];
    
    if (!isset($_SESSION['UserName']))
    {
        echo "<script> window.location='login.php'</script>";
    };

    if (isset($_GET['done']))
    {
        session_destroy();
    }
?>
<html>
    <head>
        <?php include_once('bootstrap.php'); ?>
    </head>
<body>
    <!-- A grey horizontal navbar that becomes vertical on small screens -->
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <!-- Brand -->
        <a class="navbar-brand" href="#">Etsy Tracker</a>

        <!-- Links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item ml-auto">
                <a class="nav-link" href="#" >Logout</a>
            </li>
        </ul>
    </nav>
</body>
</html>