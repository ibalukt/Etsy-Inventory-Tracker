<?php
require "classes/Oauth.php";

$oauthObject = new OAuthSimple();

$output = 'Authorizing...';

$signatures = array( 'consumer_key'     => '','shared_secret'    => '');

$signatures['oauth_token'] = $access_token;
$signatures['oauth_secret'] = $access_token_secret;

//--------------------------------------TRANSACTIONS
//set the initial count
$count = 200;
//create an empty array for all of the transactions
$transactions = array();
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
    $count = $jsonData['count'];

    //store the results (transactions ) of the response in variable t.
    $t = $jsonData['results'];

    //for every index of the t array push those indexes over to the transactions array.
    for ($j=0;$j<sizeof($t);$j++)
    {
        array_push($transactions,$t[$j]);
    }

}

$transactions = json_encode($transactions);

$myfile = fopen("files/transactions.txt", "w") or die("Unable to open file!");
$txt = $transactions;
fwrite($myfile,$txt);
fclose($myfile);

//--------------------------------------RECEIPTS-------------------------
$count = 200;
$receipts = array(); 

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
    $count = $jsonData['count'];

    $r = $jsonData['results'];

    for ($j=0;$j<sizeof($r);$j++)
    {
        array_push($receipts,$r[$j]);
    }
}

$receipts = json_encode($receipts);

$myfile = fopen("files/receipts.txt", "w") or die("Unable to open file!");
$txt = $receipts;
fwrite($myfile,$txt);
fclose($myfile);

//-----------------------------------LISTINGS

$count = 100;
$listings = array(); 

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
    $count = $jsonData['count'];

    $l = $jsonData['results'];

    for ($j=0;$j<sizeof($l);$j++)
    {
        array_push($listings,$l[$j]);
    }
}

$listings = json_encode($listings);

$myfile = fopen("files/items.txt", "w") or die("Unable to open file!");
$txt = $listings;
fwrite($myfile,$txt);
fclose($myfile);

     
?>
<HTML>
<BODY>
<?php echo $output;?>
</BODY>
</HTML>
