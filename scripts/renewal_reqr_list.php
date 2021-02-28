<?php
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers'); 
	 
	include('../connect/connect.php');
	$output = array();  
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	$query = "SELECT 
				compliance.Ser as Ser, 
				compliance.EmpId as EmpId, 
				empinfo.RoleId as RoleId, 
				empinfo.FirstName as FirstName, 
				empinfo.LastName as LastName, 
				count(*) as Total 
				FROM compliance, empinfo 
				WHERE compliance.EmpId = empinfo.EmpId 
				And CURRENT_DATE> compliance.RenewDate 
				Group By compliance.EmpId"; 

	$result = mysqli_query($connect, $query);  
	if(mysqli_num_rows($result) > 0)  
	{  
	  while($row = mysqli_fetch_assoc($result)) 
	  {  
	       $output[] = $row;  
	  }  
	  echo json_encode($output);    
	} 
	else {
		echo json_encode("401");
	}
	mysqli_close($connect);
// Ser EmpId TypeId DocId ImgId WorkZoneId UploadDate ExpireDate FileTypeId
?>