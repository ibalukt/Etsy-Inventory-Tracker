<?php
include_once("classes/Crud.php");

$crud = new crud();

$query = "SELECT * FROM SalesDump ORDER BY DumpID DESC";
$results = $crud->getData($query);

$DumpIDs = array();
$DumpDates = array();
foreach($results as $result)
{
    array_push($DumpIDs,$result['DumpID']);
    array_push($DumpDates, $result['DumpDate']);
}
?>

<html>
    <head>
    </head>
    <body>
        <h4> Start you database </h4>
        <form method="post" action="Automated/restore_from_backup.php">
            <select name="DumpID">
                <?php
                    foreach($DumpIDs as $key => $DumpID)
                    {
                        echo "<option value='$DumpID'>$DumpDates[$key]</option>";
                    } 
                ?>
            </select >

            <input type="submit" name="submit" value="submit"/>
        </form>
    </body>
</html>