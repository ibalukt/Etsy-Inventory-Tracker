<?php
    //including the database connection file
    include_once("classes/Crud.php");
    include_once("classes/Validation.php");
    
    $crud = new Crud();
    $validation = new Validation();
    
    if (isset($_POST['update']))
    {

        //__TACTION TABLE__#########################################################################################
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
                array_push($values,"N/A");
            }
            //DEBUG: echo $cols[$key] . ":" . $vals[$key] . "<br>" ;
    
        }

        //Build the query that we are going to use to insert the new transaction into the TAction table
        $query = $crud->buildQuery("TAction",$columns,$values);
        //Execute the query that we just built.
        //echo $query;
        $result = $crud->execute($query);


        //__TACTIONITEM_TABLE__####################################################################################

        //--1.---------------------------GET COLUMN NAMES TActionItem & Inventory---------------------------------

        //NOTE getCols returns the column names of the table that is given
        $TActionItem_Cols = $crud->getCols("TActionItem");
        $Inventory_Cols = $crud->getCols("Inventory");

        //--2.---------------------GET TActionID OF THE LAST ITEM INSERTED INTO THE TACTION TABLE------------------

        //This is the query to get the TActionID of the item you just inserted into the DB.
        $query = "SELECT LAST_INSERT_ID() FROM TAction";
        //This variable holds sthe results of the the query above.
        $get_id = $crud->getData($query);
        //Store the TActionID of the item put last into the TAction table.
        $TActionID = $get_id[0]['LAST_INSERT_ID()'];

        //--3.----------------------------GET The Common Column Names--------------------------------------------
        //This array will contain all of the common column names between TActionItem and Inventory
        $common_fields = array();

        //For each TActionItem Column Name
        foreach ($TActionItem_Cols as $key => $TIcol)
        {   
            //For each Inventory Column Name
            foreach ($Inventory_Cols as $key => $Icol)
            {
                //If the TActionItem ColumnName is equal to the Inventory Column Name 
                if ($TIcol == $Icol)
                {
                    //Push the common Column Name into the common fields array()
                    array_push($common_fields,$Icol);
                }
            }
        }

        //--4.---------------------GET Number of Items that User Enter for Transaciton ------------------------ 
        //Read the number of items that need to be inserted into the TAction Table
        $num_items = $crud->escape_string($_POST['num_items']);

        //--5.---------------------Redefine the $columns array for future use -----------------------------------

        //redfine the columns array for the next part of the operations.
        $columns = null;
        $columns = array();
        $columns = $common_fields;
        //DEBUG: echo print_r($columns);
        //Set the first index of the common_fields array to the TActionID
        array_unshift($columns,"TActionID");
        //Add a Quantity field to the end of the common_fields array
        array_push($columns,"Qty");

        //--6.--------------------Insert all of the items from the Transacition----------------------------------
        //For every time that $i is less than the number of items being processed in the last page
        for ($i=0;$i<$num_items;$i++)
        {
            //set the initial value of the $values array to null.
            $values = null;
            //turn the $values variable into an array;
            $values = array();
            //Set the first value of the $values array to the ID that you just inserted for the transaction
            array_unshift($values,$TActionID);
            //get the ItemID for the first item by adding ItemID to the $i+1
            $ItemID = $crud->escape_string($_POST['ItemID'.($i+1)]);
            //do the same for the Qty field
            $Qty = $crud->escape_string($_POST['Qty'.($i+1)]);
            
            //query the database for the item info that we are adding to TActionID
            $Item_Q = "SELECT * FROM Inventory WHERE ItemID ='$ItemID'";
            
            //get the item
            $item = $crud->getData($Item_Q);
            //sort through each of the fields returned for the item
            foreach ($columns as $key => $column)
            {
                //if the array key exists in the $common_fields array, then add the value of that key from item 
                //into the values array. This if statement catches when Qty is not a key in the Inventory table.
                if (array_key_exists($column,$item[0]))
                {
                    array_push($values,$item[0][$column]);
                }
            }
            //Add the $Qty value to the end of the value array
            array_push($values,$Qty);

            //DEBUG: echo print_r($common_fields);
            //DEBUG: echo print_r($values);
            
            //Use one of the crud functions to build the query. Parameters: Table Name, The columns being inserted, the values
            $query = $crud->buildQuery("TActionItem",$columns,$values);
            //DEBUG echo $query . "<br>";
            
            $result=$crud->execute($query);

            //--UPDATE Item's QtyAvailable field in Inventory
            $Qty = ($item[0]['QtyAvailable'] - $Qty);
            
            $query = "UPDATE Inventory SET QtyAvailable = '$Qty' Where ItemID = '$ItemID'";

            $result=$crud->execute($query);          

        }
        //__Inventory####################################################################################################

        header("Location: details.php?table=tactionitem");
    }
    else
    {
        echo "It isn't working";
    }
?>
