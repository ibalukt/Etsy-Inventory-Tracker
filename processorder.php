<?php
    //including the database connection file
    include_once("classes/Crud.php");
    include_once("classes/Validation.php");
    
    $crud = new Crud();
    $validation = new Validation();
    
    if (isset($_POST['update']))
    {
        //$id = $crud->escape_string($_POST['']);
        //echo "$id";

        $table = "Taction";
    
        $query ="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'laurens_data' AND TABLE_NAME = '$table'";
        //an array to store the column names
        $column_name = $crud->getData($query);
        //cols is the array that will contain the simplified column names EXAMPLE: ItemID, ItemName, UnitPrice, etc.
        $cols = array();
        //vals is the array that will contain the values for each of the column names. EXAMPLE: 1, Journal, 20.00, etc.
        $vals = array();
    
    
        foreach ($column_name as $key => $col)
        {
            //parse through the array and append the column names into the $cols array
            array_push($cols,$col['COLUMN_NAME']);
            //for each column add the corresponding value to the vals array
            //If the column name is in the post and is set then
            if (isset($_POST[$cols[$key]]))
            {
                //Append the value to the vals array
                array_push($vals,$crud->escape_string($_POST[$cols[$key]]));
            }
            else
            {
                //If the column name is the post is not set then append "N/A" to the array
                array_push($vals,"N/A");
            }
            //DEBUG: echo $cols[$key] . ":" . $vals[$key] . "<br>" ;
    
        }
    
        //Start building the INSERT query
        $query = "INSERT INTO $table";
        // the id will store the value of the ItemID or ORDERID
        $id = null;
        //Middle part of query
        $middle = null;
        //Ending of the query
        $end = null;
        foreach($cols as $key => $col)
        {
            //This stores the id, because you are not updating the value of the ID
            if ($key == 0)
            {
                //$id = $vals[$key];
                $middle = "(";
                $end = "VALUES (";
            }
            //This elseif gets rid of the comma at the end of the statement and adds the Corresponding WHERE statement to the end
            elseif($key == (sizeof($cols)-1))
            {
                $middle = $middle . "$cols[$key])";
                $end = $end . "'$vals[$key]')";
            }
            //This is the normal statemetn that adds the new value to the column name. Example ItemName='Journal'
            else
            {
                $middle = $middle . "$cols[$key],";
                $end = $end . "'$vals[$key]',";
            }
        }

        //This is the query to put correct values into TAction
        $TAction_Query = $query . $middle . $end;
        //echo $TAction_Query;

        $result = $crud->execute($TAction_Query);

        //TACTION ITEM------------------------------------------------------------------------------------------------

        //--1.---------------------------GET COLUMN NAMES TActionItem & Inventory---------------------------------

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
                //If one of the Transacion Item Columns is set in the Post from the form (picks up Qty)
                //if (isset($_POST[$TIcol]))
                //{
                    //clear it of any non allowed symbols / characters
                    //$v= $crud->escape_string($_POST[$TIcol]);
                    //push the column names into the TActionITem Column Name into the common_fields array
                    //array_push($common_fields,$TIcol);
                    //push the value retrieved from the post request into the values field
                    //array_push($values,$v);
                    //The break statement is present so that common field values are not duplicated.
                    //break;
                //}
                //If the TActionItem ColumnName is equal to the Inventory Column Name 
                if ($TIcol == $Icol)
                {
                    //Push the common Column Name into the common fields array()
                    array_push($common_fields,$Icol);
                    //Push the value of the Items[Common Field] into the $values array.
                    //array_push($values,$items[0][$Icol]);
                    //Break statement is present so that there will not be any duplicate values
                    break;
                }
            }
        }
        //echo print_r($common_fields) ;

        //--4.----------------------------------GET THE ITEMS/QTY FOR THE TRANSACTION--------------------------------

        //get the Item ID of the item selected in the form 
        $ItemID=$crud->escape_string($_POST['ItemID']);
        //get the number of a particular item in the transaction
        $Qty=$crud->escape_string($_POST['Qty']);
        
        $query = "SELECT * FROM inventory WHERE ItemID = '$ItemID'";

        //Get the data that the query returns
        $items = $crud->getData($query);

        //--5.--------------------------------LOOP THROUGH THE COMMON FIELDS AND GET ITEM VALUES------------------- 

        $values = array();
        foreach ($items as $key => $item)
        {
            foreach ($common_fields as $field)
            {
                //Add the item value to the field
                array_push($values, $item[$field]);
            }
        }
        //--.6----------------------------------ADD OTHER FIELDS and ADD THIER VALUES------------------------
        //Add TActionID to the Beginning of the common Fields array
        array_unshift($common_fields,'TActionID');
        //Add the Last TActionID to the beginning of the values array
        array_unshift($values, $TActionID);

        //Add the Qty field to the common fields array
        array_push($common_fields,'Qty');
        //Add the Qty value to the values array
        array_push($values, $Qty);

        //Total charge is equal to values[2](UnitPrice) X values[3](Qty);
        $TotalCharge = $values[2]*$values[3];

        //Add Total Charge to the common fields
        array_push($common_fields,'TotalCharge');
        array_push($values, $TotalCharge);

        echo print_r($common_fields);
        echo print_r($values);

        $TActionItem_Q = "INSERT INTO TACTIONITEM";
        foreach($common_fields as $key => $field)
        {
            if ($key == 0)
            {
                $middle = "(".$field.",";
                $end = "VALUES ('$values[$key]',";
            }
            elseif($key == (sizeof($common_fields)-1))
            {
                $middle = $middle . $field . ")";
                $end = $end ."'$values[$key]')";
            }
            else
            {
                $middle = $middle . $field . ",";
                $end = $end . "'$values[$key]',";
            }
        }
        $TActionItem_Q .= ($middle . $end); 

        echo $TActionItem_Q;

        $result=$crud->execute($TActionItem_Q);
        header("Location: details.php?table=tactionitem");
        
        
    }
    else
    {
        echo "It isn't working";
    }
?>
