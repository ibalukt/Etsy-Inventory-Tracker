<?php

    include_once("classes/Crud.php");

    $crud = new Crud();
    
    //$table = $crud->escape_string($_GET['table']);


    //query to get the ItemIDs, ItemNames, Prices, and Quantities for the items. This will be so we can put the items into 
    //a dropdown for the user to pick from
    $query = "SELECT ItemID,ItemName,UnitPrice,QtyAvailable FROM Inventory";
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
    $query = "SELECT * FROM TActionType";
    //get the transaction type information
    $types = $crud->getData($query);





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
    </head>

    <body>
        <a href="details.php?table=<?php echo $table;?>"> Home </a>
        <br/><br/>
        <form name="form1" method="post" action="processorder.php">

            <select>
            <?php
               /* foreach ($types as $type)
                {
                    echo "<option value='$type['TypeID']'> $type['TypeID'] </option>"; 
                }*/
            ?>
            </select>

            <div id="itemSelect">
            <input type="hidden" name="num_items" id="num_items" value="1" />
            <label for="ItemName">Which item are you removing from inventory?</label><br/>
            <?php

                echo " <select name='ItemID1'>";
                echo  $itemlist;    
                echo "</select>";
                echo "<label for='Qty1'>How Many? </label> ";
                echo "<input type='number' name='Qty1' value='1' min='1' style='width:60px;'/>";
            ?>
            </div>

            <button type="button" onclick="myFunction()" >Add Item</button>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <input type="hidden" name="TActionDate" value="<?php echo $today; ?>" />
            <label for="FirstName" >First Name</label><br/>
            <input type="textbox" name="FirstName" /><br/>
            <label for="LastName" >Last Name </label><br/>
            <input type="textbox" name="LastName" />
            <br/>
            <label for="Company"> Is this order going to a company? </label><br/>
            <input type="textbox" name="Company" >
            <br/>
            <label for="State"> What state is this item(s) going to? </label><br/>
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
            </select>
            <br/>
            <label for="Explanation">Notes </label><br/>
            <input type="text" name="Explanation" /><br/>

            <input type="submit" name="update" value="Update">
        </form>

    

        <script>
            var number_of_items = 1;

            function myFunction() {
                number_of_items++;
                //set up the initial container
                var div = document.createElement("DIV");
                //set of the id for the container so we can delete it if we want
                div.id = "div"+number_of_items;
                //append the new div to the itemSelect div
                document.getElementById("itemSelect").appendChild(div);

                //create a new select
                var select = document.createElement("SELECT");
                //create the options
                var option = "<?php echo $itemlist; ?>";
                select.id="ItemID"+number_of_items;
                select.name="ItemID"+number_of_items;
                select.innerHTML = option;
                document.getElementById("div"+number_of_items).appendChild(select);

                //create the label
                var label = document.createElement("LABEL");
                label.for = "Qty"+number_of_items;
                label.id = "Qty"+number_of_items;
                label.innerHTML ="How Many?";
                //create the quantity input
                var input = "<input type='number' name='Qty"+number_of_items+"' value='1' min='1' style='width:60px;' />"

                document.getElementById("div"+number_of_items).appendChild(label);
                document.getElementById("div"+number_of_items).innerHTML += input;

                num_items.value=number_of_items;

                //create the delete button
                div.innerHTML += "<button type='button'" + "onClick='(div"+number_of_items+".remove());number_of_items--; console.log(number_of_items); num_items.value=number_of_items;'>Delete</button>";
                console.log(number_of_items);
            }
        </script>
    </body>
</html>