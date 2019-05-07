<?php 
include_once('classes/DbConfig.php');
include_once('classes/Crud.php');
$crud = new crud();

    //Number of Items Being Removed
    $num_items = $crud->escape_string($_POST['num_items']);

    for ($i=0;$i<$num_items;$i++)
    {          
        //Set the first value of the $values array to the ID that you just inserted for the transaction
        $OffSiteID = $crud->escape_string($_POST['OffSiteID']);
        $ItemID = $crud->escape_string($_POST['ItemID'.($i)]);

        if (isset($_POST['Return_Items']))
        {
            echo "1";
            $query = "SELECT * FROM Inventory WHERE ItemID = $ItemID";
            $item = $crud->getData($query);

            echo "2";
            $RemainingQty = $crud->escape_string($_POST['RemainingQty'.($i)]);
            $InitialQty = $crud->escape_string($_POST['InitialQty'.($i)]);
            
            $OnHandQty = $item[0]['OnHandQty'] + $RemainingQty;
            $OffSiteQty = $item[0]['OffSiteQty'] - $RemainingQty;
            echo "3";
            $query = "UPDATE Inventory SET OnHandQty = ?, OffSiteQty = ? WHERE ItemID = ?";
            $params = array($OnHandQty,$OffSiteQty,$ItemID);
            $crud->prep_execute($query,"iii",$params);
            echo "4";

            $query = "UPDATE OffSite SET EndDate = CURRENT_TIMESTAMP";
            $query = $crud->execute($query);

        }
        elseif(isset($_POST['Restock']))
        {
            echo "1";
            $query = "SELECT * FROM Inventory WHERE ItemID = $ItemID";
            $item = $crud->getData($query);

            echo "2";
            $RestockQty = $crud->escape_string($_POST['RestockQty'.($i)]);
            
            $OnHandQty = $item[0]['OnHandQty'] - $RestockQty;
            $OffSiteQty = $item[0]['OffSiteQty'] + $RestockQty;
            echo "3";

            $query = "UPDATE OffSiteItem SET RestockQty = ? WHERE OffSiteID =? AND ItemID = ?";
            $params = array($RestockQty,$OffSiteID,$ItemID);
            $crud->prep_execute($query,"iii",$params);

            $query = "UPDATE Inventory SET OnHandQty = ?, OffSiteQty = ? WHERE ItemID = ?";
            $params = array($OnHandQty,$OffSiteQty,$ItemID);
            $crud->prep_execute($query,"iii",$params);
            echo "4";
        }
        else
        {

            //Qty of the items being changed
            $Qty = $crud->escape_string($_POST['SoldQty'.($i)]);

            //INSERT THE IACTION INTO THE DB
            $query = "UPDATE OffSiteItem SET SoldQty = ? WHERE OffSiteID = ? AND ItemID = ?";
            $params = array($Qty,$OffSiteID,$ItemID);
            $crud->prep_execute($query,"iii",$params);

            $query = "SELECT * FROM Inventory WHERE ItemID = $ItemID";
            $item = $crud->getData($query);

            $new_OffSite = $item[0]['OffSiteQty']-$Qty;

            $query = "UPDATE Inventory SET OffSiteQty = ? WHERE ItemID = ?";
            $params = array($new_OffSite,$ItemID);
            $crud->prep_execute($query,"ii",$params);
        }
    }
        
    //__Inventory####################################################################################################

    header("Location:offsite_items.php?OffSiteID=$OffSiteID");
    
?>