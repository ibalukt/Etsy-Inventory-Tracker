<?php
//including the database connection file
include_once("classes/Crud.php");

$crud = new Crud();

//getting id of the data url
$id = $crud->escape_string($_GET['id']);
$table = $crud->escape_string($_GET['table']);

//This is the query to get all of the column names from the db information schema.
$query ="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'laurens_data' AND TABLE_NAME = '$table'";
//an array to store the column names
$column_name = $crud->getData($query);
//cols is the the array will contain the simplified column names
$cols = array();
foreach ($column_name as $key => $col)
{
    //parse through the arrays and append the values into the $cols array
    array_push($cols,$col['COLUMN_NAME']);
}

//Delete the specified item from the database.
$result = $crud->delete($id,$cols[0],$table);

//If the $result variable has a value than redirect to the details page.
if ($result) {
    //redirecting to the display page (index.php in our case)
    header("Location:details.php?table=$table");
}
?>