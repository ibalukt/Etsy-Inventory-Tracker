<?php
session_start();
    //start the session
    include_once('../classes/Crud.php');


    //create a connection variable specifically for this php
    $crud = new crud();

    //If both the UserName and UserPass are created
    if ((isset($_POST['UserName'])) && (isset($_POST['UserPass'])))
    {        
        //prepare the statement
        $query = "SELECT UserName,UserPass FROM Users WHERE UserId = 1";
        $result = $crud->getData($query);

        //assign values to the parameters
        $UserName = $_POST['UserName'];
        $UserPass = $_POST['UserPass'];

        //execute the statement

        if ((password_verify($UserPass,$result[0]['UserPass'])&& ($UserName == $result[0]['UserName'])))
        {
            echo "success!";
            $_SESSION['UserName'] = $UserName;
            echo "<script> window.location ='pull_active_listings.php'; </script>";
        }
        else
        {
            //send user back to the login
            echo "<script> window.location ='../index.php?login&error'; </script>";
        } 
    }
    else
    {
        //send user back to the login page.
        echo "<script> window.location ='../index.php?login&error'; </script>";
    }
?>