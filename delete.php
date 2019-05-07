<?php
//including the database connection file
include_once("classes/Crud.php");

$crud = new Crud();

//getting id of the data url
$id = $crud->escape_string($_GET['ItemID']);

//This is the query to get all of the column names from the db information schema.
$query = "UPDATE Inventory SET State ='inactive' WHERE ItemID = $id";

//an array to store the column names
$result = $crud->execute($query);

//If the $result variable has a value than redirect to the details page.
if ($result) {
    //redirecting to the display page (index.php in our case)
    header("Location:Inventory.php");
}
?>