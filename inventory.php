
<?php

//this statement includes an instance of the database connection file
include_once("classes/Crud.php");
//include an instance of the crud methods so they are available for use
$crud = new Crud();
//fetch the data from the database
$query = "SELECT * FROM Inventory WHERE State = 'active' ORDER BY ItemName ASC";
//get all the data from the query above and store it into the $result variable
$results = $crud->getData($query);

$query = "SELECT * FROM OffSite WHERE EndDate IS NULL";
$OffSites = $crud->getData($query);

//print_r($OffSites);
$icons = array("gift","coffee","book","store");
$icon_graphics = array("<i class='fas fa-gift ml-auto mr-auto pt-3 text-secondary' style='font-size:3.5em;'></i>",
"<i class='fas fa-coffee ml-auto mr-auto pt-3 text-secondary' style='font-size:3.5em;'></i>",
"<i class='fas fa-book-open ml-auto mr-auto pt-3 text-secondary' style='font-size:3.5em;'></i>",
"<i class='fas fa-store ml-auto mr-auto pt-3 text-secondary' style='font-size:3.5em;'></i>");
$GoingWhereIcons = array();
$GoingWheres = array();
$GoingWhereID = array();

foreach($OffSites as $key => $OffSite)
{
    $match = 0;
    $new_val = $OffSite['GoingWhere'];
    $id = $OffSite['OffSiteID'];
    $icon = "";
    foreach($GoingWheres as $GoingWhere)
    {
        if($GoingWhere == $OffSite['GoingWhere'])
        {
            $match ++;
        }
    }
    if ($match == 0)
    {
        array_push($GoingWhereID,$id);
        array_push($GoingWheres,$new_val);
        foreach($icons as $index => $icon)
        {
            $found = false;
            if (strpos(strtolower($new_val),$icon) !== false)
            {
                array_push($GoingWhereIcons,$icon_graphics[$index]);
                $found=true;
            }
            if (($index == (sizeof($icons)-1)) && ($found==false))
            {
                array_push($GoingWhereIcons,$icon_graphics[$index]);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP & MYSQL OOP CRUD SYSTEM </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">-->
    <style>
        
    </style>


</head>
    <body>
        <?php include_once('nav.php'); ?>
        <div class="container">
        <header class='p-4 text-center'>
            <h3 class='text-secondary'>  My Inventory </h2>
        </header>
            <?php
                if ($results == false)
                {

                }
            ?>
            <!--<div><a href="add.php?table=<?php echo $table; ?>"> Add New Data </a><br/></br></div>-->
            <!---------------------TABLE START---------------------->
            <div class='col-sm-12' >  </div>
            <div class='col-sm-12 border' style="overflow-y:scroll; max-height:280px; " >
                <table width="86%" class="table table-sm" style=" position:relative; ">
                        <?php if ($results != false)
                            {
                                echo "<thead >
                                        <tr class='text-center'>                                     
                                            <th>Item Name</th>
                                            <th>On Hand </th>
                                            <th>OffSite </th>
                                            <th>Etsy </th>
                                            <th>Total Qty</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody >";
                            // PHP FOREACH LOOP START <th>Packaging Cost</th> <th>Unit Cost </th>
                        
                                //For every item of result
                                foreach ($results as $key => $result)
                                {
                                    $ItemID = $result['ItemID'];
                                    echo "<tr class='text-center'>";   
                                    //create a column for every column in the table and put the result inside of it
                                    //echo "<td> <input type='text' class='form-control' value='".$result['ItemID']."' /></td>";
                                    echo "<td> <input type='text'class='form-control' style='border:none;' value='".$result['ItemName']."' /></td>";
                                    //echo "<td>".$result['UnitPrice']."</td>";
                                    //echo "<td>".$result['UnitCost']."</td>";
                                    //echo "<td>".$result['PackagingCost']."</td>";
                                    echo "<td>".$result['OnHandQty']. "</td>";
                                    echo "<td>".$result['OffSiteQty']. "</td>";
                                    echo "<td>".$result['EtsyQty']."</td>";
                                    if ($result['EtsyQty'] != $result['TotalQty'])
                                    {
                                        echo "<td class='text-danger'>".$result['TotalQty']." </td>";
                                    }
                                    else
                                    {
                                        echo "<td>".$result['TotalQty']."</td>";
                                    }
                                    //echo "<td>".$result['CostModDate']."</td>";
                                    //For Every Item in the results create and edit and delete button.
                                    echo "<td> <a class='p-2' href='edit_item.php?ItemID=".$result['ItemID']."'><i class='fas fa-pencil-alt' style='font-size:1.5em;'></i></a>";
                                    echo "<a class='btn_delete p-2' href='delete.php?ItemID=$ItemID' ><i class='fas fa-minus-circle text-danger' style='font-size:1.5em;'></i></a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                
                            }
                            else
                            {
                                echo "<div class='col-sm-12 text-center mt-3'>
                                        <p class='ml-auto mr-auto'>It looks like you don't have any records in your inventory. </p>
                                        <a class='btn btn-primary ml-auto mr-auto;' href='pull_active_listings.php'> Pull Etsy Listings</a>
                                    </div>";
                            }
                        ?>
                        <!----------------\"delete.php?id=$res[id]\"--LOOP ENDS-------------------->
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($results != false) { ?>
        <h3 class='text-secondary text-center p-4'>  My Open Off-Site Inventories </h2>
        <div class='container' style="position:relative;">
            <div class='col-sm-12 ' style="max-height:170px;">
                <div id="demo" class="carousel slide"  data-interval="false" data-ride="none">
                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class='carousel-item active'>
                            <div class='col-sm-12 ml-auto mr-auto'>
                                <div class='row'>
                                    <div class='col-sm-3'>
                                        <a href='withdraw.php' style='text-decoration:none;'>
                                            <div class='card'>
                                                <i class='fas fa-plus ml-auto mr-auto pt-3 text-secondary' style='font-size:3.5em;'></i>
                                                <div class='card-body text-center'> 
                                                <p class='card-text'>Add an Offsite Inventory</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>

                        <?php
                        foreach ($GoingWheres as $key => $GoingWhere)
                        {
                            if ((($key % 3) == 0) && ($key > 0))
                            {
                            echo "
                                            </div>
                                        </div>
                                    </div>
                                    <div class='carousel-item'>
                                        <div class='col-sm-12 ml-auto mr-auto'>
                                            <div class='row'>";
                            }
                            echo    
                                "
                                <div class='col-sm-3'>
                                    <a href='offsite_items.php?OffSiteID=$GoingWhereID[$key]' style='text-decoration:none'>
                                    <div class='card'>
                                        $GoingWhereIcons[$key]
                                        <div class='card-body text-center'> 
                                        <p class='card-text'>$GoingWheres[$key]</p>
                                        </div>
                                    </div>
                                    </a>
                                </div>";  

                        }
                        ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Left and right controls -->
                <a class="carousel-control-prev" style="position:absolute; left:-80px;"  href="#demo" data-slide="prev">
                    <span><i class="fa fa-angle-left text-secondary" style='font-size:3em;' aria-hidden="true"></i></span>
                </a>
                <a class="carousel-control-next" style="position:absolute; right:-80px;" href="#demo" data-slide="next">
                    <span><i class="fa fa-angle-right text-secondary" style='font-size:3em;' aria-hidden="true"></i></span>
                </a>
            </div>
        </div>
        <?php } ?>
        <footer style='height:200px;'>
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        
    <!-- bootbox code -->

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

        <script> 
            $('.btn_delete').click(function(event){
                event.preventDefault();
                 var destination = ($(this).attr("href"));
                 //alert(destination);
                bootbox.confirm({
                    message: "Are you sure that you want to Delete this record?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                        
                    },
                    callback: function (result) {
                        if (result==true)
                        {
                            window.location=destination;
                        }
                    }
                });
            });
        </script>
    </body>
</html>
