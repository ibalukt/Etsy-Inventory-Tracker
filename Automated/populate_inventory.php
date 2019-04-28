<?php
include_once("../classes/Crud.php");

$crud = new crud();

$myfile = fopen("files/listings.txt", "r") or die("Unable to open file!");

$listings = fread($myfile,filesize("files/listings.txt"));

fclose($myfile);

//echo $jsonData;

$listings = json_decode($listings,true);

echo gettype($listings);

foreach ($listings as $key => $listing)
{
    //ItemName
    $State = $listing['s'];
    $ListingID = $listing['l'];
    $ItemName = $listing['t'];
    $UnitPrice = $listing['p'];
    $QtyAvailable = $listing['q'];
    /*$title = explode("//",$value['title']);
    if (strpos($title[0], '"Hand Lettering Foiled Print"') !== false)
    {
        $title = $title[0] .  $title[1];
    }
    {
        $title = $title[0] .  $title[1];
    }*/
    echo "---------------Listing#$key--------------- <br/>
          Edit: $State <br/>
          ItemName:$ItemName <br/>
          UnitPrice:$UnitPrice <br/>
          QtyAvailable:$QtyAvailable <br/><br/><br/>";

    $query ="INSERT INTO Inventory (ItemID,ItemName,UnitPrice,QtyAvailable,State) values ('$ListingID','$ItemName','$UnitPrice','$QtyAvailable','$State')";

    $crud->execute($query);
}
?>