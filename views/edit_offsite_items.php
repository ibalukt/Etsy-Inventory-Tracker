<?php
include_once('classes/Crud.php');
$crud = new crud();

if (isset($_GET['inventory_id']))
{
    $OffSiteID = $crud->escape_string($_GET['inventory_id']);
}
else
{
    header("Location:inventory.php?");
}

$query = "SELECT * FROM OffSite OS JOIN Reason R ON OS.ReasonID = R.ReasonID  WHERE OffSiteID = ? ORDER BY OS.OffSiteID DESC";
$params = array($OffSiteID);
$OffSites = $crud->prep_getData($query,"i",$params);
//print_r($IActions);

$query = "SELECT * FROM OffSiteItem OSI JOIN Inventory I ON OSI.ItemID = I.ItemID WHERE OSI.OffSiteID = ?";
$params = array($OffSiteID);
$OffSiteItems = $crud->prep_getData($query,"i",$params);

echo "<script> var onHandQtys = [];
                var number_of_items = ".sizeof($OffSiteItems)."; </script>";

foreach($OffSiteItems as $OffSiteItem)
{
    echo "<script> onHandQtys.push('".$OffSiteItem['OnHandQty']."'); </script>";
}

echo "<header class='pb-4 pt-5 text-center' >";
    if (isset($_GET['Restock']))
        {
            echo "<h2 class='text-center text-secondary'>Log items that were restocked </h2>"; 
        }
        else
        {
            echo "<h2 class='text-center text-secondary'>Log iems that were sold</h2>"; 
        } 
echo "</header>";
    ?>
<form method='post' action='inventory.php?main'>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name='search' placeholder="search items" aria-describedby="basic-addon2" disabled>
        <div class="input-group-append">
            <button type='submit' class="input-group-text" id="basic-addon2" disabled> Search Items</button>
        </div>
    </div>
</form>
<div class='col-sm-12 border' >
    <form  method="post" id="edit_offsite_items" action="actions/offsite_editaction.php">
        <div class="form-group col-sm-12 pt-2 ml-auto mr-auto " id='itemSelect' style="">
            <input type='hidden' name='num_items' id='num_items' value="<?php echo sizeof($OffSiteItems);?>" />
            <input type='hidden' name='OffSiteID' id='OffSiteID' value="<?php echo $OffSiteID;?>" />
            <?php 
            if (isset($_GET['Return_Items']))
            {
                echo "<input type='hidden' name='Return_Items' value='1' />";
            }
            elseif (isset($_GET['Restock']))
            {
                echo "<input type='hidden' name='Restock' value='1' />";
            }
            foreach ($OffSiteItems AS $key => $OffSiteItem)
            {
                $ItemID = $OffSiteItem['ItemID'];
                $ItemName = $OffSiteItem['ItemName'];
                $InitialQty = $OffSiteItem['InitialQty'];
                $RestockQty = $OffSiteItem['RestockQty'];
                $SoldQty = $OffSiteItem['SoldQty'];
                $RemainingQty = $OffSiteItem['RemainingQty'];
                

               if (isset($_GET['Restock']))
               {
                echo"   <div class='row pt-2 pb-2'>
                            <div class='col-sm-4 mt-auto' >
                                <label for='ItemName$key'>Item Name: </label>
                                <input type='hidden' name='ItemID$key' value='$ItemID' />
                                <input type='text' class='form-control' id='ItemName$key' name='ItemName$key' value='$ItemName' readonly/>
                             </div>
                            <div class='col-sm-2 mt-auto '>
                                <label for='InitialQty$key'> Initial Qty: </label> 
                                <input type='number' class='form-control' id='InitialQty$key' name='InitialQty$key' value='$InitialQty' min='1'readonly/>
                            </div>
                            <div class='col-sm-2 mt-auto '>
                                <label for='RestockQty$key'> Restock Qty: </label> 
                                <input type='number'class='form-control' id='RestockQty$key' name='RestockQty$key' value='$RestockQty' min='0' onchange='check_qtys(); updateValues(InitialQty$key,RestockQty$key,SoldQty$key,RemainingQty$key);'/>
                            </div>
                            <div class='col-sm-2 mt-auto'>
                                <label for='PurchasedQty$key'> Purchased Qty: </label> 
                                <input type='number'class='form-control' id='SoldQty$key' name='SoldQty$key' value='$SoldQty' min='0' readonly/>
                            </div>
                            <div class='col-sm-2 mt-auto'>
                                <label for='RemainingQty$key'> Remaining Qty: </label> 
                                <input type='number'class='form-control' id='RemainingQty$key' name='RemainingQty$key' value='$RemainingQty' min='1' onchange='updateValues();' readonly/>
                            </div>
                        </div>";
               }
               else
               {
                echo"   <div class='row pt-2 pb-2'>
                            <div class='col-sm-4 mt-auto' >
                                <label for='ItemName$key'>Item Name: </label>
                                <input type='hidden' name='ItemID$key' value='$ItemID' />
                                <input type='text' class='form-control' id='ItemName$key' name='ItemName$key' value='$ItemName' readonly/>
                            </div>
                            <div class='col-sm-2 mt-auto '>
                                <label for='StartQty$key'> Initial Qty: </label> 
                                <input type='number'class='form-control' id='InitialQty$key' name='InitialQty$key' value='$InitialQty' min='1'readonly/>
                            </div>
                            <div class='col-sm-2 mt-auto '>
                                <label for='RestockQty$key'> Restock Qty: </label> 
                                <input type='number'class='form-control' id='RestockQty$key' name='RestockQty$key' value='$RestockQty' min='1'readonly/>
                            </div>
                            <div class='col-sm-2 mt-auto'>
                                <label for='PurchasedQty$key'> Purchased Qty: </label> 
                                <input type='number'class='form-control' id='SoldQty$key' name='SoldQty$key' value='$SoldQty' min='1' onchange='check_qtys(); updateValues(InitialQty$key,RestockQty$key,SoldQty$key,RemainingQty$key);' />
                            </div>
                            <div class='col-sm-2 mt-auto'>
                                <label for='RemainingQty$key'> Remaining Qty: </label> 
                                <input type='number'class='form-control' id='RemainingQty$key' name='RemainingQty$key' value='$RemainingQty' min='1' onchange='updateValues();' readonly/>
                            </div>
                        </div>";
               }
            }
            ?>
        </div>
        <!-- Add Item and Next Button -->
        <div class="form-group col-sm-12 ml-auto mr-auto">
            <div class="row">
                <div class="col-sm-9" style="border:0px solid blue;"> </div>
                <div class="col-sm-3"  style="border:0px solid red; text-align:right;">
                    <input type="submit" name="update" class="ml-auto btn btn-outline-info btn_update" value="update">
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script> 


        function check_qtys()
        {
            for (i = 0; i < number_of_items; i++)
            {
                itemName = document.getElementById('ItemName'+i).value;
                restockQty = document.getElementById('RestockQty'+i).value;
                soldQty = document.getElementById('SoldQty'+i).value;
                initialQty = document.getElementById('InitialQty'+i).value;

                console.log(number_of_items);
                if (parseInt(restockQty) > onHandQtys[i])
                {
                    console.log("it worked!");
                    bootbox.alert(itemName + " has " + restockQty + " selected, but  there is only " + onHandQtys[i] + " available.");
                    //console.log(itemNames[selected] + "has " + quantity + " selected, but  there is only " + onHandQtys[selected] + " available");
                    document.getElementById('RestockQty'+i).value = onHandQtys[i];
                }

                var can_sell = parseInt(initialQty)+parseInt(restockQty);
                if ((parseInt(soldQty)) > (can_sell))
                {
                    bootbox.alert(itemName + " has " + soldQty + " selected, but  there is only " + can_sell + " available for sale. If you want to sell more here, then restock this inventory.");
                    document.getElementById('SoldQty'+i).value = can_sell;
                }
            }
        }

        function updateValues(id1,id2,id3,id4)
        {
            
            var val1 = parseInt((id1).value);
            var val2 = parseInt((id2).value);
            var val3 = parseInt((id3).value);

            var newVal = val1 + val2 - val3;

            console.log(newVal);

            (id4).value = newVal;

        }
 
        $('.btn_update').click(function(event){
            event.preventDefault();
            var destination = ($(this).attr("href"));
            //alert(destination);
            bootbox.confirm({
                message: "Are you sure you want to update this inventory?",
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
                        edit_offsite_items.submit();
                    }
                }
            });
        });
    </script>
</div>
<a href='javascript:window.history.back();' class='btn btn-outline-primary mt-5'>Go Back</a>

<?php 

        if (isset($_GET['Return_Items']))
        {
            echo "<script> edit_offsite_items.submit(); </script>";
        } ?>