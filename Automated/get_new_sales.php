<?php
include_once("../classes/Crud.php");
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

//Get the number of items in the SalesDump Table
$query = "SELECT COUNT(*) FROM SalesDump";
$SalesDump_count = $crud->getData($query);
$SalesDump_count = $SalesDump_count[0]['COUNT(*)'];
//Get the number of items in the TAction table
$query = "SELECT COUNT(*) FROM TAction";
$TAction_count = $crud->getData($query);
$TAction_count = $TAction_count[0]['COUNT(*)'];


//$TAction_count =1234;


//This is the number of new items that are not yet in the db
$new_count = $API_count-($TAction_count+($SalesDump_count-$TAction_count));

echo "API_count: $API_count <br/>";
echo "TAcion_count: $TAction_count <br/>";
echo "new_count: $new_count";


//open the file reader
$myfile = fopen("files/transactions.txt", "r") or die("Unable to open file!");
//store the string from the file in the variable $transactions
$transactions = fread($myfile,filesize("files/transactions.txt"));
//decode the json file into an array
$transactions = json_decode($transactions,true);
//close the file reader
fclose($myfile);
//open the file reader again
$myfile = fopen("files/receipts.txt", "r") or die("Unable to open file!");
//store the string from the file in the variable receipts
$receipts = fread($myfile,filesize("files/receipts.txt"));
//decode the json file into an array
$receipts = json_decode($receipts,true);
//close the file reader
fclose($myfile);

foreach ($receipts as $key => $receipt)
{

    if ($key >= (sizeof($receipts)-$new_count))
    {
        //ReceiptID
        $ReceiptID_1 = $receipt['r'];
        //Date
        //get the epoch seconds
        $epoch = $receipt['dt'];
        //create a new date time from the epoch seconds
        $TActionDate = new DateTime("@$epoch");
        //Format the new date
        $TActionDate = $TActionDate->format('Y-m-d H:i:s');
        //SalesTax
        $SalesTax = $receipt['t'];
        //Shipping
        $Shipping = $receipt['s'];
        //Total
        $GrandTotal = $receipt['gt'];
        //FirstName
        $PartyName = $receipt['n'];
        
        echo "<h4>----------RECEIPT#$key----------</h4> <br/>
            Date : $TActionDate <br/>
            Purchased By: $PartyName <br/>";

        $query = "INSERT INTO TAction (TActionDate,PartyID,PartyName,SalesTax,ShippingCharge,TotalCharge,Notes) VALUES ('$TActionDate','1','$PartyName','$SalesTax','$Shipping','$GrandTotal','none')";

        $crud->execute($query);

        $query = "SELECT LAST_INSERT_ID() FROM TAction";
        $get_id = $crud->getData($query);
        $TActionID = $get_id[0]['LAST_INSERT_ID()'];

        foreach ($transactions as $index => $transaction)
        {
            $ReceiptID_2 = $transaction['r'];

            if ($ReceiptID_1 == $ReceiptID_2)
            {
                $ItemID = $transaction['l'];
                $UnitPrice = $transaction['p'];
                $Qty = $transaction['q'];
                $Items_NewQty = $transaction['qa']
                echo "<b>-----Item-----</b><br/>
                    ItemID: $ItemID <br/>
                    Price: $UnitPrice <br/>";

                //This will insert the new transactionitem
                $query = "INSERT INTO TActionItem (TActionID,ItemID,UnitPrice,Qty) VALUES ('$TActionID','$ItemID','$UnitPrice','$Qty')";
                $crud->execute($query);

                //This will update the quantity of the item that was purchased accordingly
                $query = "UPDATE Inventory SET(QtyAvailable = '$Items_NewQty') WHERE ItemID = $ItemID"
                $crud->execute($query);
            }
        }

        echo "<b>-----Total-----</b><br/>
            SalesTax: $SalesTax <br/>
            Shipping: $Shipping <br/>
            Total: $GrandTotal <br/><br/><br/>";
    }
    
}
?>