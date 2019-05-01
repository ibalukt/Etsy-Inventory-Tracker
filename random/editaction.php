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
    $crud->performOperation("UPDATE",$table);

    /*$query ="SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'laurens_data' AND TABLE_NAME = '$table'";
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
    $query = "UPDATE $table SET ";
    // the id will store the value of the ItemID or ORDERID
    $id = null;

   /* foreach($cols as $key => $col)
    {
        //This stores the id, because you are not updating the value of the ID
        if ($key == 0)
        {
            $id = $vals[$key];
        }
        //This elseif gets rid of the comma at the end of the statement and adds the Corresponding WHERE statement to the end
        elseif($key == (sizeof($cols)-1))
        {
            $query = $query . "$cols[$key]='$vals[$key]'";
            $query = $query . " WHERE $cols[0]=$id";
        }
        //This is the normal statemetn that adds the new value to the column name. Example ItemName='Journal'
        else
        {
            $query = $query . "$cols[$key]='$vals[$key]',";
        }
    }
    

    $query = $crud->buildQuery($table,"UPDATE",$cols,$vals);
    echo $query;
    $result = $crud->execute($query);*/
    header("Location: details.php?table=$table");
}

    //OLD CODE ALL THE WAY DOWN AT THE BOTTOM

    




























































    //$ItemName = $crud->escape_string($_POST['ItemName']);
    //$UnitPrice = $crud->escape_string($_POST['UnitPrice']);
    //$UnitCost = $crud->escape_string($_POST['UnitCost']);
    //$PackagingCost = $crud->escape_string($_POST['PackagingCost']);
    //$QtyAvailable = $crud->escape_string($_POST['QtyAvailable']);
    //$name = $crud->escape_string($_POST['name']);
    //$age = $crud->escape_string($_POST['age']);
    //$email = $crud->escape_string($_POST['email']);

    //echo "$name";

    //$msg = $validation->check_empty($_POST, array('name', 'age', 'email'));
    //$check_age = $validation->is_age_valid($_POST['age']);
    //$check_email = $validation->is_email_valid($_POST['email']);

    /*if($msg) 
    {
        echo $msg;
        //link to the previous page
        echo "<br/> <a href='javascript:self.history.back();'> Go Back </a>";
    }
    elseif (!$check_age)
    {
        echo "Please provide proper age";
    }
    elseif (!$check_email)
    {
        echo "Please provide proper email";
    }
    else
    {*/
        //updating a table
        //echo "<script> alert('There were no validation problems'); </script>";
        //$result = $crud->execute("UPDATE Inventory SET ItemName='$ItemName',UnitPrice='$UnitPrice',UnitCost='$UnitCost',
        //                          PackagingCost='$PackagingCost',QtyAvailable='$QtyAvailable' WHERE ItemID=$id");

        //header("Location: index.php");
    //}

?>