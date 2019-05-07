<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="description" content="Free Web tutorials">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- Bootstrap Javascript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <!--Bootbox-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
</head>
<body>
    <div class="container">
        <form class="col-sm-4 ml-auto mr-auto border p-5" style="margin-top:150px;" method="post" action="password_change.php">
            <div class="row">
                <div class="col-sm-12 ml-auto mr-auto text-center">
                    <h2> Change Password</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group ml-auto mr-auto">
                    <!--    Password  -->
                    <label for="UserPass">Type a new password</label>
                    <input class="form-control" type="text" name="NewPass" required/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group ml-auto mr-auto">
                    <!--    Password  -->
                    <label for="UserPass">Confirm new password</label>
                    <input class="form-control" type="text" name="ConfirmPass" required/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 form-group ml-auto mr-auto">
                    <input type="submit" name="Change_Password" class="btn btn-primary" style="width:100%;" value="Change_Password"  />
                </div>
            </div>
        </form>
    </div>
</body>
</html>

<?php 
include_once("classes/Crud.php");
$crud = new crud();

    if(isset($_POST['Change_Password'])){

        //make sure that there is at least a name, email, and password for account creation.

        if ((($_POST['NewPass']) == ($_POST['ConfirmPass'])) && (preg_match("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$^",$_POST['NewPass'])))
        { 

            $NewPass = $_POST['NewPass'];

            $Hashed = password_hash($NewPass,PASSWORD_BCRYPT);

            $query = "UPDATE Users SET UserPass = ? WHERE UserID = ?";
            $params = array($Hashed,1);
            $crud->prep_execute($query,"si",$params);

        }
        else
        {
            echo "<script>bootbox.alert(' To create an account you must provide at least a name, email, and password. (password must be 8 to twelve characters and contain at least 1 number.) '); </script>"; 
        }
        
    }
?>