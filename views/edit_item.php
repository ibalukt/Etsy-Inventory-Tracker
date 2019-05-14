<?php 
include_once('classes/Crud.php');
$crud = new crud();

    if (isset($_GET['ItemID']))
    {
        $ItemID = $crud->escape_string($_GET['ItemID']);

        $query = "SELECT * FROM Inventory Where ItemID = ?";
        $params = array($ItemID);
        $Items = $crud->prep_getData($query,"i",$params);
    }
    else
    {
        header("Location:inventory.php?");
    }

    if (isset($_GET['error']))
    {
        echo "<script> message = 'There was an error. Please make sure you dont leave any fields empty.'; </script>";
    }

?>
<h2 class='text-center text-secondary pt-5 pb-4'> Edit this item </h2>
<form  method="post" id="edit_subinventory" action="actions/editaction.php">
    <div class="form-group col-sm-12 pt-2 ml-auto mr-auto border" id='itemSelect' style="">
        <input type='hidden' name='ItemID' id='ItemID' value="<?php echo $ItemID;?>" />
        <?php 
        if (isset($_GET['ItemID']))
        {
            foreach ($Items AS $key => $Item)
            {
                $ItemID = $Item['ItemID'];
                $ItemName = $Item['ItemName'];
                $OnHandQty = $Item['OnHandQty'];
                $OffSiteQty = $Item['OffSiteQty'];
                $EtsyQty = $Item['EtsyQty'];
                $TotalQty = $Item['TotalQty'];
                
                echo"   <div class='row pt-2 pb-2'>
                            <div class='col-sm-4 mt-auto' >
                                <label for='ItemName'>Item Name: </label>
                                <input type='text' class='form-control' name='ItemName' value='$ItemName' />
                            </div>
                            <div class='col-sm-2 mt-auto '>
                                <label for='OnHandQty'> On Hand Qty: </label> 
                                <input type='number'class='form-control' id='OnHandQty' name='OnHandQty' value='$OnHandQty' min='1'/>
                            </div>
                            <div class='col-sm-2 mt-auto '>
                                <label for='OffSiteQty'> Off Site Qty: </label> 
                                <input type='number'class='form-control' id='OffSiteQty' name='OffSiteQty' value='$OffSiteQty' min='1' readonly/>
                            </div>
                            <div class='col-sm-2 mt-auto'>
                                <label for='EtsyQty'> Etsy Qty: </label> 
                                <input type='number'class='form-control' id='SoldQty' name='EtsyQty' value='$EtsyQty' min='1' readonly/>
                            </div>
                            <div class='col-sm-2 mt-auto'>
                                <label for='TotalQty'> Total Qty: </label> 
                                <input type='number'class='form-control' id='TotalQty' name='TotalQty' value='$TotalQty' min='1' readonly/>
                            </div>
                        </div>";
            
            }
        }
        ?>
    </div>
    <!-- Add Item and Next Button -->
    <div class="form-group col-sm-12 ml-auto mr-auto">
        <div class="row">
            <div class="col-sm-2" style="border:0px solid blue;"> 
                <a href='javascript:window.history.back();' class='btn btn-outline-primary'>Go Back</a>
            </div>
            <div class='col-sm-7'>
            </div>
            <div class="col-sm-3"  style="border:0px solid red; text-align:right;">
                <input type="submit" name="update" class="ml-auto btn btn-outline-info btn_update" value="update">
            </div>
        </div>
    </div>
</form>
        

<script> 

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
                edit_subinventory.submit();
            }
        }
    });
});
</script>

