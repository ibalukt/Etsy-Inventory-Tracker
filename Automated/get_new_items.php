<?php
include_once("../classes/Crud.php");

$crud = new crud();

$myfile = fopen("files/listings.txt", "r") or die("Unable to open file!");

$listings = fread($myfile,filesize("files/listings.txt"));

fclose($myfile);

$listings = json_decode($listings,true);

$query = "SELECT * FROM Inventory";
$results = $crud->getData($query);
$Inventory_count = sizeof($results);

//echo $Inventory_count;
$API_count = sizeof($listings)+1;
//echo $API_count;



if($API_count > $Inventory_count)
{
    foreach ($listings as $key => $listing)
    {
        //These are the fields of any new items that would be added to the db
        $State;
        $ListingID;
        $ItemName;
        $UnitPrice;
        $QtyAvailable;
        //there have been 0 matches so far.
        $match = 0;
        foreach($results as $index => $value)
        {
            //Assign the variables above their proper values
            $State = $listing['s'];
            $ListingID = $listing['l'];
            $ItemName = $listing['t'];
            $UnitPrice = $listing['p'];
            $QtyAvailable = $listing['q'];
            //if the itemID from inventory and the currently evaluated listing that was pulled have a matching id,
            //that item is already in the database.
            if ($value['ItemID'] == $listing['l'])
            {
                //Add one to the match variable
                $match += 1;
            }
        }
        //If the match variable is still at zero, than the item is not in the database and we will add it.
        if ($match == 0)
        {
            echo $ListingID;
            
            $query ="INSERT INTO Inventory (ItemID,ItemName,UnitPrice,QtyAvailable,State) values ('$ListingID','$ItemName','$UnitPrice','$QtyAvailable','$State')";

            $crud->execute($query);
        }
    }
    
}
?>