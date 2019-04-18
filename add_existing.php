<?php
include_once("classes/Crud.php");

$crud = new Crud();

//$table = $crud->escape_string($_GET['table']);


//query to get the ItemIDs, ItemNames, Prices, and Quantities for the items. This will be so we can put the items into 
//a dropdown for the user to pick from
$query = "SELECT ItemID,ItemName,UnitPrice,UnitCost,QtyAvailable FROM Inventory";
//get the data
$items = $crud->getData($query);
//Add the itemNames and IDS to an empty array
$itemNames = array();
$itemIDs = array();

foreach ($items as $key => $item)
{
    array_push($itemNames,$item['ItemName']);
    array_push($itemIDs,$item['ItemID']);
}

//This query will get the transaction types from the db so we can choose from one.
//$query = "SELECT * FROM TActionType";
//get the transaction type information
//$types = $crud->getData($query);





$date = getdate();
$day = $date['mday'];
$month = $date['mon'];
$year = $date['year'];
$today = "$year-$month-$day";
//echo print_r($itemNames);

$itemlist = "";
foreach($itemNames as $key=>$itemName)
{
    $itemlist .= "<option value='$itemIDs[$key]'>$itemName</option>";
    
}
?>
<html>
    <head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>

    <body>

    <div class="container-fluid">
        <div class="row">
            <div id="demo" class="carousel slide col-sm-12" data-interval="false" data-ride="none">
        
                <!-- The slideshow -->
                <div class="carousel-inner">
                    <form  method="post" action="processorder.php" style="height:200px; border:2px solid purple;">
                        <!--  Hidden Fields -->
                        <input type="hidden" name="num_items" id="num_items" value="1" />
                        <input type="hidden" name="TActionDate" value="<?php echo $today; ?>" />
                        <div class="carousel-item  active" style="border:2px solid red;height:100%;" >
                            <!-- ItemName -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin:auto;">
                                <label for="ItemName">What is the name of the new item?</label><br/>
                                <select name='ItemID1'>
                                    <?php echo $itemlist; ?>
                                </select>
                            </div>
                        </div>
                        <div class="carousel-item" style="border:2px solid red; height:100%;" >
                            <!-- Qty -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin-left:auto; margin-right:auto;">
                                <label for='Qty1'>How Many Of this Item? </label> ;
                                <input type='number'id='Qty' name='Qty1' value='1' min='1' onchange='calculateCost();' style='width:60px; '/>
                            </div>
                        </div>
                        <div class="carousel-item" style="border:2px solid red; height:100%;" >
                            <!-- Company -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin-left:auto; margin-right:auto;">
                                <label for="Company"> Who produced / manufactured these items? </label><br/>
                                <input type="textbox" name="Company" placeholder="company" >
                            </div>
                        </div>
                        <div class="carousel-item" style="border:2px solid red; height:100%;" >
                            <!-- ProductionCost -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin-left:auto; margin-right:auto;">
                                <!-- Cost for the items being inserted -->
                                <label for="ProductionCost"> How much did the manufacturer charge you to produce these items? </label>
                                <input type="textbox" class="form-control" id="ProductionCost"  name="ProductionCost" onkeypress="calculateCost();"  onchange="calculateCost();" /> 
                            </div>
                        </div>
                        <div class="carousel-item" style="border:2px solid red; height:100%;" >
                            <!-- ShippingFee -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin-left:auto; margin-right:auto;">
                                <!-- Cost for the items being inserted -->
                                <label for="ShippingFee">Was there a fee to have these items shipped?</label>
                                <input type="textbox" class="form-control" id="ShippingFee" name="ShippingFee" onkeypress="calculateCost();" onchange="calculateCost();"/>
                            </div>
                        </div>
                        <div class="carousel-item" style="border:2px solid red; height:100%;" >
                            <!-- UnitCost -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin-left:auto; margin-right:auto;">
                                <!-- Cost for the items being inserted -->
                                <label for="UnitCost">Based off of the produciton cost and shipping fee the cost per unit is: </label>
                                <input type="textbox" class=form-control id="UnitCost" name="UnitCost"  />
                            </div>
                        </div>
                        <div class="carousel-item" style="border:2px solid red; height:100%;" >
                            <!-- UnitPrice -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin-left:auto; margin-right:auto;">
                                <!-- Cost for the items being inserted -->
                                <label for="UnitPrice"> How much will you sell these items for? </label>
                                <input type="textbox" class="form-control" name="UnitPrice"> 
                            </div>
                        </div>
                        <div class="carousel-item" style="border:2px solid red; height:100%;" >
                            <!-- UnitPrice -->
                            <div class="form-group mt-5" style="width:500px;border:2px solid green; margin-left:auto; margin-right:auto;">
                                <!-- Cost for the items being inserted -->
                                <label for="Explanation"> Any Further Notes for this Transaction? </label>
                                <input type="text" class="form-control" name="Explanation" /><br/>

                                <input type="submit" name="update" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-1 text-center">
            <a class="bg-dark ml-auto" href="#demo" data-slide="prev">
                <span class="carousel-control-prev-icon bg-dark"></span>
            </a>
        </div>
        <div class="col-sm-10"></div>
        <div class="col-sm-1 text-center">
            <a class=" bg-dark mr-auto" href="#demo" data-slide="next">
                <span class="carousel-control-next-icon bg-dark"></span>
            </a>
        </div>
    </div>


        <a href="details.php?table=<?php echo $table;?>"> Home </a>
        <br/><br/>

        <script>



            function calculateCost()
            {
                var ProductionCost = parseInt(document.getElementById('ProductionCost').value);
                var ShippingFee = parseInt(document.getElementById('ShippingFee').value);
                var Qty = parseInt(document.getElementById('Qty').value);

                var Cost = ((ProductionCost + ShippingFee) / Qty).toFixed(2);
                
                document.getElementById('UnitCost').value = Cost;
                //document.getElementById('UnitCost').innerHTML = Cost;
                
            }
        </script>
</body>
</html>