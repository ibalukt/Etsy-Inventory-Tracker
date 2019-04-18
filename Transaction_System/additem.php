<?php
    //including the database connection file
    include_once("classes/Crud.php");
    include_once("classes/Validation.php");
    
    $crud = new Crud();
    $validation = new Validation();
    
    if (isset($_POST['update']))
    {

        ##TACTION TABLE#################################################################################################
        //$id = $crud->escape_string($_POST['']);
        //echo "$id";

        //$table = "Taction";

        //get the array of column names from the TACTION TABLE
        $column_name = $crud->getCols("TAction");
        //cols is the array that will contain the simplified column names EXAMPLE: ItemID, ItemName, UnitPrice, etc.
        $columns = array();
        //vals is the array that will contain the values for each of the column names. EXAMPLE: 1, Journal, 20.00, etc.
        $values = array();
    
    
        foreach ($column_name as $key => $col)
        {
            //parse through the array and append the column names into the $cols array
            array_push($columns,$col);
            //for each column add the corresponding value to the vals array
            //If the column name is in the post and is set then
            if (isset($_POST[$columns[$key]]))
            {
                //Append the value to the vals array
                array_push($values,$crud->escape_string($_POST[$columns[$key]]));
            }
            else
            {
                //If the column name in the post is not set then append "N/A" to the array
                array_push($values,'N/A');
            }
            //DEBUG: echo $cols[$key] . ":" . $vals[$key] . "<br>" ;
    
        }

        //Build the query that we are going to use to insert the new transaction into the TAction table
        $query = $crud->buildQuery("TAction",$columns,$values);
        //Execute the query that we just built.
        echo $query;
        $result = $crud->execute($query); //TO DO: THIS QUERY WILL FAIL IF THE TYPE ID IS AN IT. YOU CAN't INSERT "N/A"

        $query = "SELECT LAST_INSERT_ID() FROM TAction";
        //This variable holds sthe results of the the query above.
        $get_id = $crud->getData($query);
        //Store the TActionID of the item put last into the TAction table.
        $TActionID = $get_id[0]['LAST_INSERT_ID()'];

        ##INVENTORY_TABLE############################################################################################
        //Get the Item ID for the Item you are Updating
        $ItemID = $crud->escape_string($_POST['ItemID']);
        //SELECT * FROM INVENTORY WHERE ItemID = 'the item id from the post'
        $query = "SELECT * FROM Inventory WHERE ItemID = '$ItemID' ";
        //Get the data and store it in the item variable
        $item = $crud->getData($query);
        //get the QtyAvailable so that you can add the Quantity from the last page to the QtyAvailable
        $QtyAvailable = $item[0]['QtyAvailable'];
        //get the Qty entered on the last page
        $Qty = $crud->escape_string($_POST['Qty']);
        //Create a newly updated Qty to be inserted and replace the current QtyAvailable field.
        $NewQtyAvailable = $Qty + $QtyAvailable;
        //Get the TotalCharge so that we can divide the total charge by the qty to determine the unit cost
        $Manufacture = $crud->escape_string($_POST['Manufacture']);
        $Shipping = $crud->escape_string($POST['ShippingFee']);
        $TotalCharge = $Manufacture + $Shipping;        
        //Do the operations to determine the $UnitCost. Round the answer to the apropriate decimal place.
        $UnitCost = round(($TotalCharge / $Qty),2);
        //DEBUG: echo $UnitCost . "$";
        $CostModDate = $crud->escape_string($_POST['TActionDate']);
        //Create the Update Query;
        $query = "UPDATE Inventory SET UnitCost = '$UnitCost', QtyAvailable ='$NewQtyAvailable',
                  CostModDate='$CostModDate' WHERE ItemID = '$ItemID'";
        echo print_r($query);
        $result = $crud->execute($query); 

        ##TACTIONITEM TABLE#####################################################################################################

        $query = "INSERT INTO TActionItem (TActionID,ItemID,Qty,UnitPrice) 
                  VALUES ('$TActionID','$ItemID','$Qty','$UnitCost')";

        $result = $crud->execute($query);


        
        header("Location: details.php?table=inventory");

    }
?>