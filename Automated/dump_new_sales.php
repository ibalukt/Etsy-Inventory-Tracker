<?php
//DEPENDENCIES
include_once('../classes/Crud.php');
require "../classes/Oauth.php";
$crud = new crud();

$ini = parse_ini_file("../etsytracker.ini");

$oauthObject = new OAuthSimple();
$signatures = array( 'consumer_key'     => $ini['API_KEY'],'shared_secret'    => $ini['SHARED_SECRET']);
$signatures['oauth_token'] = $ini['OAUTH_TOKEN'];
$signatures['oauth_secret'] = $ini['OAUTH_SECRET'];

//MAKE AN API REQUEST TO SEE IF THERE IS ANY NEW SALES
$oauthObject->reset();
$result = $oauthObject->sign(array(
    'action' => 'GET',
    'path' => 'https://openapi.etsy.com/v2/shops/14069651/receipts',
    'parameters' => array( 
            'limit' => '1',
            'offset' => '0'
    ),
    'signatures' => $signatures));

$url = $result['signed_url'];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response_body = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$jsonData = rtrim($response_body, "\0");
$jsonData = json_decode($jsonData,true);
$API_count = $jsonData['count'];


//get the count of sales currently in the dump
$query = "SELECT COUNT(*) FROM SalesDump";
$SalesDump_count = $crud->getData($query);
$SalesDump_count = $SalesDump_count[0]['COUNT(*)'];

$new_count = $API_count - $SalesDump_count;

echo $new_count;



//receipts-----------------------------------------------------------------
$myfile = fopen("files/receipts.txt", "r") or die("Unable to open file!");

$receipts = fread($myfile, filesize("files/receipts.txt"));

fclose($myfile);

//transactions------------------------------------------------------------
$myfile = fopen("files/transactions.txt","r") or die("Unable to open file!");

$transactions = fread($myfile, filesize("files/transactions.txt"));

fclose($myfile);


$receipts = json_decode($receipts,true);
//$receipts = array_reverse($receipts);
$transactions = json_decode($transactions,true);
//$transactions = array_reverse($transactions);


foreach ($receipts AS $key => $receipt)
{
    if ($key >= (sizeof($receipts)-$new_count))
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

}

?>