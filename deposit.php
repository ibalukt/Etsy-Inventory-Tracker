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
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>

    <body>

    
        <a href="details.php?table=<?php echo $table;?>"> Home </a>
        <br/><br/>
        <form name="form1" method="post" action="processorder.php">

            <div id="itemSelect">
            <input type="hidden" name="num_items" id="num_items" value="1" />
            <label for="ItemName">Which item are you depositing into inventory?</label><br/>
            <input type="textbox" name="ItemName" />
            <input type='number'id='Qty' name='QtyAvailable' value='1' min='1' onchange='calculateCost();' style='width:60px; '/>
            <input type="hidden" name="new" value="1">
            <!--<?php

                echo " <select name='ItemID1'>";
                echo  $itemlist;    
                echo "</select>";
                echo "<label for='Qty1'>How Many Of this Item? </label> ";
                echo "<input type='number'id='Qty' name='Qty1' value='1' min='1' onchange='calculateCost();' style='width:60px; '/>";
            ?>-->
            </div>

            <label for="ProductionCost"> How much did the manufacturer charge you to produce these items? </label>
            <input type="textbox" id="ProductionCost" name="ProductionCost" onkeypress="calculateCost();"  onchange="calculateCost();" /> 
            <br/>
            <label for="ShippingFee">Was there a fee to have these items shipped?</label>
            <input type="textbox" id="ShippingFee" name="ShippingFee" onkeypress="calculateCost();" onchange="calculateCost();"/>
            <br/>

            <label for="UnitCost">The cost per unit works out to be </label>
            <input type="textbox" id="UnitCost" name="UnitCost"  />
            <br/>

            <label for="UnitPrice"> How much will you sell these items for? </label>
            <input type="textbox" name="UnitPrice">
            <br/>

            <input type="hidden" name="TActionDate" value="<?php echo $today; ?>" />

            <h5> Who did you recieve these items from? </h5>
            <label for="FirstName" >FirstName</label><br/>
            <input type="textbox" name="FirstName" /><br/>
            <label for="LastName" >Last Name </label><br/>
            <input type="textbox" name="LastName" />
            <br/>
            <label for="Company"> What company did you recieve these items from? </label><br/>
            <input type="textbox" name="Company" >
            <br/>
            <!--<label for="State"> What state is this item(s) going to? </label><br/>
            <select name="State" >
                <option value="AL">AL</option>
                <option value="AK">AK</option>
                <option value="AZ">AZ</option>
                <option value="AR">AR</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DE">DE</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="IA">IA</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="ME">ME</option>
                <option value="MD">MD</option>
                <option value="MA">MA</option>
                <option value="MI">MI</option>
                <option value="MN" selected>MN</option>
                <option value="MS">MS</option>
                <option value="MO">MO</option>
                <option value="MT">MT</option>
                <option value="NE">NE</option>
                <option value="NV">NV</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NY">NY</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VT">VT</option>
                <option value="VA">VA</option>
                <option value="WA">WA</option>
                <option value="WV">WV</option>
                <option value="WY">WY</option>
            </select>-->
            <br/>
            <label for="Explanation">Notes </label><br/>
            <input type="text" name="Explanation" /><br/>

            <input type="submit" name="update" value="Update">
        </form>

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