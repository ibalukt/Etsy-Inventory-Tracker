<?php 
    $OffSiteID = $crud->escape_string($_GET['inventory_id']);
    //$query = "SELECT * FROM OffSite WHERE OffSiteID = ?";
    $query = "SELECT * FROM OffSite OS JOIN Reason R ON OS.ReasonID = R.ReasonID WHERE OffSiteID = ?";
    $params = array($OffSiteID);
    $OffSites = $crud->prep_getData($query,"i",$params);

    $query = "SELECT * FROM OffSiteItem OSI JOIN Inventory I ON OSI.ItemID = I.ItemID WHERE OSI.OffSiteID = ?";
    $params = array($OffSiteID);
    $OffSiteItems = $crud->prep_getData($query,"i",$params); 
    ?>


<header class='pb-4 pt-5 text-center'>
    <h2 class='text-secondary'> <?php echo $OffSites[0]['GoingWhere']." Items"; ?> </h2>
</header>
<form method='post' action='inventory.php?main'>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name='search' placeholder="search items" aria-describedby="basic-addon2" disabled>
        <div class="input-group-append">
            <button type='submit' class="input-group-text" id="basic-addon2" disabled> Search Items</button>
        </div>
    </div>
</form>
<div class='col-sm-12 border' style='min-height:260px;position:relative;' >
    <table class='table table-sm'>
        <thead>
            <tr>
                <th class='text-center'>Item Name</th>
                <th class='text-center'>Initial Qty</th>
                <th class='text-center'>Restock Qty </th>
                <th class='text-center'>Sold Qty</th>
                <th class='text-center'>
                <?php if (($OffSites[0]['EndDate'] != null))
                      {
                        echo "Returned Qty";
                      }
                      else
                      {
                          echo "Remaining Qty";
                      }  ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($OffSiteItems as $key => $OffSiteItem)
            {
                
                if ($OffSiteItem['OffSiteID'] == $OffSites[0]['OffSiteID'])
                {
                    
                echo "<tr>
                        <td class='text-center'><input type='text' class='form-control' style='border:none;' value='$OffSiteItem[ItemName]'/></td>
                        <td class='text-center'>$OffSiteItem[InitialQty]</td>
                        <td class='text-center'>$OffSiteItem[RestockQty]</td>
                        <td class='text-center'>$OffSiteItem[SoldQty] </td>
                        <td class='text-center'>$OffSiteItem[RemainingQty]</td>";
                echo "</tr>";
                    if (($OffSites[0]['EndDate'] == null) && ($key == (sizeof($OffSiteItems)-1)))
                    {
                        echo "<tr class='text-center'> 
                                <td></td>
                                <td></td>
                                <td><a class='btn btn-outline-primary' href='index.php?edit_offsite_items&Restock&inventory_id=$OffSiteItem[OffSiteID]'>Adjust Restock Qty</a></td>
                                <td><a class='btn btn-outline-primary' href='index.php?edit_offsite_items&inventory_id=$OffSiteItem[OffSiteID]'>Adjust Sold Qty</a></td>
                                <td><a class='btn btn-outline-primary btn_return' href='index.php?edit_offsite_items&Return_Items&inventory_id=$OffSiteItem[OffSiteID]'>Return Remaining</a></td>
                            </tr>";
                    }
                }
            }
        ?>
        </tbody>
    </table>
    <div style='text-align:center;'>
    <?php   if (($OffSites[0]['EndDate'] != null)) 
            {
                echo "<p class='text-muted ' style=' display:block; width:100%; position:absolute; bottom:-55px;'> This inventory was closed on ".$OffSites[0]['EndDate']." and is no longer open for editing </p>";
            }
    ?>
    </div>

</div>
<a href='javascript:window.history.back();' class='btn btn-outline-primary mt-5'>Go Back</a>