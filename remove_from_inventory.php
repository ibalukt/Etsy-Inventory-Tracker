<?php 
include_once('session_check.php');
include_once('classes/DbConfig.php');
include_once('classes/Crud.php');
$crud = new crud();

    if (isset($_POST['is_submitted']))
    {
        //Number of Items Being Removed
        $num_items = $crud->escape_string($_POST['num_items']);

        //This calculates the total number of items being worked with
        $ItemCount = 0;
        for ($i=0;$i<$num_items;$i++)
        {
             $ItemCount += $crud->escape_string($_POST['Qty'.($i+1)]);
        }

        //$stmt =  $db->prepare("INSERT INTO Offsite (ReasonID,GoingWhere) VALUES (?,?)");
        //$stmt->bind_param("ss",$ReasonID,$GoingWhere);
        //Items being taken out
        $ReasonID = $crud->escape_string($_POST['ReasonID']);
        $GoingWhere = $crud->escape_string($_POST['GoingWhere']);

        echo $ReasonID;
        echo $GoingWhere;
        //$GoingWhere = strtolower($GoingWhere);

        echo"1<br/>";
        $query ="INSERT INTO OffSite (ReasonID,GoingWhere) VALUES(?,?)";
        $params = array($ReasonID,$GoingWhere);

        $crud->prep_execute($query,"is",$params);
        echo "2<br/>";
        //Get the Last Id that was inserted into the TAction Table
        $OffSiteID = $crud->last_insert_id();
    

        //Total Price
        for ($i=0;$i<$num_items;$i++)
        {          
            
            $ItemID = $crud->escape_string($_POST['ItemID'.($i+1)]);
            $Qty = $crud->escape_string($_POST['Qty'.($i+1)]);

            //INSERT THE OFFSITE ITEMS INTO THE DB
            $query="INSERT INTO OffSiteItem (OffSiteID,ItemID,InitialQty) VALUES (?,?,?)";
            $params = array($OffSiteID,$ItemID,$Qty);

            $crud->prep_execute($query,"iii",$params);

            //GET THE ITEM 
            $query = "SELECT * FROM Inventory WHERE ItemID = $ItemID";
            $item = $crud->getData($query);
              
            //print_r($item);
            //Adjust the quantity to be inserted into the physquantity of the db
            $OnHandQty = $Qty + $item[0]['OnHandQty'];
            $OffSiteQty = abs($Qty) + $item[0]['OffSiteQty'];
            
            //INSERT THE OFFSITE ITEMS INTO THE DB

            $query = "UPDATE Inventory SET OnHandQty = ?, OffSiteQty = ? WHERE ItemID = ?";
            $params = array($OnHandQty,$OffSiteQty,$ItemID);

            $crud->prep_execute($query,"iii",$params); 
    
        }
            
        //__Inventory####################################################################################################

        header("Location: inventory.php");
    }
    else
    {
        //echo var_dump($_POST);
        echo "It isn't working";
    }
?>