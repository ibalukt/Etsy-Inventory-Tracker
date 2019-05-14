<?php
include_once("../classes/Crud.php");

$crud = new crud();

$myfile = fopen("active_listings.txt", "r") or die("Unable to open file!");

$listings = fread($myfile,filesize("active_listings.txt"));

fclose($myfile);

$listings = json_decode($listings,true);

$query = "SELECT * FROM Inventory";
$results = $crud->getData($query);
$Inventory_count = sizeof($results);

$API_count = sizeof($listings);

//echo "Inventory Count: " . $Inventory_count . "<br/>";
//echo "API Count" . $API_count . "<br/>";

$new = 0;
$form = "<form method='post' style='display:none;' id ='myform' action='../index.php'>";

if($API_count > $Inventory_count)
{
    $form .= "<input type='hidden' name='new_exists' value='1' />";
    foreach ($listings as $key => $listing)
    {
        //These are the fields of any new items that would be added to the db
        $State;
        $ListingID;
        $ItemName;
        $UnitPrice;
        $EtsyQty;
        //there have been 0 matches so far.
        $match = 0;

        //If the inventory is completely empty than there is nothing to match against so just assign the values in the 
        // else statement.
        if (sizeof($results) > 0)
        {
            foreach($results as $index => $value)
            {
                //Assign the variables above their proper values
                $State = $listing['s'];
                $ListingID = $listing['l'];
                $ItemName = $listing['t'];
                $UnitPrice = $listing['p'];
                $EtsyQty = $listing['q'];

                //if the itemID from inventory and the currently evaluated listing that was pulled have a matching id,
                //that item is already in the database.
                if ($value['ItemID'] == $listing['l'])
                {
                    //Add one to the match variable
                    $match += 1;
                }
            }
        }
        else
        {
            //Assign the values
            $State = $listing['s'];
            $ListingID = $listing['l'];
            $ItemName = $listing['t'];
            $UnitPrice = $listing['p'];
            $EtsyQty = $listing['q'];
        }
        //If the match variable is still at zero, than the item is not in the database and we will add it.
        if ($match == 0)
        {
            //DEBUG: echo "<p>$listing[t] <b>is not in the database</b></p> <br/><br/>";    
            $query ="INSERT INTO Inventory (ItemID,ItemName,OnHandQty,EtsyQty,State) VALUES ('$ListingID','$ItemName','$EtsyQty','$EtsyQty','$State')";

            //DEBUG: echo "$query" ."<br/>";
            $crud->execute($query);

            //$form .= "<input type='number' name='ItemID$new' value='$ListingID' />
            //         <input type='text' name='ItemName$new' value='$ItemName'/>
            //         <input type='number' name='OnHandQty$new' value='$EtsyQty' />
            //         <input type='number' name='EtsyQty$new' value='$EtsyQty' />
            //         <input type='text' name='State$new' value='$State' /> <br/><br/>";
            $new ++;
        }
        else
        {
            // DEBUG echo "<p> $listing[t] <i color='yellow'>is already in the database</i> </p> <br/><br/>";
            $query ="UPDATE Inventory SET EtsyQty = $EtsyQty WHERE ItemID = $ItemID";
        }
    }
}

 $form .= " <input type='hidden' name='num_new' value='$new'/>
            </form>";

echo $form;

echo "<script> myform.submit(); </script>"

  //echo "<script> window.location ='inventory.php';</script>";
?>