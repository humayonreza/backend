<?php
    header('Content-type: application/json');
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers'); 
    var_dump($_FILES);
    $infoBundle = $_FILES['file']['name'];
    $infoBundle = explode(".",$infoBundle);
    $infoString = $infoBundle['0'];
    $strInfo = explode("-",$infoString);
    $imgFolder = $strInfo['0'];
    $imgId = $strInfo['1'];

    if ($imgFolder == "6" ){
        $store_path = "../images/services/";
    }
    else if ($imgFolder == "7" ){
        $store_path = "../images/banner/";
    }
    else if ($imgFolder == "8" ){
        $store_path = "../images/slider/";
    }
    
    if ( $_FILES['file']['error'] > 0 )
    {
        echo 'Error: ' . $_FILES['file']['error'];
    }
    else 
    {
        if(move_uploaded_file($_FILES['file']['tmp_name'], $store_path . $imgId . '.png'))
        {
            echo "Upload Successful";        
        }
    }    
?>