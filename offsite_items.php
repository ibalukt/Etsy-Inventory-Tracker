<?php
include_once('classes/DbConfig.php');
include_once('classes/Crud.php');
$crud = new crud();
$db = new DbConfig();
$db = $db->__construct();

if (isset($_GET['OffSiteID']))
{
    $OffSiteID = $crud->escape_string($_GET['OffSiteID']);
    $where = " WHERE OffSiteID = $OffSiteID ";
}
else
{
    $where = " ";
}

//GET IActions
$query = "SELECT * FROM OffSite OS JOIN REASON R ON OS.ReasonID = R.ReasonID";
$query .= $where;
$orderby = " ORDER BY OffSiteID DESC"; 
$query .= $orderby;
//echo $query;
$OffSites = $crud->getData($query);

//print_r($OffSites);


$query = "SELECT * FROM OffSiteItem OSI JOIN Inventory I ON OSI.ItemID = I.ItemID";
$OffSiteItems = $crud->getData($query);

?>
<html>
    <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    </head>
    <body>
    <?php include_once('nav.php'); ?>
    <header class='p-4'> 
        <h3 class='text-secondary text-center'> <?php echo $OffSites[0]['GoingWhere']; ?></h3>
    </header>
        <div class='container'>
            <div class='col-sm-12 border' >
                <table class='table table-sm'>
                    <thead>
                        <tr>
                            <th class='text-center'>Item Name</th>
                            <th class='text-center'>Initial Qty</th>
                            <th class='text-center'>Restock Qty </th>
                            <th class='text-center'>Sold Qty</th>
                            <th class='text-center'>Remaining Qty</th>
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
                            }
                        }
                        if ($OffSites[0]['EndDate'] == null)
                        {
                            echo "<tr class='text-center'> 
                                    <td></td>
                                    <td></td>
                                    <td><a class='btn btn-outline-primary' href='edit_offsite_items.php?Restock&OffSiteID=$OffSiteItem[OffSiteID]'>Adjust Restock Qty</a></td>
                                    <td><a class='btn btn-outline-primary'  href='edit_offsite_items.php?OffSiteID=$OffSiteItem[OffSiteID]'>Adjust Sold Qty</a></td>
                                    <td><a class='btn btn-outline-primary btn_return'  href='edit_offsite_items.php?Return_Items&OffSiteID=$OffSiteItem[OffSiteID]'>Return Remaining</a></td>
                                </tr>";
                        }
                    ?>
                    </tbody>
                </table>
            </div>
            <br/>
            <p class='text-center'>
            <?php 
                if ($OffSites[0]['EndDate'] != null)
                {
                    echo "This inventory was closed on ". $OffSites[0]['EndDate'] . " and is no longer open for editing."; 
                }
            ?> 
            </p>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
        <script>
            $('.btn_return').click(function(event){
                event.preventDefault();
                 var destination = ($(this).attr("href"));
                 //alert(destination);
                bootbox.confirm({
                    message: "This off-site inventory will be closed if you return all remaining items to your\
                              main inventory. Are you sure you want to procede?",
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