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
    
        $table = $crud->escape_string($_POST['table']);
    
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
            array_push($vals,$crud->escape_string($_POST[$cols[$key]]));
            //DEBUG: echo $cols[$key] . ":" . $vals[$key] . "<br>" ;
    
        }
    
        //Start building the update query
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

        $query = $query . $middle . $end;
        echo $query;

        $result = $crud->execute($query);
        header("Location: details.php?table=$table");
    }






























































    //Including the database connection file
    /*include_once("classes/Crud.php");
    include_once("classes/Validation.php");

    //new instance of the crud and validation classes
    $crud = new Crud();
    $validation = new Validation();

    if (isset($_POST['Submit'])) 
    {
        $ItemName = $crud->escape_string($_POST['ItemName']);
        $UnitPrice = $crud->escape_string($_POST['UnitPrice']);
        $UnitCost = $crud->escape_string($_POST['UnitCost']);
        $PackagingCost = $crud->escape_string($_POST['PackagingCost']);
        $QtyAvailable = $crud->escape_string($_POST['QtyAvailable']);

        /*$msg = null;//$validation->check_empty($_POST, array('name','age','email'));
        $check_age = null; //$validation->is_age_valid($_POST['age']);
        $check_email = null;  //$validation->is_email_valid($_POST['email']);

        if($msg != null)
        {
            echo $msg;

            echo "<br/><a href='javascript:self.history.back();'> Go Back </a>";         
        }
        elseif(!$check_age) 
        {
            echo "Please provide proper age";
        }
        elseif(!$check_email)
        {
            echo "Please provide proper email";
        }
        else
        {*/
            // if all the fields are filled and are not empty

            //insert the form data into the database
            
            //$result = $crud->execute("INSERT INTO Inventory(ItemName,UnitPrice,UnitCost,PackagingCost,QtyAvailable)
            //                         VALUES('$ItemName','$UnitPrice','$UnitCost','$PackagingCost','$QtyAvailable')");

            //echo "<font color='green'> Data added successfully.";
            //echo "<br/><a href='index.php'> View Result </a>";
        //}
    
?>
