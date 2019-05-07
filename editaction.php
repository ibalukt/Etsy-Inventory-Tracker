<?php
include_once('classes/Crud.php');
$crud = new crud();

if ((isset($_POST['ItemID'])) && (isset($_POST['ItemName'])) && (isset($_POST['OnHandQty'])))
{
    $ItemName = $crud->escape_string($_POST['ItemName']);
    $OnHandQty = $crud->escape_string($_POST['OnHandQty']);
    $ItemID = $crud->escape_string($_POST['ItemID']);

    $query = "UPDATE Inventory Set ItemName = ?, OnHandQty = ? WHERE ItemID = ?";
    $params = array($ItemName,$OnHandQty, $ItemID);
    $crud->prep_execute($query,"sii",$params);
}
else
{
    echo "it isn't working";
}
header("Location:inventory.php");

?>