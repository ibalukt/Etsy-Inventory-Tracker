<?php 
include_once('session_check.php');
include_once('classes/Crud.php');
$crud = new crud();

require "classes/Oauth.php";
$ini = parse_ini_file("etsytracker.ini");


$oauthObject = new OAuthSimple();

$output = 'Authorizing...';

$signatures = array( 'consumer_key'     => $ini['API_KEY'],'shared_secret'    => $ini['SHARED_SECRET']);

$signatures['oauth_token'] = $ini['OAUTH_TOKEN'];
$signatures['oauth_secret'] = $ini['OAUTH_SECRET'];

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


    if (isset($_GET['num']))
    {
        $num = $crud->escape_string($_GET['num']);
    }
    else
    {
        $num = sizeof($l);
    }

    for ($j=0;$j<$num;$j++)
    {
        $myObj = (object)[];
        $myObj->l = $l[$j]['listing_id'];
        $myObj->t = $l[$j]['title'];
        $myObj->p = $l[$j]['price'];
        $myObj->q = $l[$j]['quantity'];
        $myObj->s = $l[$j]['state'];

        $listing = json_encode($myObj);

        if ((($count-$i) < 101) && ($j==$num-1))
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

//echo $listings;

$myfile = fopen("active_listings.txt", "w") or die("Unable to open file!");
$txt = $listings;
fwrite($myfile,$txt);
fclose($myfile);

echo "<script> window.location='get_items.php';</script>";

?>