<div class='row '>
<form class="col-sm-4 ml-auto mr-auto p-5 border" style="margin-top:130px;" method="post" action="actions/authenticate.php">
    <div class="row">
        <div class="col-sm-12 ml-auto mr-auto">
            <?php 
                /*if (isset($_GET['error']))
                {
                    $num =($_GET['error']);
                    $errors = array("Please Login To Access This Area.", "Invalid UserName or Password. Try Again.");
                    echo "<p class='text-danger'>".$errors[$num] . "</p>";
                }*/
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 ml-auto mr-auto text-center">
            <h2> Login </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-group ml-auto mr-auto mt-2">
            <!--    UserName    -->
            <label for="UserName">User Name </label>
            <input class="form-control" type="text" name="UserName" required/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-group ml-auto mr-auto">
            <!--    Password  -->
            <label for="UserPass">Password</label>
            <input class="form-control" type="text" name="UserPass" required/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 form-group ml-auto mr-auto">
            <input type="submit" class="btn btn-primary" style="width:100%;" value="Login"  />
        </div>
    </div>
    <div class="row">
        <div class='col-sm-12 ml-auto mr-auto text-center'>
            <a  href="forgot_password.php">I Forgot Password? </a>               
        </div>
    </div>
</form>
<script>
            if (window.location.href.indexOf("error") > -1)
            {
                message = "Please Login To Access This Area.", "Invalid UserName or Password. Try Again.";   
                //bootbox.alert("Please Login To Access This Area.", "Invalid UserName or Password. Try Again.");
            }
</script>
</div>