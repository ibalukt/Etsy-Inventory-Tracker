<header class='pb-4 pt-5 text-center'>
    <h2 class='text-secondary'>  My Inventory </h2>
</header>
<form method='post' action='index.php?main'>
    <div class="input-group mb-3">
        <input type="text" class="form-control" name='search' placeholder="search items" aria-describedby="basic-addon2" required>
        <div class="input-group-append">
            <button type='submit' class="input-group-text" id="basic-addon2"> Search Items</button>
        </div>
    </div>
</form>
<div class='col-sm-12 border' style="overflow-y:scroll; min-height:260px; max-height:260px; " >
<table class='table table-sm' >
<?php 
    $query = "SELECT * FROM Inventory WHERE State = ? ORDER BY ItemName ASC";
    $params = array('active');
    $results = $crud->prep_getData($query,"s",$params);

    $db_number = sizeof($results);

        if (isset($_POST['search']))
    {
        $search_string = $_POST['search'];
        $search_results = array();

        foreach($results AS $result)
        {
            $remove_tags = explode("//",$result['ItemName']);
            $cleaned_item_name = $remove_tags[0];
            $match_percentage = similar_text($search_string,$cleaned_item_name, $perc);
            //echo $result['ItemName'] . "match percent:" .$perc . "<br/>" ;
            if ($perc > 60)
            {
                array_push($search_results,$result);
            }
        }
        
        $results=$search_results;
    }

    if ($db_number == 0)
    {
        echo "<div class='col-sm-12 text-center mt-3'>
                <p class='ml-auto mr-auto'>It looks like you don't have any records in your inventory. </p>
                <a class='btn btn-primary ml-auto mr-auto;' href='actions/pull_active_listings.php'> Pull Etsy Listings</a>
            </div>";
    }
    else
    {
        if ($results == false)
        {
            
            echo "<div class='col-sm-12 text-center mt-3'>
                    <p class='ml-auto mr-auto'>There were no items matching your search. <a href='index.php'> click here to show all </a></p>
                </div>";
        }
        else
        {
            echo "<thead >
                        <tr class='text-center'>                                     
                            <th>Item Name</th>
                            <th>On Hand </th>
                            <th>OffSite </th>
                            <th> <a href='#'  data-toggle='tooltip' title='click to sync w/ Etsy!' style='color:rgb(245,100,0);' >Etsy </a></th>
                            <th>Total Qty</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody >";
            // PHP FOREACH LOOP START <th>Packaging Cost</th> <th>Unit Cost </th>

                //For every item of result
                foreach ($results as $key => $result)
                {
                    $ItemID = $result['ItemID'];
                    echo "<tr class='text-center'>";   
                    //create a column for every column in the table and put the result inside of it
                    //echo "<td> <input type='text' class='form-control' value='".$result['ItemID']."' /></td>";
                    echo "<td> <input type='text'class='form-control' style='border:none;' value='".$result['ItemName']."' /></td>";
                    //echo "<td>".$result['UnitPrice']."</td>";
                    //echo "<td>".$result['UnitCost']."</td>";
                    //echo "<td>".$result['PackagingCost']."</td>";
                    echo "<td>".$result['OnHandQty']. "</td>";
                    echo "<td>".$result['OffSiteQty']. "</td>";
                    echo "<td>".$result['EtsyQty']."</td>";
                    if ($result['EtsyQty'] != $result['TotalQty'])
                    {
                        echo "<td class='text-danger'>".$result['TotalQty']." </td>";
                    }
                    else
                    {
                        echo "<td>".$result['TotalQty']."</td>";
                    }
                    //echo "<td>".$result['CostModDate']."</td>";
                    //For Every Item in the results create and edit and delete button.
                    echo "<td> <a class='p-2' href='index.php?edit_item&ItemID=".$ItemID."'><i class='fas fa-pencil-alt' style='font-size:1.5em;'></i></a>";
                    echo "<a class='btn_delete p-2' href='delete.php?ItemID=$ItemID' ><i class='fas fa-minus-circle text-danger' style='font-size:1.5em;'></i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
        }
    }
?>
</tbody>
</table>
</div>
<?php include_once('current_offsite_inventories.php');