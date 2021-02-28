
<?php

    // include('../connect/connect.php');
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";
	$dbname = "humayonr_restaurant";
	$connect = new mysqli($servername, $username, $password, $dbname);
	if ($connect->connect_error) 
	{
		die("Connection failed: " . $connect->connect_error);
	} 
	mysqli_set_charset($connect,'utf8'); 

    $query="DELETE FROM menu WHERE Ser > 0" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 
    $query="DELETE FROM submenu WHERE Ser > 0" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect);
    $query="DELETE FROM item_details WHERE Ser > 0" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect);

    $query="ALTER TABLE menu AUTO_INCREMENT = 1" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 

    $query="ALTER TABLE submenu AUTO_INCREMENT = 1" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 

    $query="ALTER TABLE item_details AUTO_INCREMENT = 1" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 



// Ser	OrgId	TypeId	MenuDesc  

// Ser	TypeId	ServiceTypeId	ServiceTypeName	GenericId	GenericName -->

// Ser ServiceTypeId GenericTypeId ItemOriginId TitleName PrepType ItemDesc IsOfferExist AmountOff UnitCost Price IsExist

    for($i=1; $i<4; $i++){ 
        $OrgId = 1001;
        $MenuName = "Menu Name-" . $i;;
        $MenuDesc = "This is well prepared menu-" . $i;
        $query="INSERT INTO menu (MenuId,MenuName,MenuDesc,OrgId) VALUES ($i,'$MenuName','$MenuDesc',$OrgId)" ; 
        $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 

        for($k=1; $k<5; $k++){ 
            $ServiceTypeId = (int) $i . $k;
            $GenericId = (int) $i . $k;
            $ServiceTypeName = "Service Type Name-" . $k;
            $GenericName = "Generic Name-" . $k;
            $query="INSERT INTO submenu (MenuId,ServiceTypeId,ServiceTypeName,GenericId,GenericName,OrgId) VALUES ($i,$ServiceTypeId,'$ServiceTypeName', $GenericId, '$GenericName', $OrgId)" ; 
            $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 

                for($m=1; $m<15; $m++){ 
                    $ServiceTypeId = (int) $i . $k;
                    $GenericTypeId = (int) $i . $k;
                    $ItemId = $m;
                    $ItemOriginId = rand(1,6);                    
                    $ItemName = "Item Name-". (int) $m;
                    $PrepType = rand(1,4);
                    $ItemDesc = "Item Description-" . (int) $m;
                    $IsOfferExist = 0;
                    $AmountOff = 0.0;
                    $UnitCost = rand(10.0,100.25);
                    $Price = rand(10.0,100.25);
                    $IsExist = 1;

                    $query="INSERT INTO `item_details` (ServiceTypeId,GenericTypeId,ItemId,ItemOriginId,ItemName,PrepType,ItemDesc,IsOfferExist,AmountOff,UnitCost,Price,IsExist, OrgId) VALUES($ServiceTypeId,$GenericTypeId,$ItemId,$ItemOriginId,
                    '$ItemName', $PrepType, '$ItemDesc', $IsOfferExist, $AmountOff, $UnitCost, $Price, $IsExist,$OrgId)"; 
                    $resp=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 
                    // echo $resp;
            }
        }
    }

    echo $resp;

?>