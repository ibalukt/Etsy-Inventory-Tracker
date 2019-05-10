<?php
include_once('classes/Crud.php');

$crud = new crud();

if (isset($_GET['ID']))
{

    $query = "SELECT * FROM OffSite WHERE OffSiteID = ?";
    $params = array($crud->escape_string($_GET['ID']));
    $result = $crud->prep_execute($query,"i",$params);

    print_r($result);

}

?>