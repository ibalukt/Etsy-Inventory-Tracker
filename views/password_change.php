<div class='row'>
    <form class="col-sm-4 ml-auto mr-auto border p-5" style=" margin-top:130px;" method="post" action="index.php?change_pass">
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


<?php 

    if(isset($_POST['Change_Password'])){

        //make sure that there is at least a name, email, and password for account creation.

        if ((($_POST['NewPass']) == ($_POST['ConfirmPass'])) && (preg_match("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@$!%*#?&]{8,}$^",$_POST['NewPass'])))
        { 

            $NewPass = $_POST['NewPass'];

            $Hashed = password_hash($NewPass,PASSWORD_BCRYPT);

            $query = "UPDATE Users SET UserPass = ? WHERE UserID = ?";
            $params = array($Hashed,1);
            $crud->prep_execute($query,"si",$params);

            echo "<script>bootbox.alert('The new password has been stored in the database', function(){
                window.location ='index.php';
                })</script>"; 




        }
        else
        {
            echo "<script>bootbox.alert('password must be 8 to twelve characters and contain at least 1 number.'); </script>"; 
        }
        
    }
?>