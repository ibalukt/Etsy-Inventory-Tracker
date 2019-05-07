<?php

    include_once("classes/Crud.php");

    $crud = new Crud();
    
    //$table = $crud->escape_string($_GET['table']);

    if (isset($_GET['GroupID']))
    {
        $GroupID = $crud->escape_string($_GET['GroupID']);
        $HiddenGroup = "<input type='hidden' name='GroupID'  value='$GroupID' />";

    }
    else
    {
        $HiddenGroup = "";
    }

    //query to get the ItemIDs, ItemNames, Prices, and Quantities for the items. This will be so we can put the items into 
    //a dropdown for the user to pick from

    $query = "SELECT * FROM Inventory ORDER BY ItemName ASC";
    //get all the data from the query above and store it into the $result variable
    $items = $crud->getData($query);
    ///print_r($items);
    //Add the itemNames and IDS to an empty array
    $itemNames = array();
    $itemIDs = array();
    $unitPrices = array();
    
    foreach ($items as $key => $item)
    {
        if ($key > 0)
        {
            array_push($itemNames,$item['ItemName']);
            array_push($itemIDs,$item['ItemID']);
            array_push($unitPrices,$item['UnitPrice']);
        }
    }

    if (isset($_GET['action']))
    {
        $action = $crud->escape_string($_GET['action']);
    }

    //This query will get the transaction types from the db so we can choose from one.
    $query = "SELECT * FROM Reasons WHERE In_Out = $action";
    //get the transaction type information
    $reasons = $crud->getData($query);

    echo "<script> var reasonIDs = [];
                   var reasons = []; 
        </script>";
    $reason_list = "";
    foreach($reasons as $key => $reason)
    {
        $reason_list .= "<option value='$reason[ReasonID]'> $reason[Reason] </option>";
        echo "<script> reasonIDs.push('".$reason['ReasonID']. "'); 
        reasons.push('".$reason['Reason']."'); </script>";
    }



    $date = getdate();
    $day = $date['mday'];
    $month = $date['mon'];
    $year = $date['year'];
    $today = "$year-$month-".($day-1);
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
        <script> var r = 0; </script>
        </head>
    <body>
        <a href="details.php?table=<?php echo $table;?>"> Home </a>


<div class="container-fluid">
    <div class="row">
        <div id="demo" class="carousel slide col-sm-12" data-interval="false" data-ride="none">    
                <!-- The slideshow -->
                <div class="carousel-inner">
                    <form  method="post" id="withdraw" action="remove_from_inventory.php" style="height:400px; border:0px solid purple;">
                        <!--  Hidden Fields -->
                        <input type="hidden" name="num_items" id="num_items" value="1" />
                        <!-- ################################ SLIDE 1 ######################################## -->
                        <div class="carousel-item  active" style="border:0px solid red;height:100%;" >
                            <div class="col-sm-7 ml-auto mr-auto text-center">
                                <?php 
                                    if ($action == 0)
                                    {
                                        echo "<h3>Which Items You Removing From Inventory?<h3>";
                                    }
                                    else
                                    {
                                        echo $HiddenGroup;
                                        echo "<h3>Which Items Are You Depositing In Inventory?</h3>";
                                    }
                                ?>
                            </div>
                            <!-- Select Items that are being removed from inventory -->
                            <div class="form-group col-sm-8 pt-2 ml-auto mr-auto border" id='itemSelect' style=" max-height:280px; height:280px; overflow-y:scroll; border:2px solid green;">
                                <div class="row pt-2 pb-2">
                                    <div class="col-sm-8 " >
                                        <label for="ItemID1">Item: </label>
                                        <select href="" class="form-control" id='ItemID1' name='ItemID1' onchange="updateValues();" >
                                            <?php echo $itemlist; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2 ">
                                        <label for='Qty'> Quantity: </label> 
                                        <input type='number'class="form-control" id='Qty1' name='Qty1' value='1' min='1' onchange='updateValues();' />
                                    </div>
                                    <div class="col-sm-2" >
                                        <br/>
                                        <button type="button" class="btn btn-block btn-outline-primary mt-2" onclick="var vals=preserveItems();var vals2=preserveQtys(); 
                                                                                       myFunction();recoverItems(vals);
                                                                                       recoverQtys(vals2);updateValues();">Add Item</button>
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
                                <?php
                                    if ($action == 0)
                                    {
                                        echo "<h3>Why are you removing these items?</h3>";
                                    }
                                    else
                                    {
                                        echo "<h3>Why are you putting these items into inventory?</h3>";
                                    }
                                ?>
                            </div>
                            <div class="form-group col-sm-8 ml-auto mr-auto border " id="details" style=" max-height:280px; height:280px; border:0px solid green;">
                                <div class="row pt-2 pb-2">
                                    <div class="col-sm-12 " >
                                        <label for="ReasonID">I am:</label>
                                        <select class="form-control"  id="ReasonID" name='ReasonID' onchange="r=this.options.selectedIndex; to.value=reasons[r];" >
                                            <?php echo $reason_list; ?>
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
                                    <button type="button" class="ml-5 btn btn-outline-info" id="whonext" href="#demo" data-slide="next" onclick="to.value=reasons[r];"  >Next</button>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- ################################ SLIDE 3 ######################################## -->
                        <!-- Select Who / Where the items are going -->
                        <div class="carousel-item" style="border:0px solid red; height:100%;" >
                            <div class='col-sm-8 ml-auto mr-auto text-center'>
                                <h3><span class="party"></span>Where Are These Items Going?</h3>
                            </div>
                            <!-- Company -->
                            <div class="form-group col-sm-8 ml-auto mr-auto border" style=" max-height:280px; height:280px; border:2px solid green;">
                                <div class="row pt-2 pb-2">
                                    <div class="col-sm-12 " >
                                        <label for="PartyName">Destination? </label>
                                        <?php 
                                            if ($action == 0)
                                            {
                                                echo "<input type='text' class='form-control' id='goingTo' name='GoingWhere' placeholder='person, company, event, etc.' onkeyup='purchased_by();updateValues(); '  
                                                onclick='purchased_by();updateValues()' required>"; 
                                            }
                                            else
                                            {
                                                echo "<input type='text' class='form-control' id='goingTo' name='GoingWhere' value='Inventory' readonly>"; 
                                            }
                                        ?>
                                        <!--<input type="text" class="form-control" id="goingTo" name='GoingWhere' placeholder="person, company, event, etc." onkeyup="purchased_by();updateValues(); "  
                                                                                                onclick="purchased_by();updateValues()" required>-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-sm-8 ml-auto mr-auto">
                                <div class="row">
                                    <div class="col-sm-2" style="border:0px solid green;">
                                        <button type="button" class="btn btn-outline-info" href="#demo" data-slide="prev">Previous</button>
                                    </div>
                                <div class="col-sm-8 " style="border:0px solid blue;"> </div>
                                    <div class="col-sm-2" id="going2"  style="border:0px solid red; text-align:right;">
                                        <?php
                                            if ($action == 1)
                                            { 
                                                echo "<button type='button' class='btn btn-outline-info' href='#demo' data-slide='next'>Next</button>"; 
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ################################ SLIDE 5 ######################################## -->
                        <div class="carousel-item" style="border:0px solid red; height:100%;" >
                            <div class='col-sm-8 ml-auto mr-auto text-center'>
                                <?php 
                                    if ($action ==0)
                                    {
                                        echo "<h3>Withdrawl Summary</h3>";
                                    }
                                    else
                                    {
                                        echo "<h3>Deposit Summary </h3>";
                                    }
                                ?>
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
                                        <div class="col-sm-8">
                                            <label>Going to be: </label>
                                            <input type="text" id="to" class="form-control" style="background-color:transparent;" value="" readonly/>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Where: </label>
                                            <input type="text" id="at" class="form-control" style="background-color:transparent;" value="" readonly/>
                                        </div>
                                    </div>
                                    <div class="row pt-2 pb-2">
                                        <div class="col-sm-8 " >
                                            <label for="sumItem1">Item:</label>
                                            <input type="textbox" class="form-control" style="background-color:transparent" id='sumItem1' name='sumItem1' value=<?php echo $itemNames[0]; ?> readonly/>
                                        </div>
                                        <div class="col-sm-2 ">
                                            <label for='sumQty1'> Quantity: </label> 
                                            <input type='textbox'class="form-control" style="background-color:transparent" id='sumQty1' name='sumQty1' value='-1' max='0'  readonly/>
                                        </div>
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
                                        <input onclick="<?php if($action==0){echo "flipQtys()";}?>" type="submit" class="btn btn-outline-success" name="submit" value="submit">
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


            function purchased_by()
            {
                var button_container = document.getElementById('going2');

                console.log(button_container.innerHTML);
                if (document.getElementById('goingTo').value != '')
                {
                    var enabled_button = "<button type='button' class='btn btn-outline-info' href='#demo' data-slide='next'  >Next</button>";
                    if (button_container.innerHTML == '')
                    {
                      button_container.innerHTML += enabled_button;
                    }                    
                }
                else
                {
                    button_container.innerHTML = "";
                }
            }

            var number_of_items = 1;
            
            function addNotes(addnote) {

                var notes = document.getElementById('Notes');

                var buttons = "<div class='row pt-2 pb-2' id='buttonarea'>" +
                                    "<div class='col-sm-8 ml-auto mr-auto text-center' >"+
                                        "<p>Do you want to add any notes about this sale?</p>"+
                                        "<span><button type='button' class='btn btn-outline-primary mr-2' onclick='addNotes(1); buttonarea.remove();' >Yes </button>"+
                                        "<button type='button' class='btn btn-outline-danger' href='#demo' data-slide='next'> No Skip </button></span>"+
                                    "</div>"+
                                "</div>";

                var note = "<div class='row pt-2 pb-2' id='notearea'>" +
                                "<div class='col-sm-12'  >"+
                                    "<label for='Notes'> Enter your notes about this sale: </label>"+
                                    "<textarea rows='5' class='form-control' id='Notes' name='Notes' onchange='sumNote.value=this.value;' onkeyup='notes();' > </textarea>"+
                                "</div>"+
                                "<div class='form-group col-sm-8 mt-2 mr-auto' >"+
                                    "<button type='button' class='btn btn-outline-danger' onclick='notebutton.remove();addNotes(0);notearea.remove();'>X</button>"+
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
                            "<div class='col-sm-8'>" + 
                                "<label for='ItemID"+number_of_items + "'>Item</label>" +
                                "<select class='form-control' id='ItemID"+number_of_items+"' name='ItemID"+number_of_items+"' onchange='updateValues();'>"+
                                    "<?php echo $itemlist; ?>"+
                                "</select>"+
                            "</div>"+
                            "<div class='col-sm-2'>" +
                                "<label for='Qty"+number_of_items + "'>Quantity</label>"+
                                "<input type='number' class='form-control' id='Qty"+number_of_items+"' name='Qty"+number_of_items+"' value='1' min='-1'  onchange='updateValues();' />" +
                            "</div>"+
                            "<div class='col-sm-2'><br/>"+
                                "<button type='button' class='btn btn-block btn-danger mt-2'" + "onClick='(div"+number_of_items+".remove());(sum"+number_of_items+".remove());number_of_items--; console.log(number_of_items); num_items.value=number_of_items;'>Remove</button>"+
                            "</div>"
                       "</div>";

                 sum = "<div class='row pt-2 pb-2' id='sum"+ number_of_items + "' >" +
                        "<div class='col-sm-8'>" + 
                            "<label for='sumItem"+number_of_items + "'>Item</label>" +
                            "<input type='text' class='form-control' style='background-color:transparent;' id='sumItem"+number_of_items +"'name='sumItem"+number_of_items+"'value='"+itemNames[0]+"' readonly/>"+
                        "</div>"+
                        "<div class='col-sm-2'>" +
                            "<label for='sumQty"+number_of_items + "'>Quantity</label>"+
                            "<input type='text' class='form-control' style='background-color:transparent;' id='sumQty"+number_of_items +"' name='sumQty"+number_of_items+"' value='1' min='1' readonly/>" +
                        "</div>"+
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

                    //###################### SUMMARY SLIDE ###################################
                    //set the item Name for the summary item field
                    document.getElementById('sumItem'+i).value = itemNames[selected];
                    //set the quantity for the summary Qty field
                    document.getElementById('sumQty'+i).value = quantity;

                }

                var destination = document.getElementById('goingTo').value;

                document.getElementById('at').value = destination;

                
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
            purchased_by();
        </script>
    </body>
</html>