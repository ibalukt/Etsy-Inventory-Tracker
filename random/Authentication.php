<?php

include_once("classes/Crud.php");
require "classes/Oauth.php";

$crud = new crud();
$oauthObject = new OAuthSimple();

$output = 'Authorizing...';

$signatures = array( 'consumer_key'     => '', //TODO: These need to go in the inni file
                     'shared_secret'    => ''); //TODO: These need to go in the inni file


if (!isset($_GET['oauth_verifier'])) {
///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
// Step 1: Get a Request Token
//
// Get a temporary request token to facilitate the user authorization 
// in step 2. We make a request to the OAuthGetRequestToken endpoint,
// submitting the scope of the access we need (in this case, all the 
// user's calendars) and also tell Google where to go once the token
// authorization on their side is finished.
//
$result = $oauthObject->sign(array(
    'path'      =>'https://openapi.etsy.com/v2/oauth/request_token',
    'parameters'=> array(
        'scope'         => '',
        'oauth_callback'=> 'http://localhost:8080/Integrated_Project/getToken.php'),
    'signatures'=> $signatures));
// The above object generates a simple URL that includes a signature, the 
// needed parameters, and the web page that will handle our request.  I now localhost:8080/Integrated_Project/getToken.php
// "load" that web page into a string variable.
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
$r = curl_exec($ch);
curl_close($ch);
// We parse the string for the request token and the matching token
// secret. Again, I'm not handling any errors and just plough ahead 
// assuming everything is hunky dory.
parse_str($r, $returned_items);
$request_token = $returned_items['oauth_token'];
$request_token_secret = $returned_items['oauth_token_secret'];
// We will need the request token and secret after the authorization.
// Google will forward the request token, but not the secret.
// Set a cookie, so the secret will be available once we return to this page.
setcookie("oauth_token_secret", $request_token_secret, time()+3600);
//

//////////////////////////////////////////////////////////////////////
//echo "<a href='".$link."' > go here </a>";


///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
// Step 2: Authorize the Request Token
//
// Generate a URL for an authorization request, then redirect to that URL
// so the user can authorize our access request.  The user could also deny
// the request, so don't forget to add something to handle that case.
$result = $oauthObject->sign(array(
    'path'      =>'https://www.etsy.com/oauth/signin?',
    'parameters'=> array(
        'oauth_token' => $request_token),
    'signatures'=> $signatures));
// See you in a sec in step 3.
header("Location:$result[signed_url]");
exit;
//////////////////////////////////////////////////////////////////////
}
else {
///////////////////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
// Step 3: Exchange the Authorized Request Token for a Long-Term
//         Access Token.
//
// We just returned from the user authorization process on Google's site.
// The token returned is the same request token we got in step 1.  To 
// sign this exchange request, we also need the request token secret that
// we baked into a cookie earlier. 
//
// Fetch the cookie and amend our signature array with the request
// token and secret.
$signatures['oauth_secret'] = $_COOKIE['oauth_token_secret'];
$signatures['oauth_token'] = $_GET['oauth_token'];

// Build the request-URL...
$result = $oauthObject->sign(array(
    'path'      => 'https://openapi.etsy.com/v2/oauth/access_token',
    'parameters'=> array(
        'oauth_verifier' => $_GET['oauth_verifier'],
        'oauth_token'    => $_GET['oauth_token']),
    'signatures'=> $signatures));
// ... and grab the resulting string again. 
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $result['signed_url']);
$r = curl_exec($ch);
// Voila, we've got a long-term access token.
parse_str($r, $returned_items);        
$access_token = $returned_items['oauth_token'];
$access_token_secret = $returned_items['oauth_token_secret'];

// We can use this long-term access token to request Google API data,
// for example, a list of calendars. 
// All Google API data requests will have to be signed just as before,
// but we can now bypass the authorization process and use the long-term
// access token you hopefully stored somewhere permanently.*/
$signatures['oauth_token'] = $access_token; //TODO: these need to go in the inni
$signatures['oauth_secret'] = $access_token_secret; //TODO: these need to go in the inni;

echo "Access Token: $access_token <br/> Token Secret: $access_token_secret ";

?>