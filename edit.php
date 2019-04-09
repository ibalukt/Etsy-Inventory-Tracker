<?php
//including the database connection file
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
//echo print_r($cols) . "</br>";
//echo print_r($dtype);

//getting the id from the url
$id = $crud->escape_string($_GET['id']);



//selecting data associated with this particular id
$result = $crud->getData("SELECT * FROM $table WHERE $cols[0] = $id");



$vals = array();

//This loop assigns all of the appropriate values to the $vals array. 
foreach ($result as $res) {
    //For each result
    foreach ($cols as $value)
    {
        //Place a new value for one of the corresponding table columns (Example: for journal, whats is the ItemID,ItemName,etc.)
        array_push($vals, $res[$value]);
    }
}
//DEBUG echo print_r($dtypes);
?>

<html>
    <head>
        <title>Edit Data</title>
    </head>

    <body>
        <a href="details.php?table=<?php echo $table;?>"> Home </a>
        <br/><br/>

        <form name="form1" method="post" action="editaction.php">
            <?php
                //-----------------------------------FOREACH LOOP TO GENERATE FORM INPUTS----------------------------- 
                foreach ($dtypes as $key => $dtype)
                {
                    if ($key == 0)
                    {
                        //if the key is equal to zero then, the value will be an id (auto_increment field), therefore
                        // the user should not be able to edit this field.
                        echo "<input type='hidden' name='$cols[$key]' value='$vals[$key]'/>";
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
                                                        <input type='text' name='$cols[$key]' value='$vals[$key]'/> </br>
                                                    "; break;
                            //if the $dtype is a decimal create a text input
                            case "decimal" : echo   "
                                                        <label for='$cols[$key]'> $cols[$key] </label>
                                                        <input type='text' name='$cols[$key]' value='$vals[$key]'/> </br>
                                                    "; break;
                            //if the $dtype is an int create a number input
                            case "int" : echo   "
                                                    <label for='$cols[$key]'> $cols[$key] </label>
                                                    <input type='number' name='$cols[$key]' value='". intval($vals[$key]). "'/> </br>
                                                "; break;
                            case "date" :echo   "
                                                    <label for='$cols[$key]'> $cols[$key] </label>
                                                    <input type='date' name='$cols[$key]' value='". $vals[$key]. "'/> </br>
                                                "; break;
                            //the default is a text input
                            default :   "
                                            <label for='$cols[$key]'> $cols[$key] </label>
                                            <input type='text' name='$cols[$key]' value='$vals[$key]'/> </br>
                                        "; break;
                        }
                    }
                }
            //---------------------------------------END OF THE FOREACH LOOP----------------------------- 
            ?>
            <input type="submit" name="update" value="Update" />
        </form>
    </body>
</html>






<!--<tr>
                    <td> Item Name</td>
                    <td><input type="text" name="ItemName" value="<?php echo $ItemName; ?>"/></td>
                </tr>
                <tr>
                    <td> Unit Price </td>
                    <td><input type="text" name="UnitPrice" value="<?php echo $UnitPrice; ?>"/></td>
                </tr>
                <tr>
                    <td> Unit Cost </td>
                    <td><input type="text" name="UnitCost" value="<?php echo $UnitCost; ?>"/></td>
                </tr>
                <tr>
                    <td> Packaging Cost </td>
                    <td><input type="text" name="PackagingCost" value="<?php echo $PackagingCost; ?>"/></td>
                </tr>
                <tr>
                    <td> Quantity Available </td>
                    <td><input type="text" name="QtyAvailable" value="<?php echo $QtyAvailable; ?>"/></td>
                </tr>
                    <td><input type="hidden" name="id" value="<?php echo $id;?>"/></td>
                    <td><input type="submit" name="update" value="Update" /> </td>
                </tr>-->
