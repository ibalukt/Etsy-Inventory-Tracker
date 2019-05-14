<?php 
include_once("../classes/Crud.php");

$crud = new crud();

//listings----------------------------------------------------------------
$myfile = fopen("files/listings.txt", "r") or die("Unable to open file!");

$listings = fread($myfile,filesize("files/listings.txt"));

fclose($myfile);

$listings = json_decode($listings,true);

echo count($listings);
foreach ($listings AS $listing)
{
    $listing = json_encode($listing);
    //echo "$listing <br/>";
    $query = "INSERT INTO ListingsDump (Listings) VALUES ('$listing')";

    $crud->execute($query);
}

?>