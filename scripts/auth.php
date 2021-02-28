<?php
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
	
	include('../connect/connect.php');

	$output = array();  
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$UserId = mysqli_real_escape_string($connect, $request->UserId); 
	$Password = mysqli_real_escape_string($connect, $request->Password);
	$header ="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9";
	// echo json_encode($UserId);
	$query = "SELECT	
	signin.Ser as Ser, 
	signin.UserType as UserType,	
	signin.FullName as FullName,
	signin.PasswordCreateDate as PasswordCreateDate,
	signin.SecStr as SecStr,
	signin.OrgId as OrgId,
	orginfo.OrgName as OrgName
	FROM signin, orginfo
	WHERE signin.OrgId = orginfo.OrgId
	AND signin.UserId = '$UserId' AND signin.Password ='$Password' 
	AND orginfo.isActive = 1";  

	$result = mysqli_query($connect, $query);  
	if(mysqli_num_rows($result) > 0)  
	{  
		while($row = mysqli_fetch_assoc($result)) 
		{ 
		   $output[] = $row;
		   $SecStr = $row["SecStr"];
		} 
		$data = json_encode($output);
		$data = ltrim($data, '['); 
		$data = rtrim($data, ']'); 
		$encoded_dbresponse = base64_encode($data);
		$encoded_backend_response = $header . "." . $encoded_dbresponse .".". $SecStr;		
		echo json_encode($encoded_backend_response);		
	}  
	else 
	{
		$output = 401;
		echo json_encode($output);
	}		
	mysqli_close($connect);
?>


