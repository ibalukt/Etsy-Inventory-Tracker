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
    $unitPrices = array();
    
    foreach ($items as $key => $item)
    {
        array_push($itemNames,$item['ItemName']);
        array_push($itemIDs,$item['ItemID']);
        array_push($unitPrices,$item['UnitPrice']);
    }

    //This query will get the transaction types from the db so we can choose from one.
    $query = "SELECT * FROM Party WHERE InOrOut = 0";
    //get the transaction type information
    $parties = $crud->getData($query);

    echo "<script>var partyTypes = [];</script>";

    $partylist = "";
    foreach($parties as $party)
    {
        $partylist .= "<option value='$party[PartyID]'> $party[PartyType] </option>";
        echo "<script> partyTypes.push('".$party['PartyType'] ."'); </script>";

    }

    echo "<script>console.log(partyTypes);</script>";






    $date = getdate();
    $day = $date['mday'];
    $month = $date['mon'];
    $year = $date['year'];
    $today = "$month-".($day-1)."-$year";
    //echo print_r($itemNames);

    //write some javascript and create a variable to load the item names and prices into.
    echo "<script> var itemNames = []; 
                   var unitPrices =[];  </script>";
    //create a string variable to load the options for the select input into
    $itemlist = "";
    foreach($itemNames as $key=>$itemName)
    {
        //add one of the options for each iteration through the loop
        $itemlist .= "<option value='$itemIDs[$key]'>$itemName</option>";
        //Add one of the item names to the array.
        echo "<script> itemNames.push('".$itemName."'); 
                       unitPrices.push('".$unitPrices[$key]."'); </script>";
        
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
        <a href="details.php?table=<?php echo $table;?>"> Home </a>


<div class="container-fluid">
    <div class="row">
        <div id="demo" class="carousel slide col-sm-12" data-interval="false" data-ride="none">    
                <!-- The slideshow -->
                <div class="carousel-inner">
                    <form  method="post" id="withdraw" action="processorder.php" style="height:400px; border:0px solid purple;">
                        <!--  Hidden Fields -->
                        <input type="hidden" name="num_items" id="num_items" value="1" />
                        <input type="hidden" name="TActionDate" value="<?php echo $today; ?>" />
                        <!-- ################################ SLIDE 1 ######################################## -->
                        <div class="carousel-item  active" style="border:0px solid red;height:100%;" >
                            <div class="col-sm-7 ml-auto mr-auto text-center">
                                <h3>Select the items that were purchased</h3>
                            </div>
                            <!-- Select Items that are being removed from inventory -->
                            <div class="form-group col-sm-8 pt-2 ml-auto mr-auto border" id='itemSelect' style=" max-height:280px; height:280px; overflow-y:scroll; border:2px solid green;">
                                <div class="row pt-2 pb-2">
                                    <div class="col-sm-4 " >
                                        <label for="ItemID1">Item: </label>
                                        <select href="" class="form-control" id='ItemID1' name='ItemID1' onchange="document.getElementById('UnitPrice1').value = unitPrices[this.options.selectedIndex];updateValues();" >
                                            <?php echo $itemlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <label for='Qty'> Quantity: </label> 
                                        <input type='number'class="form-control" id='Qty1' name='Qty1' value='1' min='1' onchange='updateValues();' />
                                    </div>
                                    <div class="col-sm-2 ">
                                        <label for='UnitPrice1'> Unit Price: </label> 
                                        <input type='text'class="form-control" id='UnitPrice1' name='UnitPrice1' value="" onchange="updateValues();" />
                                    </div>
                                    <div class="col-sm-2" >
                                        <label for='Total1'> Item Total: </label>
                                        <input type="text" class="form-control" id="Total1" name="Total1" readonly/>
                                    </div>
                                    <div class="col-sm-2" >
                                        <br/>
                                        <button type="button" class="btn btn-block btn-outline-primary mt-2" onclick="var vals=preserveItems();var vals2=preserveQtys(); 
                                                                                       var vals3=preservePrices();myFunction();recoverItems(vals);
                                                                                       recoverQtys(vals2);recoverPrices(vals3);updateValues();">Add Item</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Add Item and Next Button -->
                            <div class="form-group col-sm-8 ml-auto mr-auto">
                                <div class="row">
                                <div class="col-sm-2" style="border:0px solid green;">
                                <!--<button type="button" class="btn btn-primary" onclick="var vals=preserveItems();var vals2=preserveQtys(); 
                                                                                       var vals3=preservePrices();myFunction();recoverItems(vals);
                                                                                       recoverQtys(vals2);recoverPrices(vals3);updateValues();">Add Item</button>-->
                                </div>
                                <div class="col-sm-8" style="border:0px solid blue;"> </div>
                                <div class="col-sm-2"  style="border:0px solid red; text-align:right;">
                                    <button type="button" class="ml-5 btn btn-outline-info" href="#demo" data-slide="next" >Next</button>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- ################################ SLIDE 2 ######################################## -->
                        <!-- Select Who / Where the items are going -->
                        <div class="carousel-item" style="border:0px solid red; height:100%;" >
                            <div class='col-sm-8 ml-auto mr-auto text-center'>
                                <h3>Who purchased these items?</h3>
                            </div>
                            <!-- Company -->
                            <div class="form-group col-sm-8 ml-auto mr-auto border " id="details" style=" max-height:280px; height:280px; border:0px solid green;">
                                <div class="row pt-2 pb-2">
                                    <div class="col-sm-12 " >
                                        <label for="PartyID">These items were purchased by a:</label>
                                        <select class="form-control"  id="PartyID" name='PartyID' onchange="var p = document.getElementsByClassName('party'); 
                                                                                                            for(i=0; i<p.length; i++)
                                                                                                            {p[i].innerHTML=partyTypes[this.options.selectedIndex];} 
                                                                                                            ">
                                            <?php echo $partylist; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-8 ml-auto mr-auto">
                                <div class="row">
                                <div class="col-sm-2" style="border:0px solid green;">
                                    <button type="button" class="btn btn-outline-info" href="#demo" data-slide="prev">Previous</button>
                                </div>
                                <div class="col-sm-8" style="border:0px solid blue;"> </div>
                                <div class="col-sm-2"  style="border:0px solid red; text-align:right;">
                                    <button type="button" class="ml-5 btn btn-outline-info" id="whonext" href="#demo" data-slide="next" >Next</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- ################################ SLIDE 3 ######################################## -->
                        <!-- Select Who / Where the items are going -->
                        <div class="carousel-item" style="border:0px solid red; height:100%;" >
                            <div class='col-sm-8 ml-auto mr-auto text-center'>
                                <h3><span class="party"></span> Info</h3>
                            </div>
                            <!-- Company -->
                            <div class="form-group col-sm-8 ml-auto mr-auto border" style=" max-height:280px; height:280px; border:2px solid green;">
                                <div class="row pt-2 pb-2">
                                    <div class="col-sm-12 " >
                                        <label for="PartyName"><span class="party"> </span> name: </label>
                                        <input type="text" class="form-control" id="goingTo" name='PartyName' onchange="to.value=this.value;" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-8 ml-auto mr-auto">
                                <div class="row">
                                    <div class="col-sm-2" style="border:0px solid green;">
                                        <button type="button" class="btn btn-outline-info" href="#demo" data-slide="prev">Previous</button>
                                    </div>
                                <div class="col-sm-8  " style="border:0px solid blue;"> </div>
                                    <div class="col-sm-2"  style="border:0px solid red; text-align:right;">
                                        <button type="button" class="btn btn-outline-info" href="#demo" data-slide="next" >Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ################################ SLIDE 4 ######################################## -->
                        <!-- Add any further notes or reminders about the items being removed -->
                        <div class="carousel-item" style="border:0px solid red; height:100%;" >
                            <div class='col-sm-8 ml-auto mr-auto text-center'>
                                <h3>Notes</h3>
                            </div>
                            <!-- Explanation -->
                            <div class="form-group col-sm-8 ml-auto mr-auto border" id="Notes" style=" max-height:280px; height:280px; border:2px solid green;">
                                <div class='row pt-2 pb-2' id='buttonarea'>
                                    <div class='col-sm-8 ml-auto mr-auto text-center' style="border:0px solid red;" >
                                        <p>Do you want to add any notes about this sale?</p>
                                        <span><button type="button" class='btn btn-outline-primary' onclick='addNotes(1); buttonarea.remove();' >Yes </button> 
                                        <button type="button" class='btn btn-outline-danger' href="#demo" data-slide="next" > No Skip </button></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-8 ml-auto mr-auto">
                                <div class="row">
                                <div class="col-sm-2" style="border:0px solid green;">
                                    <button type="button" class="btn btn-outline-info" href="#demo" data-slide="prev">Previous</button>
                                </div>
                                <div class="col-sm-8" style="border:0px solid blue;"> </div>

                                </div>
                            </div>
                            <!-- Add the note or no note buttons!!!!!!!!-->
                        </div>

                        <!-- ################################ SLIDE 5 ######################################## -->
                        <div class="carousel-item" style="border:0px solid red; height:100%;" >
                            <div class='col-sm-8 ml-auto mr-auto text-center'>
                                <h3>Summary</h3>
                            </div>
                            <!-- Explanation -->
                            <div class="form-group col-sm-8 ml-auto mr-auto border" style=" max-height:280px; height:280px; overflow-x:none; overflow-y:scroll; border:2px solid green;">
                                
                                <div id="summary">
                                    <div class="row pt-2 pb-2">
                                        <div class="col-sm-12">
                                            <label>Date:</label><input type="text" class="form-control" style="background-color:transparent;" value="<?php echo $today;?>" readonly/> 
                                        </div>
                                    </div>
                                    <div class="row pt-2 pb-2">
                                        <div class="col-sm-12">
                                            <label>Purchased By: </label><input type="text" id="to" class="form-control" style="background-color:transparent;" value="" readonly/>
                                        </div>
                                    </div>
                                    <div class="row pt-2 pb-2">
                                        <div class="col-sm-4 " >
                                            <label for="sumItem1">Item:</label>
                                            <input type="textbox" class="form-control" style="background-color:transparent" id='sumItem1' name='sumItem1' value=<?php echo $itemNames[0]; ?> readonly/>
                                        </div>
                                        <div class="col-sm-2 ">
                                            <label for='sumQty1'> Quantity: </label> 
                                            <input type='textbox'class="form-control" style="background-color:transparent" id='sumQty1' name='sumQty1' value='-1' max='0'  readonly/>
                                        </div>
                                        <div class="col-sm-2 ">
                                            <label for='UnitPrice1'> Sold At: </label> 
                                            <input type='text'class="form-control" id='sumPrice1' name='UnitPrice1' value="" />
                                        </div>
                                        <div class="col-sm-2" >
                                            <label for='Total1'> Item Total: </label>
                                            <input type="text" class="form-control" id="sumTotal1" name="total1" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-2 pb-2">
                                    <div class="col-sm-12">
                                        <label for="sumNote">Notes:</label>
                                        <textarea class="form-control" name="sumNote" id="sumNote"> </textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-8 ml-auto mr-auto">
                                <div class="row">
                                    <div class="col-sm-2" style="border:0px solid green;">
                                        <button type="button" class="btn btn-outline-primary" href="#demo" data-slide="prev">Previous</button>
                                    </div>
                                    <div class="col-sm-8"> </div>
                                    <div class="col-sm-2" style="text-align:center;">
                                        <input onclick="flipQtys();" type="submit" class="btn btn-outline-success" name="update" value="Update">
                                    </div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--
    <div class="row">
        <div class="col-sm-1 text-center">
            <a class="btn btn-primary ml-auto" href="#demo" data-slide="prev">
                next
            </a>
        </div>
        <div class="col-sm-10"></div>
        <div class="col-sm-1 text-center">
            <a class=" bg-dark mr-auto" href="#demo" data-slide="next" onclick="var p = document.getElementsByClassName('party'); for(i=0; i<p.length; i++){p[i].innerHTML=partyTypes[PartyID.options.selectedIndex];} goingTo.value=to.value;">
                <span class="carousel-control-next-icon bg-dark"></span>
            </a>
        </div>
    </div>-->

    

        <script>
            var number_of_items = 1;
            
            function addNotes(addnote) {

                var notes = document.getElementById('Notes');

                var buttons = "<div class='row pt-2 pb-2' id='buttonarea'>" +
                                    "<div class='col-sm-8 ml-auto mr-auto text-center' >"+
                                        "<p>Do you want to add any notes about this sale?</p>"+
                                        "<span><button type='button' class='btn btn-primary mr-2' onclick='addNotes(1); buttonarea.remove();' >Yes </button>"+
                                        "<button type='button' class='btn btn-danger'> No Skip </button></span>"+
                                    "</div>"+
                                "</div>";

                var note = "<div class='row pt-2 pb-2' id='notearea'>" +
                                "<div class='col-sm-12'  >"+
                                    "<label for='Notes'> Enter your notes about this sale: </label>"+
                                    "<textarea rows='5' class='form-control' id='Notes' name='Notes' onchange='sumNote.value=this.value;' > </textarea>"+
                                "</div>"+
                                "<div class='form-group col-sm-8 mt-2 mr-auto' >"+
                                    "<button type='button' class='btn btn-danger' onclick='addNotes(0);notearea.remove();'>Just Kidding</button>"+
                                "</div>"+
                            "</div>";
                
                if (addnote == 1)
                {
                    notes.innerHTML += note;
                }
                else
                {
                    notes.innerHTML += buttons;
                }
            }

            function myFunction() {
                number_of_items++;
                //set up the initial container
                var itemSelect = document.getElementById("itemSelect");
                var summary = document.getElementById('summary');
                var row = document.createElement("DIV");
                //set of the id for the container so we can delete it if we want
                //row.id = "div"+number_of_items;
                row = "<div class='row pt-2 pb-2' id='div"+ number_of_items + "' >" +
                            "<div class='col-sm-4'>" + 
                                "<label for='ItemID"+number_of_items + "'>Item</label>" +
                                "<select class='form-control' id='ItemID"+number_of_items+"' name='ItemID"+number_of_items+"' onchange=UnitPrice"+number_of_items+".value=unitPrices[this.options.selectedIndex];updateValues();>"+
                                    "<?php echo $itemlist; ?>"+
                                "</select>"+
                            "</div>"+
                            "<div class='col-sm-2'>" +
                                "<label for='Qty"+number_of_items + "'>Quantity</label>"+
                                "<input type='number' class='form-control' id='Qty"+number_of_items+"' name='Qty"+number_of_items+"' value='1' min='-1'  onchange='updateValues();' />" +
                            "</div>"+
                            "<div class='col-sm-2'>"+
                                "<label for='UnitPrice"+number_of_items+ "'>Unit Price </label>"+
                                "<input type='text' class='form-control' id='UnitPrice"+number_of_items+"' name='UnitPrice"+number_of_items+"' onchange='updateValues();'/>"+
                            "</div>"+
                            "<div class='col-sm-2'>" +
                                "<label for='Total"+number_of_items+ "'> Item Total </label>" +
                                "<input type='text' class='form-control' id='Total"+number_of_items+"' name='Total"+number_of_items+"' readonly/>"+
                            "</div>"+
                            "<div class='col-sm-2'><br/>"+
                                "<button type='button' class='btn btn-block btn-danger mt-2'" + "onClick='(div"+number_of_items+".remove());(sum"+number_of_items+".remove());number_of_items--; console.log(number_of_items); num_items.value=number_of_items;'>Remove</button>"+
                            "</div>"
                       "</div>";

                sum = "<div class='row pt-2 pb-2' id='sum"+ number_of_items + "' >" +
                        "<div class='col-sm-4'>" + 
                            "<label for='sumItem"+number_of_items + "'>Item</label>" +
                            "<input type='text' class='form-control' style='background-color:transparent;' id='sumItem"+number_of_items +"'name='sumItem"+number_of_items+"'value='"+itemNames[0]+"' readonly/>"+
                        "</div>"+
                        "<div class='col-sm-2'>" +
                            "<label for='sumQty"+number_of_items + "'>Quantity</label>"+
                            "<input type='text' class='form-control' style='background-color:transparent;' id='sumQty"+number_of_items +"' name='sumQty"+number_of_items+"' value='1' min='1' readonly/>" +
                        "</div>"+
                        "<div class='col-sm-2'>"+
                                "<label for='sumPrice"+number_of_items+ "'>Unit Price </label>"+
                                "<input type='text' class='form-control' id='sumPrice"+number_of_items+"' name='sumPrice"+number_of_items+"' />"+
                            "</div>"+
                            "<div class='col-sm-2'>" +
                                "<label for='sumTotal"+number_of_items+ "'> Item Total </label>" +
                                "<input type='text' class='form-control' id='sumTotal"+number_of_items+"' name='sumTotal"+number_of_items+"' />"+
                        "</div>"
                    "</div>";

                
                //append the new div to the itemSelect div
                itemSelect.innerHTML += row;
                summary.innerHTML += sum;
                num_items.value=number_of_items;
                //console.log(num_items.value);

            }

            function updateValues()
            {
                for (i=1; i<=number_of_items;i++)
                {
                    //##################### ITEM SELECT SLIDE ################################
                    //get the selected Item from the select input
                    var selected = document.getElementById('ItemID'+i).options.selectedIndex;
                    //get the quantity that was selected
                    var quantity = document.getElementById('Qty'+i).value;
                    //get the price of the item
                    
                    var price;
                    
                    //If the price field is empty and nees a value
                    if (document.getElementById('UnitPrice'+i).value === "")
                    { 
                        //get the price from the selected item
                        price = unitPrices[selected];
                        //fill that price field with the price that was just defined
                        document.getElementById('UnitPrice'+i).value = price;
                    }
                    //if there is already something there
                    else
                    {
                        //keep that number and use it as the price.
                        price = document.getElementById('UnitPrice'+i).value;
                    }
                    
                    //get the total of the price multiplied by the quantity
                    var total = price * quantity;
                    //Make the total displayed with two decimal places.      
                    var total = total.toFixed(2);
                    //set the total to the variable calculated above
                    document.getElementById('Total'+i).value = total;

                    //###################### SUMMARY SLIDE ###################################
                    //set the item Name for the summary item field
                    document.getElementById('sumItem'+i).value = itemNames[selected];
                    //set the quantity for the summary Qty field
                    document.getElementById('sumQty'+i).value = quantity;
                    //set the price field for the summary Qty field
                    document.getElementById('sumPrice'+i).value = price;
                    //set the summary total field
                    document.getElementById('sumTotal'+i).value = total;



                }
            }

            function preserveItems() 
            {
                values = null;
                values = [];
            
                for (i=1;i<=number_of_items;i++)
                {
                    values.push(document.getElementById('ItemID'+i).options.selectedIndex);
                }
                
                return values;
            }

            function recoverItems(values) 
            {
                //console.log(select_list_values);
                //document.getElementById('ItemID1').options.selectedIndex = select_list_values[0];
                for (i=1;i<=values.length;i++)
                {
                    document.getElementById('ItemID'+i).options.selectedIndex = values[i-1];
                    document.getElementById('sumItem'+i).value = itemNames[values[i-1]];
                    console.log(values[i-1]);
                }

            }

            function preserveQtys()
            {
                values = null;
                values = [];
                for (i=1;i<=number_of_items;i++)
                {
                    values.push(document.getElementById('Qty'+i).value);
                }

                return values;
            }

            function recoverQtys(values)
            {
                for (i=1;i<=values.length;i++)
                {
                    document.getElementById('Qty'+i).value = values[i-1];
                    document.getElementById('sumQty'+i).value = values[i-1];
                }
            }

            function preservePrices()
            {
                values = null;
                values = [];
                for (i=1;i<=number_of_items;i++)
                {
                    values.push(document.getElementById('UnitPrice'+i).value);
                }

                return values;
            }

            function recoverPrices()
            {                
                for (i=1;i<=values.length;i++)
                {
                    document.getElementById('UnitPrice'+i).value = values[i-1];
                    document.getElementById('sumPrice'+i).value = values[i-1];
                }
            }

            function flipQtys()
            {
                for(i=1;i<=number_of_items;i++)
                {
                    document.getElementById('Qty'+i).min = -100;
                    document.getElementById('Qty'+i).value *= -1;
                    console.log("Qty"+i + ":" + document.getElementById('Qty'+i).value);
                }
            }

            updateValues();
        </script>
    </body>
</html>