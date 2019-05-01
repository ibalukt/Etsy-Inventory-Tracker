<?php
include_once("../classes/Crud.php");
$crud = new crud();



//$query = "SELECT Count(*) FROM TAction";
//$TAction_count = $crud->getData($query);
//$TAction_count = $TAction_count[0]['COUNT(*)'];

/*if (isset($_POST['all']))
{
    $TAction_count=0;
}
else
{
    $query = "SELECT Count(*) FROM TAction";
    $TAction_count = $crud->getData($query);
    $TAction_count = $TAction_count[0]['COUNT(*)'];

    echo print_r($TAction_count);
}*/

//--------------------------------------------RESTORE LISTINGS FROM BACKUP-------------------------------------------
//get all of the listing json objects from the listing dump

if (isset($_POST['submit']))
{
    //DROP TABLES FIRST
    $query="DROP TABLE IF EXISTS TActionItem;";
    $crud->execute($query);
    $query="DROP TABLE IF EXISTS TAction;";
    $crud->execute($query);
    $query="DROP TABLE IF EXISTS Inventory;";
    $crud->execute($query);

    //TABLES
    $query = "CREATE TABLE IF NOT EXISTS TActionItem 
    (
    TActionID INT NOT NULL,
    ItemID BIGINT,
    Qty INT,
    UnitPrice DECIMAL(18,2) ,
    LineTotal DECIMAL(18,2) AS ((UnitPrice*Qty))
    /*CONSTRAINT PK_TActionItem_TActionIDItemID PRIMARY KEY (TActionID,ItemID)*/
    );";
    $crud->execute($query);

    $query = "CREATE TABLE IF NOT EXISTS Inventory 
    (
    ItemID BIGINT auto_increment NOT NULL,
    ItemName VARCHAR(200) NOT NULL,
    UnitPrice DECIMAL(18,2),
    UnitCost DECIMAL(18,2),
    PackagingCost Decimal(18,2) ,
    QtyAvailable Int NOT NUll,
    CostModDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    State VARCHAR(60) ,
    CONSTRAINT PK_Inventory_ItemID Primary Key (ItemID)
    );";
    $crud->execute($query);

    $query="CREATE TABLE IF NOT EXISTS TAction
    (
    TActionID INT AUTO_INCREMENT NOT NULL,
    TActionDate DATE,
    PurchasedBy VARCHAR(120),
    SalesTax DECIMAL(18,2) DEFAULT 0.00,
    ShippingCharge DECIMAL(18,2) DEFAULT 0.00,
    TotalCharge DECIMAL(18,2) DEFAULT 0.00,
    Notes TEXT,
    
    CONSTRAINT PK_TAction_TActionID Primary Key (TActionID)
    );";
    $crud->execute($query);

    //FOREIGN KEYS
    $query = "ALTER TABLE TActionItem 
    ADD CONSTRAINT FK_TActionItem_ItemID_Inventory_ItemID
    FOREIGN KEY (ItemID) REFERENCES Inventory(ItemID);";
    $crud->execute($query);

    $query = "ALTER TABLE TActionItem 
    ADD CONSTRAINT FK_TActionItem_TActionID_TAction_TActionID
    FOREIGN KEY (TActionID) REFERENCES TAction(TActionID);";
    $crud->execute($query);

    $query = "INSERT INTO Inventory (ItemID,ItemName,UnitPrice,UnitCost,PackagingCost,QtyAvailable) VALUES('0','THIS WAS REMOVED FROM ETSY API AND COULD NOT BE RECOVERED','0.00','0.00','0.00','0');";
    $crud->execute($query);
    
    $query = "SELECT * FROM ListingsDump";
    $results = $crud->getData($query);

    foreach($results as $value)
    {
        //re-assemble the json notation
        $listing = "[";
        //place the raw json object inside the brackets 
        $listing .= $value['Listings'];
        //put the closing bracket onto the end
        $listing .= "]";

        //decode the json object into an array
        $listing = json_decode($listing,true);

        //Take the necessary feilds
        $ItemID = $listing[0]['l'];
        $ItemName = $listing[0]['t'];
        $QtyAvailable = $listing[0]['q'];
        $UnitPrice = $listing[0]['p'];
        $State = $listing[0]['s'];

        //$inserted = false;
        //Do a while loop to ensure that the item is inserted into the inventory.
        //while($inserted == false);
        //{
        //Insert the items into the invetory query.
        $query = "INSERT INTO Inventory (ItemID,ItemName,UnitPrice,QtyAvailable,State) VALUES('$ItemID','$ItemName','$UnitPrice','$QtyAvailable','$State')";
        //Execute the query. If execute query returns false, then try again.
        $inserted = $crud->execute($query);
        //}
    }

//--------------------------------------------RESTORE RECEIPTS AND TRANSACTIONS FROM BACKUP-------------------------------------------
//get each receipt/transaction json objects from the sales dump

    $Starting_Point =$crud->escape_string($_POST['DumpID']);
    $query = "SELECT * FROM SalesDump WHERE DumpID > $Starting_Point"; //TODO:get rid of 1233, that is just for testing purposes.
    $results = $crud->getData($query);

    foreach($results as $key => $value)
    {

        //reassemble json notation
        $receipt = "[";
        //place the receipt json object inside of the brackets
        $receipt .= $value['Receipts'];
        //add the closing bracked on the end
        $receipt .= "]";

        //decode the json objects into an array
        $receipt = json_decode($receipt,true);

        //Store the necessary fields
        //convert epoch seconds to 
        $epoch = $receipt[0]['dt'];
        //create a new date time from the epoch seconds
        $TActionDate = new DateTime("@$epoch");
        //Format the new date
        $TActionDate = $TActionDate->format('Y-m-d H:i:s');
        $PurchasedBy = $receipt[0]['n'];
        $SalesTax = $receipt[0]['t'];
        $ShippingCharge = $receipt[0]['s'];
        $TotalCharge = $receipt[0]['gt'];
        
        //INSERT THE Fields into the TActionField

        $inserted = false;
        //while($inserted == false)
        //{
        $query = "INSERT INTO TAction (TActionDate,PurchasedBy,SalesTax,ShippingCharge,TotalCharge)
                VALUES('$TActionDate','$PurchasedBy','$SalesTax','$ShippingCharge','$TotalCharge')";

        $inserted = $crud->execute($query);
        //}

        //Get the last TActionID inserted;
        $query = "SELECT LAST_INSERT_ID() FROM TAction";
        $get_id = $crud->getData($query);
        $TActionID = $get_id[0]['LAST_INSERT_ID()'];


        //recreate the Json Objects for transaction
        $transactions ="[";
        //add the objects to the brackets
        $transactions .= $value['Transactions'];
        //add the bracket on the end
        $transactions .= "]";

        //decode the object into an array
        $transactions = json_decode($transactions,true);
        //loop through each item in the array
        foreach($transactions AS $index => $transaction)
        {
            //get the necessary fields
            $ItemID = $transaction['l'];
            $Qty = $transaction['q'];
            $UnitPrice = $transaction['p'];
            
            //start the $inserted field as false
            $inserted = false;
            //tries is equal to 0
            $tries = 0;
            while($inserted==false)
            {
                //echo "TActionID= $key,  ItemID = $ItemID, Quantity = $Qty, UnitPrice = $UnitPrice  <br/><br/><br/>";
                //insert the items into the db
                $query = "INSERT INTO TActionItem (TActionID,ItemID,Qty,UnitPrice) VALUES('$TActionID','$ItemID','$Qty','$UnitPrice')";
                echo $query . "<br/><br/>";
                $inserted = $crud->execute($query);
                //$inserted = $crud->execute($query);
                //if there is more than 2 tries 
                if ($tries > 3)
                {
                    $inserted = true;                   
                }
                elseif ($tries > 2)
                {
                    //set the item ID to 1. For whatever reason certain listing id's come back as 0 and the listings cannot be pulled from
                    //the API because they are 'sold out', 'removed', or some other weird status that can't be retrieved. Sooooo, se this to 
                    //one and move on. 
                    $ItemID = 1;
                }
                $tries ++;
            }
        }
    }
}
?>