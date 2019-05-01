<?php
    include_once("classes/Crud.php");

    $crud = new Crud();
    
    $table = $crud->escape_string($_GET['table']);
    
    $query ="SELECT COLUMN_NAME,DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'laurens_data' AND TABLE_NAME = '$table'";
    //an array to store the column names
    $column_name = $crud->getData($query);
    //cols is the the array will contain the simplified column names
    $cols = array();
    $dtypes = array();
    foreach ($column_name as $key => $col)
    {
        //parse through the array and append the column names into the $cols array
        array_push($cols,$col['COLUMN_NAME']);
        //parse through the array and append  the datatypes into the dtypes array
        array_push($dtypes,$col['DATA_TYPE']);
    }

    $today = $crud->today();
?>
<html>
    <head>
        <title>Edit Data</title>
    </head>

    <body>
        <a href="details.php?table=<?php echo $table;?>"> Home </a>
        <br/><br/>
        <form name="form1" method="post" action="addaction.php">
            <?php
                //-----------------------------------FOREACH LOOP TO GENERATE FORM INPUTS----------------------------- 
                foreach ($dtypes as $key => $dtype)
                {
                    if ($key == 0)
                    {
                        //if the key is equal to zero then, the value will be an id (auto_increment field), therefore
                        // the user should not be able to edit this field.
                        echo "<input type='hidden' name='$cols[$key]' value='null'/>";
                        echo "<input type='hidden' name='table' value='$table'/>";
                    }
                    else
                    {           
                        //Use this switch statement to determine what kind of input should be written to the html form     
                        switch ($dtype)
                        {
                            //if the $dtype is a varchar create a text input
                            case "varchar" : echo   "
                                                        <label for='$cols[$key]'> $cols[$key] </label>
                                                        <input type='text' name='$cols[$key]' value=''/> </br>
                                                    "; break;
                            //if the $dtype is a decimal create a text input
                            case "decimal" : echo   "
                                                        <label for='$cols[$key]'> $cols[$key] </label>
                                                        <input type='text' name='$cols[$key]' value=''/> </br>
                                                    "; break;
                            //if the $dtype is an int create a number input
                            case "int" : echo   "
                                                    <label for='$cols[$key]'> $cols[$key] </label>
                                                    <input type='number' name='$cols[$key]' value=''/> </br>
                                                "; break;
                            case "date" :echo   "
                                                    <!--<label for='$cols[$key]'> $cols[$key] </label>-->
                                                    <input type='hidden' name='$cols[$key]' value='$today'/> </br>
                                                "; break;
                            //the default is a text input
                            default :   "
                                            <label for='$cols[$key]'> $cols[$key] </label>
                                            <input type='text' name='$cols[$key]' value=''/> </br>
                                        "; break;
                        }
                    }
                }
            //---------------------------------------END OF THE FOREACH LOOP----------------------------- 
            ?>
            <!--
            <h5> Who did you recieve these items from? </h5>
            <label for="FirstName" >FirstName</label><br/>
            <input type="textbox" name="FirstName" /><br/>
            <label for="LastName" >Last Name </label><br/>
            <input type="textbox" name="LastName" />
            <br/>
            <label for="Company"> What company did you recieve these items from? </label><br/>
            <input type="textbox" name="Company" >
            <br/>
            <label for="Explanation">Notes </label><br/>
            <input type="text" name="Explanation" /><br/>-->

            <input type="submit" name="update" value="Update" />
        </form>
    </body>
</html>
