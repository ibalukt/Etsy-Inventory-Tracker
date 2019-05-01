<?php
    //start the session
    session_start();
    include_once("classes/DbConfig.php");

    //create a connection variable specifically for this php
    $db = new DbConfig();
    $db = $db->__construct();

    //If both the UserName and UserPass are created
    if ((isset($_POST['UserName'])) && (isset($_POST['UserPass'])))
    {        
        //prepare the statement
        $stmt = $db->prepare("SELECT * FROM Users WHERE UserName= ? AND UserPass= ?");
        //bind the parameters
        $stmt->bind_param("ss",$UserName,$UserPass);

        //assign values to the parameters
        $UserName = mysqli_escape_string($db,$_POST['UserName']);
        $UserPass = mysqli_escape_string($db,$_POST['UserPass']);

        //execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        //if the number of rows retuned is zero, that means that there was no results that matched user inputs
        if ($result->num_rows == 1)
        {
            //create a session variable for validation on other pages
            $_SESSION['UserName'] = $UserName;
            //send user to the new page
            header('Location:index.php');
        } 
        else
        {
            //send user back to the login
            echo "<script> window.location ='login.php?error=1'; </script>";
        } 
    } 
    else
    {
        //send user back to the login page.
        echo "<script> window.location ='login.php?error=0'; </script>";
    }
?>