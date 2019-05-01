<?php

require "../classes/Oauth.php";
$ini = parse_ini_file("../etsytracker.ini");

$oauthObject = new OAuthSimple();

$output = 'Authorizing...';

$signatures = array( 'consumer_key'     => $ini['API_KEY'],'shared_secret'    => $ini['SHARED_SECRET']);

$signatures['oauth_token'] = $ini['OAUTH_TOKEN'];
$signatures['oauth_secret'] = $ini['OAUTH_SECRET'];

//--------------------------------------Sections
//reset the oauthObject every loop
$oauthObject->reset();
//set the request 
$result = $oauthObject->sign(array(
    'action' => 'GET',
    'path'      =>'https://openapi.etsy.com/v2/shops/14069651/sections',
    'parameters'=> array(
        'limit' => '100',
        'offset' => '0', // set offset to i so it makes it through all of the available transactions
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

//store the results (transactions ) of the response in variable t.
$s = $jsonData['results'];

$shop_section_ids = array();

//for every index of the t array push those indexes over to the transactions array.
for ($i=0;$i<sizeof($s);$i++)
{
    array_push($shop_section_ids, $s[$i]['shop_section_id']);
}

//print_r($shop_section_ids);

$listings = "[ ";
foreach ($shop_section_ids as $i => $shop_section_id)
{
    //reset the oauthObject every loop
    $oauthObject->reset();
    //set the request 
    $result = $oauthObject->sign(array(
        'action' => 'GET',
        'path'      =>'https://openapi.etsy.com/v2/shops/14069651/sections/'.$shop_section_id.'/listings',
        'parameters'=> array(
            'limit' => '100',
            'offset' => '0', // set offset to i so it makes it through all of the available transactions
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

        if (($i == (sizeof($shop_section_ids)-1)) && ($j == (sizeof($l)-1)))
        {
            $listings .= $listing;
        }
        else
        {
            $listings .= $listing . ",";
        }
    }
}

$listings .= " ]";

//$listings = json_decode($listings,true);

//echo $listings;

//echo gettype($listings);

$myfile = fopen("files/listings.txt", "w") or die("Unable to open file!");
$txt = $listings;
fwrite($myfile,$txt);
fclose($myfile);




?>