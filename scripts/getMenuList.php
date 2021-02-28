<?php
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers'); 
	 
	include('../connect/connect.php');
	// $output = array();  
	// $postdata = file_get_contents("php://input");
	// $request = json_decode($postdata);
	// $MenuId = $request->MenuId;
	
	// $query = "SELECT *FROM menu WHERE MenuId = $MenuId ORDER BY Ser DESC"; 

	// $result = mysqli_query($connect, $query);  
	// if(mysqli_num_rows($result) > 0)  
	// {  
	//   while($row = mysqli_fetch_assoc($result)) 
	//   {  
	//        $output[] = $row;  
	//   }  
	//   echo json_encode($output);    
	// } 

	// Ser,OrgId,ObjTypeId,ParentId,Caption,Txt170,Txt50,Txt30,ImgId,Val,FlagId :: lookup_info

	
    // Ser,PageId,ParentId,ChildId,ObjectId,Caption,Link,Val,ClassId,IconClass,ImgId,OrgId,FlagId

	$query = "SELECT *FROM lookup_table1 ORDER BY Ser"; 

	$result = mysqli_query($connect, $query);  
	if(mysqli_num_rows($result) > 0)  
	{  
	  while($row = mysqli_fetch_assoc($result)) 
	  {  
		//    $x = $row['Ser'];
			$Ser = $row['Ser'];
			$OrgId =$row['OrgId'];
			$ObjectId =$row['ObjectId'];
			$ParentId =$row['ParentId'];
			$Caption =$row['Caption'];
			$Txt170 =$row['Caption'];
			$Txt50 =$row['Link'];
			$Txt30 ="NA";
			$ImgId =$row['ImgId'];
			$Val =$row['Val'];
			$FlagId =$row['FlagId'];
		//    echo $x;
		   $sql="INSERT INTO lookup_info(Ser,OrgId,ObjTypeId,ParentId,Caption,Txt170,Txt50,Txt30,ImgId,Val,FlagId) VALUES ($Ser,$OrgId,$ObjectId,$ParentId,'$Caption','$Txt170','$Txt50' ,'$Txt30', $ImgId,$Val, $FlagId)"; 
		   $resp = mysqli_query($connect, $sql) ? "201" : mysqli_error($connect);
	  }  
	  echo $resp;    
	} 
	
	
?>