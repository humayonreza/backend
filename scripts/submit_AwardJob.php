<?php
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');

	// Ser,Urid,AwardDate,AwardTime,ShiftDate,TimeOn,TimeOff,TotalHours,
  	// SiteName,SiteAddress,SiteContact,Conpliance,Status
	
	include('../connect/connect.php');
	$output = array();  
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);

	$Ser = $request->Ser;	
	$Urid= $request->Urid;	 //  Urid = User row id
	$ContractorName = $request->ContractorName;		
	$d = date("Y-m-d");
	$AwardDate = date("Y-m-d", strtotime($d. ' + 0 days'));
	$t= date('H:i:s');
	$AwardTime = date('H:i:s',strtotime('+8 hour',strtotime($t)));		
	$ShiftDate = $request->ShiftDate;
	$TimeOn = $request->TimeOn."00";
	$TimeOff = $request->TimeOff."00";	
	$TotalHours = 6;//$request->UnitId;	
	$SiteName = $request->SiteName;
	$SiteAddress = $request->SiteAddress;	
	$SiteContact = $request->SiteContact;
	$Compliance = $request->Compliance;
	$Status = 0;//$request->Status;	
    // 	$Rep = "Dan, Wilson";

	if ($Ser == 0)
	{
		$sql="SELECT Max(Ser) FROM unscheduled_shift";
		$sql = mysqli_query($connect,$sql) or die(mysqli_error());
		while($row = mysqli_fetch_array($sql))
		{
			$newSer = $row['0'] == 0 ?  $row['0'] + 1 : $row['0'] + 1;
		}		
		$query = "INSERT INTO unscheduled_shift(Ser,Urid,AwardDate,AwardTime,ShiftDate,TimeOn,TimeOff,TotalHours,SiteName,SiteAddress,SiteContact,Compliance,Status) 
		VALUES ($newSer,$Urid,'$AwardDate','$AwardTime','$ShiftDate','$TimeOn','$TimeOff',$TotalHours,
		'$SiteName','$SiteAddress','$SiteContact','$Compliance', $Status)"; 		
		if(mysqli_query($connect, $query))  
		{  
		    $response = 201;
		    echo json_encode($response);  
		}  
		else  
		{  
		    $response = 401;
		    echo json_encode($response); 
		}  
		// $sql = mysqli_query($connect,$sql_updt) or die(mysqli_error()); 
	}
	mysqli_close($connect);

	// else 
	// {
	// 	$query="UPDATE auth SET 
	// 	UnitId = $UnitId, 
	// 	RankId = $RankId, 
	// 	FullName = '$FullName', 
	// 	userId ='$UserId',
	// 	userPassword = '$Password',
	// 	category = $Category
	// 	WHERE Ser = $Ser";
	// 	if(mysqli_query($connect, $query))  
	// 	{  
	// 	    $response = $Ser;
	// 	    echo json_encode($response);  
	// 	}  
	// 	else  
	// 	{  
	// 	    $response = 401;
	// 	    echo json_encode($response); 
	// 	}  
	// }

	function sendSMS($content) 
	{
        $ch = curl_init('https://api.smsbroadcast.com.au/api-adv.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec ($ch);
        curl_close ($ch);
        return $output;    
    }

    $username = 'xcoderadmin';
    $password = 'xcoder5232';
    $destination = '0450819211'; 
    //Multiple numbers can be entered, separated by a comma
    $source    = 'XPG';
    $text = 
    "From :" . $ContractorName . "\n" .
    "Date :" . $ShiftDate . "\n" .
    "Site :" . $SiteName . "\n" .
    "Loc :" . $SiteAddress . "\n" .
    "Tel :" . $SiteContact . "\n" .
    "Duration :" . $request->TimeOn . "-" . $request->TimeOff . "\n" .
    "Note :" . $Compliance;

    $ref = 'abc123';
        
    $content =  'username='.rawurlencode($username).
                '&password='.rawurlencode($password).
                '&to='.rawurlencode($destination).
                '&from='.rawurlencode($source).
                '&message='.rawurlencode($text).
                '&ref='.rawurlencode($ref);
  
    $smsbroadcast_response = sendSMS($content);
    $response_lines = explode("\n", $smsbroadcast_response);
    
     foreach( $response_lines as $data_line){
        $message_data = "";
        $message_data = explode(':',$data_line);
        if($message_data[0] == "OK"){
            $respError = 0;
            // echo "The message to ".$message_data[1]." was successful, with reference ".$message_data[2]."\n";
        }elseif( $message_data[0] == "BAD"){
           $respError = 1; 
           // echo "The message to ".$message_data[1]." was NOT successful. Reason: ".$message_data[2]."\n";
        }elseif( $message_data[0] == "ERROR"){
          $respError = 2;  
          // echo "There was an error with this request. Reason: ".$message_data[1]."\n";
        }
    }
	

?>