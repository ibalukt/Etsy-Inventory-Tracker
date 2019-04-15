<?php
//this statement includes an instance of the database connection file
include_once("classes/Crud.php");
//include an instance of the crud methods so they are available for use
$crud = new Crud();

//This info is HARD CODED. If you alter the table names or Columns, this all must be redone.
$columns = array("TActionDate","FirstName","LastName","ItemName","Qty","TotalCharge");

//echo print_r($cols);
//This query will output the Transaction date, First Name, Last Name, Quantity, And Total Charge of the Order
$query = "SELECT TA.TActionDate,TA.FirstName,
          TA.LastName,I.ItemName,TAItem.Qty,TAItem.TotalCharge
          FROM TActionItem TAItem JOIN Inventory I ON TAItem.ItemID = I.ItemID JOIN TAction TA ON TAItem.TActionID = TA.TActionID";
//get all the data from the query above and store it into the $result variable
$result = $crud->getData($query);

//echo print_r($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP & MYSQL OOP CRUD SYSTEM </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
    <body>
        <a href="index.html">go back</a>
        <div class="container">
            <h2 align="center"> INTEGRATED PROJECT </h2>

            <div><a href="add.php?table=<?php echo $table; ?>"> Add New Data </a><br/></br></div>

            <!---------------------TABLE START---------------------->
            <table width="86%" class="table table-bordered">
                <thead>
                    <tr>
                        <?php
                            //create the table headings for each column of the table
                            foreach($columns as $value)
                            {
                                echo "<th>".$value ."</th>";
                            }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <!------------------PHP FOREACH LOOP START------------------->
                    <?php
                        //For every item of result
                        foreach ($result as $key => $res)
                        {
                            echo "<tr>";   
                            //create a column for every column in the table and put the result inside of it
                            foreach($columns as $value)
                            { 
                                echo "<td>".$res[$value]."</td>";
                            }
                        }
                    ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>