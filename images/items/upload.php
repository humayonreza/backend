<?php
// header('Content-type: application/json');
// 	header("Access-Control-Allow-Origin: *");
// 	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
// 	include('../connect/connect.php');
// $img_folder = 'http://localhost:8080/backendRESTAURANT/images/items';
// // $newfile = 'Images/folder/one_thumb.jpg';

// for($i=1; $i<150; $i++) {
//     $imgId = rand(1,10);
//     $file = $img_folder . "1001" . $imgId . ".png";
//     $newfile = $img_folder . "1001" . $i . ".png";
//     if (!copy($file, $newfile)) {
//         echo "failed to copy";
//     }
// }

header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
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
    // $sourcefile = './drinks/' . $imgId . '.png';
    // $newfile = "1001" . $i . ".png";
    // if (!copy($sourcefile, $newfile)) { echo "failed to copy 1234"; }
    $query = "SELECT MenuId, CategoryId, IsVegetarian, ImgId, OrgId FROM item_details ORDER BY Ser";			
    $result = mysqli_query($connect, $query);  
    if(mysqli_num_rows($result) > 0)  
    {  
        while($row = mysqli_fetch_assoc($result)) 
        {  
            $MenuId= $row['MenuId']; 
            $CategoryId= $row['CategoryId']; 
            $IsVegetarian= $row['IsVegetarian'];
            $ImgId= $row['ImgId'];
            $OrgId= $row['OrgId'];
            if ($MenuId == "1") {
                $imgId= rand(1,10);
                $sourcefile = './breakfast/' . $imgId . '.png';
                $newfile = $OrgId . $ImgId . ".png";
                if (!copy($sourcefile, $newfile)) { echo "failed to copy 1234"; }
            }
            else if ($MenuId == "2") {
                $imgId= rand(1,10);
                $sourcefile = './lunch/' . $imgId . '.png';
                $newfile = $OrgId . $ImgId . ".png";
                if (!copy($sourcefile, $newfile)) { echo "failed to copy 1234"; }
            }
            else if ($MenuId == "3") {
                $imgId= rand(1,10);
                $sourcefile = './dinner/' . $imgId . '.png';
                $newfile = $OrgId . $ImgId . ".png";
                if (!copy($sourcefile, $newfile)) { echo "failed to copy 1234"; }
            }
            else if ($MenuId == "4") {
                $imgId= rand(1,10);
                $sourcefile = $CategoryId == "4" ? $sourcefile = './drinks/nonalcoholic/' . $imgId . '.png' : './drinks/alcoholic/' . $imgId . '.png';
                // $sourcefile = './drinks/' . $imgId . '.png';
                $newfile = $OrgId . $ImgId . ".png";
                if (!copy($sourcefile, $newfile)) { echo "failed to copy 1234"; }
            }
            else if ($MenuId == "5") {
                $imgId= rand(1,10);
                $sourcefile = './fastfood/' . $imgId . '.png';
                $newfile = $OrgId . $ImgId . ".png";
                if (!copy($sourcefile, $newfile)) { echo "failed to copy 1234"; }
            }
            else if ($IsVegetarian == "1") {
                $imgId= rand(1,10);
                $sourcefile = './veg/' . $imgId . '.png';
                $newfile = $OrgId . $ImgId . ".png";
                if (!copy($sourcefile, $newfile)) { echo "failed to copy 1234"; }
            }
            // echo $MenuId. "-".$CategoryId."-".$ImgId . "\n";				
        } 
        echo "Upload Successful...";	 
    } 
    else {
        echo json_encode("401"); 
    }	
?>