<header class='pb-4 pt-5 text-center'>
    <h2 class='text-secondary'>  OffSite Inventories </h2>
</header>
<form method='post' action='inventory.php'>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name='search' placeholder="search items" aria-describedby="basic-addon2" disabled>
        <div class="input-group-append">
            <button type='submit' class="input-group-text" id="basic-addon2" disabled> Search Items</button>
        </div>
    </div>
</form>
<div class='col-sm-12 border' style="overflow-y:scroll; min-height:260px; max-height:260px; " >
<table class='table table-sm'>

    <?php
        
        $query = "SELECT * FROM OffSite OS JOIN Reason R ON OS.ReasonID = R.ReasonID";
        $OffSites = $crud->getData($query);
        
        if ($OffSites != null)
        {
            echo "<thead>
                    <tr class='text-center'>
                        <th>OffSite ID</th>
                        <th> Why </th>
                        <th> Where </th>
                        <th> StartDate </th>
                        <th> EndDate </th>
                        <th> Status </th>
                    </tr>
                </thead>
                <tbody>";
            foreach ($OffSites as $key => $OffSite)
            {
                echo "
                            <tr class='text-center'>
                                <td>$OffSite[OffSiteID]</td>
                                <td><input type='text' class='form-control' value='$OffSite[Explanation]' style='border:none'/></td>
                                <td><a style='text-decoration:none;' href='index.php?offsite_items&inventory_id=$OffSite[OffSiteID]'>$OffSite[GoingWhere]</a></td>
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
        }
        else
        {
            echo " <div class='col-sm-12 text-center'>
                        <p> There is currently no offsite inventories to view </p>
                    </div>";
        }
    ?>
</tbody>
</table>
</div>
<a href='javascript:window.history.back();' class='btn btn-outline-primary mt-5'>Go Back</a>