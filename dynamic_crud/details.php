<?php
//this statement includes an instance of the database connection file
include_once("classes/Crud.php");
//include an instance of the crud methods so they are available for use
$crud = new Crud();
//get the table that will be used to load the pages data.
$table = $crud->escape_string($_GET['table']);

$cols = $crud->getCols($table);
//DEBUG echo print_r($cols, true);

print_r($cols);

//echo print_r($cols);
//fetch the data from the database
$query = "SELECT * FROM $table";
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
                            foreach($cols as $value)
                            {
                                echo "<th>".$value ."</th>";
                            }
                        ?>
                        <th>Actions</th>
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
                            foreach($cols as $value)
                            { 
                                echo "<td>".$res[$value]."</td>";
                            }
                            //For Every Item in the results create and edit and delete button.
                            echo "<td> <a href='edit.php?table=".$table."&id=".$res[$cols[0]]."'>Edit</a> |".
                            " <a onclick='confirm('Are you sure that you want to delete?'); "." <a href='delete.php?table=".$table."&id=".$res[$cols[0]]."'>Delete</a>"."</td>";
                        }
                    ?>
                    <!----------------\"delete.php?id=$res[id]\"--LOOP ENDS-------------------->
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>