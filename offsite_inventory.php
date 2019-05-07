<?php
include_once('classes/Crud.php');
$crud = new crud();

$query = "SELECT * FROM OffSite OS JOIN Reason R ON OS.ReasonID = R.ReasonID";
$OffSites = $crud->getData($query);

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
        <h3 class='text-secondary text-center'> Off-Site Inventories</h3>
    </header>
    <div class='container'>
    <body>
        <table class='table table-sm'>
            <thead>
                <tr>
                    <th>OffSite ID</th>
                    <th> Why </th>
                    <th> Where </th>
                    <th> StartDate </th>
                    <th> EndDate </th>
                    <th> Status </th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($OffSites as $key => $OffSite)
                    {
                        echo "
                                    <tr>
                                        <td>$OffSite[OffSiteID]</td>
                                        <td><a style='text-decoration:none;' href='offsite_items.php?OffSiteID=$OffSite[OffSiteID]'>$OffSite[Explanation]</a></td>
                                        <td><a style='text-decoration:none;' href='offsite_items.php?OffSiteID=$OffSite[OffSiteID]'>$OffSite[GoingWhere]</a></td>
                                        <td>$OffSite[StartDate]</td>";
                                if ($OffSite['EndDate'] != null)
                                {
                                    echo "<td>$OffSite[EndDate]</td>";
                                    echo "<td class='text-danger'> Closed </td>";
                                }
                                else
                                {
                                    echo "<td> N/A </td>";
                                    echo "<td class='text-success'> Open </td>";
                                }
                                echo " </tr>";
                             
                                
                    }
                ?>
            </tbody>
        </table>
    </div>
    </body>
</html>