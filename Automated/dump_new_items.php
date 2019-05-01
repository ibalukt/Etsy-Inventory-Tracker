<?php
include_once('../classes/Crud.php');
$crud = new crud();

$myfile = fopen("files/listings.txt", "r") or die("Unable to open file!");

$listings = fread($myfile,filesize("files/listings.txt"));

fclose($myfile);

$listings = json_decode($listings,true);

$query = "SELECT * FROM ListingsDump";
$results = $crud->getData($query);

$ListingDump_count = sizeof($results);

$API_count = sizeof($listings);

if($API_count > $ListingDump_count)
{
    foreach ($listings as $key => $listing)
    {
        //These are the fields of any new items that would be added to the db
        $State = $listing['s'];
        $ListingID = $listing['l'];
        $ItemName = $listing['t'];
        $UnitPrice = $listing['p'];
        $QtyAvailable = $listing['q'];
        //there have been 0 matches so far.
        $match = 0;
        foreach($results as $index => $value)
        {

            $value = json_decode($value['Listings'],true);

            if ($listing['l'] == $value['l'])
            {
                //Add one to the match variable
                $match += 1;
            }
        }
        //If the match variable is still at zero, than the item is not in the database and we will add it.
        if ($match == 0)
        {
            //echo $ListingID . "<br/><br/>";
            $myObj = (object)[];
            $myObj->l = $ListingID;
            $myObj->t = $ItemName;
            $myObj->p = $UnitPrice;
            $myObj->q = $QtyAvailable;
            $myObj->s = $State;

            $new_listing = json_encode($myObj);

            echo $new_listing . "<br/><br/><br/>";

            $query = "INSERT INTO ListingsDump (Listings) VALUES ('$new_listing')";

            $crud->execute($query);
        }
    }   
}

?>