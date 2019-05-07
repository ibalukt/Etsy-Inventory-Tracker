<?php
include_once('classes/DbConfig.php');
include_once('classes/Crud.php');
$crud = new crud();
$db = new DbConfig();
$db = $db->__construct();

if (isset($_GET['location']))
{
    $GoingWhere = $crud->escape_string($_GET['location']);
    $where = "WHERE GoingWhere = '$GoingWhere'";
}
else
{
    $where = "";
}

//GET IActions
$query = "SELECT * FROM OffSite OS JOIN REASON R ON OS.ReasonID = R.ReasonID ORDER BY OffSiteID DESC";
$query .= $where;
$OffSites = $crud->getData($query);

$query = "SELECT * FROM OffSiteItem OSI JOIN Inventory I ON OSI.ItemID = I.ItemID";
$OffSiteItems = $crud->getData($query);

?>
<html>
    <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </head>
    <body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <!-- Brand -->
            <a class="navbar-brand" href="#">Etsy Inventory Tracker</a>

            <!-- Links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item ml-auto">
                    <a class="nav-link" href="#" >Logout</a>
                </li>
            </ul>
        </nav>
    <header class='p-4'> 
        <h2 class='text-center'> My Sub-Inventories </h2>
    </header>
    <div class="container">
        <div id="accordion">
        <?php
            foreach($OffSites AS $key => $OffSite) 
            {
                echo    "<div class='card'>";
                echo        "<div class='card-header'>";
                echo            "<div class='col-sm-12'>";
                echo                "<div class='row'>";
                echo                    "<div class='col-sm-8'>";
                echo                        "<a class='card-link' data-toggle='collapse' href='#collapse$key'>";
                echo                            "$OffSite[Explanation] at $OffSite[GoingWhere] <br/> $OffSite[StartDate]";
                echo                        "</a>";
                echo                    "</div>";
                echo                    "<div class='col-sm-4 text-right'>";
                echo                        "STATUS : $OffSite[State]";
                echo                    "</div>";
                echo                "</div>";
                echo            "</div>";
                echo        "</div>";
                if (isset($_GET['Target']))
                {
                echo        "<div id='collapse$key' class='collapse show' data-parent='#accordion'>";
                }
                else
                {
                echo        "<div id='collapse$key' class='collapse' data-parent='#accordion'>";    
                }
                echo        "<div class='card-body'>";
                echo            "<table class ='table table-condensed'>
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Initial Qty</th>
                                            <th>Restock Qty</th>
                                            <th>Sold Qty</th>
                                            <th>Remaining Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                foreach ($OffSiteItems AS $key => $OffSiteItem)
                {
                    if ($OffSiteItem['OffSiteID'] == $OffSiteItem['OffSiteID'])
                    {
                        echo            "<tr>
                                            <td>$OffSiteItem[ItemName]</td>
                                            <td>$OffSiteItem[InitialQty]</td>
                                            <td>$OffSiteItem[RestockQty]</td>
                                            <td>$OffSiteItem[SoldQty]</td>
                                            <td>$OffSiteItem[RemainingQty]</td>
                                        </tr>";
                    }
                }
                echo               "</tbody> 
                                </table>
                                <div class='col-sm-12'>
                                    <div class='row'>
                                        <div class='col-sm-5'></div>
                                        <div class='col-sm-2 text-right'>
                                            <a class='btn btn-outline-primary'; href='edit_subinventory.php?Restock&OffSiteID=$OffSite[OffSiteID]'>Restock an Item</a>
                                        </div>
                                        <div class='col-sm-2 border'>
                                            <a class='ml-2 btn btn-outline-primary'; href='edit_subinventory.php?Restock&OffSiteID=$OffSite[OffSiteID]'>Adjust Sold Qty</a>
                                        </div>
                                        <div class='col-sm-3 border text-center'>
                                        <a class='btn btn-outline-primary'; href='edit_subinventory.php?OffSiteID=$OffSite[OffSiteID]'>Return OffSite Inventory</a>
                                        </div>
                                        
                                    </div>
                                </div>";
                                
                echo        "</div>";
                echo     "</div>";
            }
            ?>
            </div>
        </div>

    </body>
</html>