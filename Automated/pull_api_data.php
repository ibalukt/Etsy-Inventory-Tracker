<?php
require "../classes/Oauth.php";
$ini = parse_ini_file("../etsytracker.ini");

$oauthObject = new OAuthSimple();

$output = 'Authorizing...';

$signatures = array( 'consumer_key'     => $ini['API_KEY'],'shared_secret'    => $ini['SHARED_SECRET']);

$signatures['oauth_token'] = $ini['OAUTH_TOKEN'];
$signatures['oauth_secret'] = $ini['OAUTH_SECRET'];
/*
//--------------------------------------TRANSACTIONS
//set the initial count 
$count = 200;
//create an empty array for all of the transactions
$transactions = "[";
//loop through all of the transactions available in the api 
for ($i=0;$i<$count;$i+=100)
{
    //reset the oauthObject every loop
    $oauthObject->reset();
    //set the request 
    $result = $oauthObject->sign(array(
        'action' => 'GET',
        'path'      =>'https://openapi.etsy.com/v2/shops/14069651/transactions',
        'parameters'=> array(
            'limit' => '100',
            'offset' => $i, // set offset to i so it makes it through all of the available transactions
        ),
        'signatures'=> $signatures));

    //create the url from the function above
    $url = $result['signed_url'];
    //initialize the curl 
    $ch = curl_init($url);
    //set the curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //execute the curl
    $response_body = curl_exec($ch);
    //get the status to make sure that it transfered okay
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //close the curl
    curl_close($ch);
    
    //trim the extra stuff from the response and store it in $jsonData variable
    $jsonData = rtrim($response_body, "\0");
    //decode the jsonData into an array
    $jsonData = json_decode($jsonData,true);
    //get the count from the array so that the loop knows how many iterations are needed
    if (isset($_GET['all']))
    {
        $count = $jsonData['count'];
    }

    //store the results (transactions ) of the response in variable t.
    $t = $jsonData['results'];

    //for every index of the t array push those indexes over to the transactions array.
    for ($j=0;$j<sizeof($t);$j++)
    {
        //array_push($transactions,$t[$j]);
        $myObj = (object)[];
        $myObj->r = $t[$j]['receipt_id'];
        $myObj->l = $t[$j]['listing_id'];
        $myObj->p = $t[$j]['price'];
        $myObj->q = $t[$j]['quantity'];
        $myObj->qa = $t[$j]['product_data']['offerings'][0]['quantity'];

        $transaction = json_encode($myObj);
    
        if ((($count-$i) < 101) && ($j==(sizeof($t)-1)))
        {
            $transactions .= $transaction;
        }
        else
        {
            $transactions .= $transaction .= ",";
        }
    }

}

$transactions .= "]";

$transactions = json_decode($transactions,true);
$transactions = array_reverse($transactions);
$transactions = json_encode($transactions);

$myfile = fopen("files/transactions.txt", "w") or die("Unable to open file!");
$txt = $transactions;
fwrite($myfile,$txt);
fclose($myfile);

//--------------------------------------RECEIPTS------------------------- 

$count = 200;
$receipts = "["; 

for ($i=0;$i<$count;$i+=100)
{
    $oauthObject->reset();
    $result = $oauthObject->sign(array(
        'action' => 'GET',
        'path' => 'https://openapi.etsy.com/v2/shops/14069651/receipts',
        'parameters' => array( 
                'limit' => '100',
                'offset' => $i
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
    if (isset($_GET['all']))
    {
        $count = $jsonData['count'];
    }

    $r = $jsonData['results'];

    for ($j=0;$j<sizeof($r);$j++)
    {
        $myObj = (object)[];
        $myObj->r = $r[$j]['receipt_id'];
        $myObj->dt = $r[$j]['creation_tsz'];
        $myObj->n = $r[$j]['name'];
        $myObj->t = $r[$j]['total_tax_cost'];
        $myObj->s = $r[$j]['total_shipping_cost'];
        $myObj->gt = $r[$j]['adjusted_grandtotal'];
        //array_push($receipts,$r[$j]);

        $receipt = json_encode($myObj);
    
        if ((($count-$i) < 101) && ($j==(sizeof($r)-1)))
        {
            $receipts .= $receipt;
        }
        else
        {
            $receipts .= $receipt .= ",";
        }
    }
}

$receipts .= "]";

$receipts = json_decode($receipts,true);
$receipts = array_reverse($receipts);
$receipts = json_encode($receipts);

//echo $receipts;

$myfile = fopen("files/receipts.txt", "w") or die("Unable to open file!");
$txt = $receipts;
fwrite($myfile,$txt);
fclose($myfile);

//-----------------------------------LISTINGS


//EXPIRED
$count = 100;
$listings = "[";

for ($i=0;$i<$count;$i+=100)
{
    $oauthObject->reset();
    $result = $oauthObject->sign(array(
        'action' => 'GET',
        'path' => 'https://openapi.etsy.com/v2//shops/14069651/listings/expired',
        'parameters' => array( 
                'limit' => '100',
                'offset' => $i
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
    if (isset($_GET['all']))
    {
        $count = $jsonData['count'];
    }

    $l = $jsonData['results'];

    for ($j=0;$j<sizeof($l);$j++)
    {
        $myObj = (object)[];
        $myObj->l = $l[$j]['listing_id'];
        $myObj->t = $l[$j]['title'];
        $myObj->p = $l[$j]['price'];
        $myObj->q = $l[$j]['quantity'];
        $myObj->s = $l[$j]['state'];

        $listing = json_encode($myObj);
        $listings .= $listing . ",";
    }

}

//Active
$count = 100;
//$listings = array(); 

for ($i=0;$i<$count;$i+=100)
{
    $oauthObject->reset();
    $result = $oauthObject->sign(array(
        'action' => 'GET',
        'path' => 'https://openapi.etsy.com/v2/shops/14069651/listings/active',
        'parameters' => array( 
                'include_private' => 'true',
                'limit' => '100',
                'offset' => $i
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
    if (isset($_GET['all']))
    {
        $count = $jsonData['count'];
    }

    $l = $jsonData['results'];

    for ($j=0;$j<sizeof($l);$j++)
    {
        $myObj = (object)[];
        $myObj->l = $l[$j]['listing_id'];
        $myObj->t = $l[$j]['title'];
        $myObj->p = $l[$j]['price'];
        $myObj->q = $l[$j]['quantity'];
        $myObj->s = $l[$j]['state'];

        $listing = json_encode($myObj);
        $listings .= $listing . ",";
    }
}*/

//INACTIVE

$listings = "[";
$count = 100;

for ($i=0;$i<$count;$i+=100)
{
    $oauthObject->reset();
    $result = $oauthObject->sign(array(
        'action' => 'GET',
        'path' => 'https://openapi.etsy.com/v2/shops/14069651/listings/active',
        'parameters' => array( 
                'limit' => '100',
                'offset' => $i
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
    if (isset($_GET['all']))
    {
        $count = $jsonData['count'];
    }

    $l = $jsonData['results'];

    for ($j=0;$j<sizeof($l);$j++)
    {
        $myObj = (object)[];
        $myObj->l = $l[$j]['listing_id'];
        $myObj->t = $l[$j]['title'];
        $myObj->p = $l[$j]['price'];
        $myObj->q = $l[$j]['quantity'];
        $myObj->s = $l[$j]['state'];

        $listing = json_encode($myObj);

        if ((($count-$i) < 101) && ($j==(sizeof($l)-1)))
        {
            $listings .= $listing;
        }
        else
        {
            $listings .= $listing .= ",";
        }
    }
}


$listings .= "]";

echo $listings;

//DEBUG listings = json_decode($listings,true);

//DEBUG echo gettype($listings);

$myfile = fopen("files/listings.txt", "w") or die("Unable to open file!");
$txt = $listings;
fwrite($myfile,$txt);
fclose($myfile);


if (isset($_GET['all']))
{
    echo "<script> window.location='http://localhost:8080/Integrated_Project/Automated/dump_api_data.php' </script>";
}

?>
<HTML>
<BODY>
</BODY>
</HTML>
