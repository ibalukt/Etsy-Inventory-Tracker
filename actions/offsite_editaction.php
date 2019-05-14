<?php 
include_once('../classes/Crud.php');
$crud = new crud();

    //Number of Items Being Removed
    $num_items = $crud->escape_string($_POST['num_items']);

    for ($i=0;$i<$num_items;$i++)
    {          
        //Set the first value of the $values array to the ID that you just inserted for the transaction
        $OffSiteID = $crud->escape_string($_POST['OffSiteID']);
        $ItemID = $crud->escape_string($_POST['ItemID'.($i)]);
        $RestockQty = $crud->escape_string($_POST['RestockQty'.($i)]);
        $RemainingQty = $crud->escape_string($_POST['RemainingQty'.($i)]);
        $InitialQty = $crud->escape_string($_POST['InitialQty'.($i)]);
        $SoldQty = $crud->escape_string($_POST['SoldQty'.($i)]);


        if (isset($_POST['Return_Items']))
        {
            
            /*$OnHandQty = $item[0]['OnHandQty'] + $RemainingQty;
            $OffSiteQty = $item[0]['OffSiteQty'] - $RemainingQty;
            echo "3";
            $query = "UPDATE Inventory SET OnHandQty = ?, OffSiteQty = ? WHERE ItemID = ?";
            $params = array($OnHandQty,$OffSiteQty,$ItemID);
            $crud->prep_execute($query,"iii",$params);
            echo "4";*/

            $query = "UPDATE OffSite SET EndDate = CURRENT_TIMESTAMP WHERE OffSiteID = $OffSiteID";
            $query = $crud->execute($query);


        }
        elseif(isset($_POST['Restock']))
        {
            
            $query = "UPDATE OffSiteItem SET RestockQty = ? WHERE OffSiteID = ? AND ItemID = ?";
            $params = array($RestockQty,$OffSiteID,$ItemID);
            $crud->prep_execute($query,"iii",$params);

            $query_p2 = "SET OnHandQty = ?, OffSiteQty = ? WHERE ItemID = ?";
            $types = "iii";

            $p= 0;

        }
        else
        {
            

            //INSERT THE IACTION INTO THE DB
            $query = "UPDATE OffSiteItem SET SoldQty = ? WHERE OffSiteID = ? AND ItemID = ?";
            $params = array($SoldQty,$OffSiteID,$ItemID);
            $crud->prep_execute($query,"iii",$params);

            //$query = "UPDATE Inventory SET OffSiteQty = ? WHERE ItemID = ?";
            //$params = array($OffSiteQty,$ItemID);
            //$crud->prep_execute($query,"ii",$params);
        }

            //Get the info in the db where the ItemID matches the current item
            $query = "SELECT * FROM OffSiteItem OSI JOIN OffSite OS ON OSI.OffSiteID = OS.OffSiteID JOIN Inventory I ON OSI.ItemID = I.ItemID WHERE OSI.ItemID = ?";
            $params = array($ItemID);
            $items = $crud->prep_getData($query,"i",$params);

    
            $OffSiteQty = 0;
            foreach($items as $item)
            {
                //If the EndDate for this item is still null, then items have yet to be returned
                if ($item['EndDate'] == null)
                {
                    $OffSiteQty = $OffSiteQty + ($item['RemainingQty']);
                }
                //Otherwise, they are already back.
                else
                {
                    echo "this item order is already returned. <br/>";
                }
            }

            echo "The amount of this item off-site is : " . $OffSiteQty;

            
            $TotalQty = $items[0]['TotalQty'];
            $OnHandQty = $TotalQty - $OffSiteQty;
            if (isset($_POST['Return_Items'])) 
            {
                $query = "UPDATE Inventory SET OnHandQty = ?, OffSiteQty = ? WHERE ItemID = ?";
                $params=array($OnHandQty,$OffSiteQty,$ItemID);
                $types = "iii";

            }
            elseif (isset($_POST['Restock']))
            {
                $query = "UPDATE Inventory SET OnHandQty = ?, OffSiteQty = ? WHERE ItemID = ?";
                $params=array($OnHandQty,$OffSiteQty,$ItemID);
                $types = "iii";
            }
            else
            {
                $query = "UPDATE Inventory SET OffSiteQty = ? WHERE ItemID = ?";
                $params=array($OnHandQty,$OffSiteQty,$ItemID);
                $types = "ii";
                $params=array($OffSiteQty,$ItemID);
            }
            echo $query . "<br/>";
            echo print_r($params);
            $crud->prep_execute($query,$types,$params);
    }
        
    //__Inventory####################################################################################################

    header("Location:../index.php?offsite_items&inventory_id=$OffSiteID");
    
?>