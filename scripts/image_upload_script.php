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
    $imgType = $strInfo['1'];
    $imgName = $strInfo['2'];

    if ($imgFolder == "0" ){
        $store_path = "../images/menu/";
    }
    else if ($imgFolder == "1" ){
        $store_path = "../images/kitchen_spl/";
    }
    else if ($imgFolder == "2" ){
        $store_path = "../images/items/";
    }
    // $store_path = $imgFolder == "0" ? "../images/menu/" 
    //             : $imgFolder == "1" ? "../images/kitchen_spl/"
    //             : $imgFolder == "2" ? "../images/items/" : NULL;
    // $store_path = "../images/menu/";
    if ( $_FILES['file']['error'] > 0 )
    {
        echo 'Error: ' . $_FILES['file']['error'];
    }
    else 
    {
        if ($imgType != "0")
        {
            require_once('ImageManipulator.php');
            $manipulator = new ImageManipulator($_FILES['file']['tmp_name']);
            $imgType == "1" ? $manipulator->resample(800, 800) 
            : $imgType == "2" ? $manipulator->resample(100, 100) 
            : NULL;
            // $newImage = $manipulator->resample(800, 800);
            // $newImage = $manipulator->resample(100, 100);
            error_reporting(0);
            $manipulator->save($store_path . $imgName . '.png');//$_FILES['file']['name']);
            echo "Upload Successful..";    
        }
        else
        {
            if(move_uploaded_file($_FILES['file']['tmp_name'], $store_path . $imgName . '.png'))
            {
                echo "Upload Successful";        
            }
           
        }
    }    
?>