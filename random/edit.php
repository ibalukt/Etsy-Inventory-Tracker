<?php
//including the database connection file
include_once("classes/Crud.php");

$crud = new Crud();

$table = $crud->escape_string($_GET['table']);

//echo print_r($cols) . "</br>";
//echo print_r($dtype);

//getting the id from the url
$id = $crud->escape_string($_GET['id']);



//selecting data associated with this particular id
$result = $crud->getData("SELECT * FROM Inventory WHERE ItemID = $id)";



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
