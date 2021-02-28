
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

    $query="DELETE FROM item_details WHERE Ser > 0" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 
    $query="DELETE FROM allergicinfo WHERE Ser > 0" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect);
    // $query="DELETE FROM item_details WHERE Ser > 0" ; 
    // $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect);

    $query="ALTER TABLE item_details AUTO_INCREMENT = 1" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 

    $query="ALTER TABLE allergicinfo AUTO_INCREMENT = 1" ; 
    $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 

    // $query="ALTER TABLE item_details AUTO_INCREMENT = 1" ; 
    // $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 
    // Ser,MenuId,ItemId,ItemName,ItemDesc,ItemOriginId,UnitCost,Price,CategoryId,IsOfferExist,AmtOffer,IsVegetarian,IsAllergic,IsFeatured,IsDiscontinued,ImgId,OrgId
    function IsFeaturedCount($val,$MenuId) 
    {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "humayonr_restaurant";
        $connect = new mysqli($servername, $username, $password, $dbname);


        $sql="SELECT Ser FROM item_details WHERE IsFeatured = 1 AND MenuId = $MenuId";

        if ($result=mysqli_query($connect,$sql))
        {
            $rowcount = mysqli_num_rows($result);
            $count= $rowcount > 1 ? 0 : 1;
            // echo $count;
            return $count;
        }
    }

    function create_desc($MenuId, $ItemId, $CategoryId){
        if ($MenuId == 1) {
            return "This item is for Breakfast with Item ID-" . $ItemId;
        }
        else if ($MenuId == 2) 
        {
            if ($CategoryId ==1) {
                return "This item is for Lunch with Item ID-" . $ItemId . " and suitable as Starter";
            }
            else if ($CategoryId ==2) {
                return "This item is for Lunch with Item ID-" . $ItemId . " and suitable as Main";
            }
            else if ($CategoryId ==3){
                return "This item is for Lunch with Item ID-" . $ItemId . " and suitable as Dessert";
            }
        }
        else if ($MenuId == 3) 
        {
            if ($CategoryId ==1) {
                return "This item is for Dinner with Item ID-" . $ItemId . " and suitable as Starter";
            }
            else if ($CategoryId ==2) {
                return "This item is for Dinner with Item ID-" . $ItemId . " and suitable as Main";
            }
            else if ($CategoryId ==3) {
                return "This item is for Dinner with Item ID-" . $ItemId . " and suitable as Dessert";
            }
        }
        else if ($MenuId == 4) {
            if ($CategoryId ==1){
                return "This item category is Drinks with Item ID-" . $ItemId . " and mark as Beer";
            }
            else if ($CategoryId ==2){
                return "This item category is Drinks with Item ID-" . $ItemId . " and mark as Spirit";
            }
            else if ($CategoryId ==3){
                return "This item category is Drinks with Item ID-" . $ItemId . " and mark as Wine";
            }
            else if ($CategoryId ==4){
                return "This item category is Drinks with Item ID-" . $ItemId . " and mark as Non-Alcoholic";
            }
        }
        else if ($MenuId == 5) {
            if ($CategoryId ==1){
                return "This item category is Fastfood with Item ID-" . $ItemId;
            }
            
            else if ($CategoryId ==2){
                return "This item category is Fastfood with Item ID-" . $ItemId;
            }
            
            else if ($CategoryId ==3){
                return "This item category is Fastfood with Item ID-" . $ItemId;

            }
        }
    }
    for($i=1; $i<150; $i++)
    { 
        $MenuId = rand(1,5); 
        echo "MenuId : " . $MenuId . "<br>";
        $ItemId = $i;
        $ItemOriginId= rand(1,5);
        $UnitCost= rand(10.75,50.75);
        $Price= $UnitCost + rand(5,25);
        $CategoryId= $MenuId == 4 ? rand(1,4) : rand(1,3);
        $IsOfferExist= rand(0,1);
        $AmtOffer= $IsOfferExist == 1 ? rand(5,50) : 0;
        $IsVegetarian= $MenuId == 4 ? 0 : rand(0,1);
        $ItemDesc= create_desc($MenuId, $ItemId, $CategoryId);
        $ItemName= $IsVegetarian == 1 ? "Item - " . $ItemId . "(Veg | Vegan)" : "Item - " . $ItemId;
        $IsAllergic= $MenuId == 4 ? 0 : rand(0,1);
        $IsFeaturedCheck= rand(0,1);
        $IsFeatured= $IsFeaturedCheck == 1 ? IsFeaturedCount(1, $MenuId) : 0;
        $IsDiscontinued= 0;
        $ImgId= $i;
        $OrgId = 1001;
        $query="INSERT INTO item_details (MenuId,ItemId,ItemName,ItemDesc,ItemOriginId,UnitCost,Price,CategoryId,IsOfferExist,AmtOffer,IsVegetarian,IsAllergic,IsFeatured,IsDiscontinued,ImgId,OrgId) VALUES 
        (
            $MenuId,
            $ItemId,
            '$ItemName',
            '$ItemDesc',
            $ItemOriginId,
            $UnitCost,
            $Price,
            $CategoryId,
            $IsOfferExist,
            $AmtOffer,
            $IsVegetarian,
            $IsAllergic,
            $IsFeatured,
            $IsDiscontinued,
            $ImgId,
            $OrgId
        )"; 
        $response=mysqli_query($connect,$query) ? 201 : mysqli_error($connect); 
        $ItemId = $i;
        $IsGlutonFree= $IsVegetarian == 1 && $IsAllergic == 1 ? 1 : rand(0,1);
        $IsEggFree= $IsVegetarian == 1 && $IsAllergic == 1 ? rand(0,1) : 0;
        $IsLactoseFree= $IsVegetarian == 1 && $IsAllergic == 1 ? rand(0,1) : 0;
        $IsSugarFree= $IsVegetarian == 1 && $IsAllergic == 1 ? rand(0,1) : 0;
        $IsSeafoodFree= $IsVegetarian == 1 && $IsAllergic == 1 ? 1 : rand(0,1);
        $IsNutFree= $IsVegetarian == 1 && $IsAllergic == 1 ? rand(0,1) : 0;
        $query_allergic="INSERT INTO allergicinfo (ItemId,IsGlutonFree,IsEggFree,IsLactoseFree,IsSugarFree,IsSeafoodFree,IsNutFree) VALUES 
        (
            $ItemId,
            $IsGlutonFree,
            $IsEggFree,
            $IsLactoseFree,
            $IsSugarFree,
            $IsSeafoodFree,
            $IsNutFree
        )"; 
        $response=mysqli_query($connect,$query_allergic) ? 201 : mysqli_error($connect); 

        // }
    }

    // echo $response;

?>