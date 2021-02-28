<?php
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers'); 
	 
	include('../connect/connect.php');
	$output = array();  
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	$CrudType = $request->CrudType;	
	
	// echo json_encode("Reza.."); 
	if ($CrudType ==0)  //  INSERT
	{
		perform_insert($request);
	}
	else if ($CrudType ==1)  //  UPDATE
	{
		perform_update($request);		
	}
	else if ($CrudType ==2)  //  DELETE
	{
		$query = generate_delete_query($request);			 		
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
	}

	else if ($CrudType ==3)  //  SELECT ALL
	{
		$query = generate_select_query($request);
					 		
		$result = mysqli_query($connect, $query);  
		if(mysqli_num_rows($result) > 0)  
		{  
			while($row = mysqli_fetch_assoc($result)) 
			{  
			    $output[] = $row;  
			}  
			echo json_encode($output);    
		}
		else{
			echo json_encode("401");
		}
	}

	function perform_insert($request)
	{
		include('../connect/connect.php');
		$sql="SELECT Max(Ser) FROM $request->TableName";		
		$sql = mysqli_query($connect,$sql) or die(mysqli_error());
		while($row = mysqli_fetch_array($sql))
		{
			if ($row['0'] == 0 && (($request->TableName =="shift_template") || 
			($request->TableName =="empcontactinfo") || ($request->TableName =="compliance")))
			{
				$Ser = 1;
			}
			else if ($row['0'] == 0 && $request->TableName =="MyTableName"){
				$Ser = 1001;
			}
			else 
			{
				$Ser = $row['0'] + 1;
			}
		}
		if ($request->TableName =="shift_template")
		{
			$query = "INSERT INTO $request->TableName VALUES
			($Ser,$request->SiteId,$request->WkDaySer,$request->TimeOn,$request->TimeOff,$request->GdRequired)";
			$response = mysqli_query($connect, $query) ? 201 : mysqli_error($connect);
			echo json_encode($response);
		}
		else if ($request->TableName =="empcontactinfo")
		{
			$query = "INSERT INTO $request->TableName VALUES
			($Ser,$request->EmpId,'$request->HomeAddress','$request->PersContactNo','$request->HomeContactNo',
			'$request->PostCode','$request->Suburb','$request->Email')";
			$response = mysqli_query($connect, $query) ? 201 : mysqli_error($connect);
			echo json_encode($response);
		}
		else if ($request->TableName =="compliance")
		{
			$ImgId = $request->EmpId . "-" . $Ser;
			$ImgId = ltrim($ImgId);
			$ImgId = rtrim($ImgId);
			// $d = date("Y-m-d");
			// $UploadDate = date("Y-m-d", strtotime($d. ' + 0 days'));
			// $RenewDate = date("Y-m-d", strtotime($request->ExpireDate. ' -45 days'));
			$query = "INSERT INTO $request->TableName VALUES
			($Ser,$request->EmpId,$request->TypeId,$request->DocId,'$ImgId',$request->WorkZoneId,
			'$request->ExpireDate',$request->FileTypeId)";
			$response = mysqli_query($connect, $query) ? 201 : mysqli_error($connect);
			echo json_encode($ImgId);
		}
	}

	// Ser EmpId HomeAddress PersContactNo HomeContactNo PostCode Suburb Email

	function perform_update($request)
	{
		include('../connect/connect.php');
		if ($request->TableName =="empcontactinfo")
		{
			$query="UPDATE $request->TableName SET 
			HomeAddress = '$request->HomeAddress',
			PostCode = '$request->PostCode',
			Suburb = '$request->Suburb',
			Email = '$request->Email',
			PersContactNo = '$request->PersContactNo',
			HomeContactNo = '$request->HomeContactNo'  
			WHERE Ser = $request->Ser";
			$response = mysqli_query($connect, $query) ? 201 : mysqli_error($connect);
			echo json_encode($response);
		}
		else if ($request->TableName =="compliance")
		{
			$query="UPDATE $request->TableName SET 
			ExpireDate = '$request->ExpireDate',
			FileTypeId = $request->FileTypeId			
			WHERE Ser = $request->Ser";
			$response = mysqli_query($connect, $query) ? 201 : mysqli_error($connect);
			echo json_encode($response);
		}
	}

	function generate_delete_query($request)
	{
		include('../connect/connect.php');
		if ($request->TableName =="colorcode")
		{
			$query="DELETE FROM $request->TableName WHERE Ser = $request->Ser"; 
			return $query;
		}
	}

	function generate_select_query($request)
	{
		if ($request->TableName =="compliance")
		{
			if ($request->FetchAll == 1)
			{
				$query = "SELECT *FROM $request->TableName ORDER BY Ser"; 
				return $query;
			}
			else
			{
				$query = "SELECT Ser, EmpId,TypeId, DocId, ImgId, WorkZoneId, ExpireDate, FileTypeId, 
				DATEDIFF(ExpireDate, CURRENT_DATE) as DaysLeft FROM $request->TableName
				WHERE $request->ColName = $request->QueryVal"; 
				return $query;	
			}

		}
		else 
		{
			$query = "SELECT *FROM $request->TableName WHERE $request->ColName = $request->QueryVal 
			Order By Ser"; 
			return $query;			
		}		
	}
	 // FROM compliance Where EmpId = 1002
	mysqli_close($connect);
?>



