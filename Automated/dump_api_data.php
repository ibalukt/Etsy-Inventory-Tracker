<?php

include_once("../classes/Crud.php");

$crud = new crud();

//listings----------------------------------------------------------------
$myfile = fopen("files/listings.txt", "r") or die("Unable to open file!");

$listings = fread($myfile,filesize("files/listings.txt"));

fclose($myfile);

//receipts-----------------------------------------------------------------
$myfile = fopen("files/receipts.txt", "r") or die("Unable to open file!");

$receipts = fread($myfile, filesize("files/receipts.txt"));

fclose($myfile);

//transactions------------------------------------------------------------
$myfile = fopen("files/transactions.txt","r") or die("Unable to open file!");

$transactions = fread($myfile, filesize("files/transactions.txt"));

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

//Decode the receipts file into an array
$receipts = json_decode($receipts,true);

echo count($receipts);
//Decode the transactions file into an array
$transactions = json_decode($transactions,true);

echo count($transactions);
//loop through the receipts
foreach ($receipts AS $key => $receipt)
{
    $epoch = $receipt['dt'];
    //create a new date time from the epoch seconds
    $TActionDate = new DateTime("@$epoch");
    //Format the new date
    $TActionDate = $TActionDate->format('Y-m-d H:i:s');

    //create a transaction group so you can group all of the transaction json objects together for storage
    $transaction_grp = "";
    //index to determin if there is a comma or not between the transaction json objects
    $size = 0;
    //loop through the transactions
    foreach ($transactions AS $index => $transaction)
    {
        //if the receipt id and the transaction item receipt id are the same then
        if ($receipt['r'] == $transaction['r'])
        {
            //if the size variable is greater than one than this is the second transaction for the receipt
            if ($size > 0)
            {
                //add a comma between the json objects
                $transaction_grp .= ",";
            }
            //encode the array into a json object
            $transaction = json_encode($transaction);
            //add the json object to the transaction group
            $transaction_grp .= $transaction;
            //increment the size by one
            $size += 1;
        }
    }

    //encode the receipt array into a json object
    $receipt = json_encode($receipt);

    //DEBUG: echo "RECEIPT #$key  <br/><br/>";

    //INSERT both the receipt and the transaction items associated with it into the db
    $query = "INSERT INTO SalesDump (DumpDate,Receipts,Transactions) VALUES('$TActionDate','$receipt','$transaction_grp')";
    $crud->execute($query);

}

echo "<script> window.location ='http://localhost:8080/Integrated_Project/set_up_db.php' </script>";
?>


