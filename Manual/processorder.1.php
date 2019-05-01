<?php
    //including the database connection file
    include_once("../classes/Crud.php");
    include_once("../classes/Validation.php");
    
    $crud = new Crud();
    $validation = new Validation();
    
    if (isset($_POST['update']))
    {

        //TAction Table--------------------------------------------------------------
        $TActionDate = $crud->escape_string($_POST['TActionDate']);
        $PurchasedBy = $crud->escape_string($_POST['PurchasedBy']);

        //echo $TActionDate;
        //echo $PurchasedBy;
        $query = "INSERT INTO TAction (TActionDate,PurchasedBy) VALUES ('$TActionDate','$PurchasedBy')";
        $crud->execute($query);

        //Get the Last Id that was inserted into the TAction Table
        $query = "SELECT LAST_INSERT_ID() FROM TAction";
        $get_id = $crud->getData($query);
        $TActionID = $get_id[0]['LAST_INSERT_ID()'];

        //Get the number of items being taken out.
        $num_items = $crud->escape_string($_POST['num_items']);

        //TACTION ITEMS.
        $columns = array('TActionID','ItemID','Qty','UnitPrice');

        //Total Price
        $Total_Charge = 0;
        for ($i=0;$i<$num_items;$i++)
        {          
            $values = null;

            //Set the first value of the $values array to the ID that you just inserted for the transaction
            $ItemID = $crud->escape_string($_POST['ItemID'.($i+1)]);
            $Qty = $crud->escape_string($_POST['Qty'.($i+1)]);
            
            //query the database for the item info that we are adding to TActionID
            $Item_Q = "SELECT * FROM Inventory WHERE ItemID ='$ItemID'";
            $item = $crud->getData($Item_Q);

            //Add to the total
            $Total_Charge = $Total_Charge +  ($Qty * $item[0]['UnitPrice']);

            $values = array($TActionID,$item[0]['ItemID'],$Qty,$item[0]['UnitPrice']);
            
            //Use one of the crud functions to build the query. Parameters: Table Name, The columns being inserted, the values
            $query = $crud->buildQuery("TActionItem","INSERT INTO",$columns,$values); 
            $result=$crud->execute($query);
     
            $Qty = $Qty + $item[0]['QtyAvailable'];
                
            $query = "UPDATE Inventory SET QtyAvailable = '$Qty' Where ItemID = '$ItemID'";

            //echo $query;

            $result=$crud->execute($query);      

        }
        $Sales_Tax = $Total_Charge * .08375;
        $Sales_Tax = abs(round($Sales_Tax,2));
        $Total_Charge = abs($Total_Charge);

        echo "Total Charge: ". abs($Total_Charge);
        echo "Sales Tax: " . abs($Sales_Tax);

        $query = "UPDATE TAction SET SalesTax='$Sales_Tax' ,ShippingCharge='0.00' ,TotalCharge ='$Total_Charge' WHERE TActionID=$TActionID";
        $crud->execute($query);
        
        //__Inventory####################################################################################################

        //header("Location: details.php?table=tactionitem");
    }
    else
    {
        echo "It isn't working";
    }
?>
