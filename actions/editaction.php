<?php
include_once('../classes/Crud.php');
include_once('../classes/Validation.php');

$val = new validation();
$crud = new crud();

if ((isset($_POST['ItemID'])) && (isset($_POST['ItemName'])) && (isset($_POST['OnHandQty'])))
{
    $ItemName = $crud->escape_string($_POST['ItemName']);
    $OnHandQty = $crud->escape_string($_POST['OnHandQty']);
    $ItemID = $crud->escape_string($_POST['ItemID']);

    if ((!empty($ItemName)) && (!empty($ItemID)))
    {

        $OnHandQty = ABS($OnHandQty);

        $query = "UPDATE Inventory Set ItemName = ?, OnHandQty = ? WHERE ItemID = ?";
        $params = array($ItemName,$OnHandQty, $ItemID);
        $crud->prep_execute($query,"sii",$params);

        header("Location:../index.php?");
    }
    else
    {
        echo "it didn't work";
        echo "<script>location.href = document.referrer + '&error';</script>";
    }
}
else
{
    echo "it didn't work because something wasn't set";
    echo "<script>location.href = document.referrer + '&error';</script>";
}


?>