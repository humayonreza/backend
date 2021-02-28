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

		if ($request->TableName == 'create_emp_related_tables')
		{
			$sql="SELECT Max(Ser),Max(EmpId) FROM auth";		
			$sql = mysqli_query($connect,$sql) or die(mysqli_error());
			while($row = mysqli_fetch_array($sql))
			{
				$Ser = $row['0'] == 0 ? 1 : $row['0'] + 1;
				$EmpId = $row['1'] == 0 ? 1 : $row['1'] + 1;								
			}
			
			
			$OrgId = 0;

			$d = date("Y-m-d");
			$CreateDate = date("Y-m-d", strtotime($d. ' + 0 days'));
			
			$query = "INSERT INTO empinfo VALUES
			($Ser,$OrgId,$EmpId,$request->UserType,$request->RoleId,'$request->FirstName',
			'$request->LastName','$CreateDate','$CreateDate')";
			$response_empinfo = mysqli_query($connect, $query) ? 201 : mysqli_error($connect);
			echo json_encode($response_empinfo);

			$query = "INSERT INTO auth VALUES ($Ser,$EmpId,'$EmpId','admin','asdf@5232')";
			$response_auth = mysqli_query($connect, $query) ? 201 : mysqli_error($connect);
		}

	}


	// Ser EmpId UserId Password SecStr

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
		if ($request->TableName == 'create_emp_related_tables')
		{
			if ($request->FetchAll == 1)
			{
				$query = "SELECT 
				empinfo.Ser as Ser,
				empinfo.EmpId as EmpId,			
				empinfo.UserType as UserType,
				empinfo.RoleId as RoleId,
				empinfo.FirstName as FirstName,
				empinfo.LastName as LastName,					
				auth.UserId as UserId
				FROM empinfo,auth
				WHERE empinfo.EmpId=auth.EmpId ORDER BY empinfo.EmpId"; 
				return $query;
			}
			else 
			{
				$query = "SELECT *FROM $request->TableName 
				WHERE $request->ColName = $request->QueryVal"; 
				return $query;			
			}
		}
		else if ($request->TableName == 'create_emp_related_renew_compliances')
		{
			if ($request->FetchAll == 1)
			{
				$query = "SELECT 
				compliance.Ser as Ser, 
				compliance.EmpId as EmpId, 
				empinfo.RoleId as RoleId, 
				empinfo.FirstName as FirstName, 
				empinfo.LastName as LastName, 
				count(*) as Total 
				FROM compliance, empinfo 
				WHERE compliance.EmpId = empinfo.EmpId 
				AND DATEDIFF(ExpireDate, CURRENT_DATE) < 40 
				AND compliance.Status = 0
				Group By compliance.EmpId"; 
				return $query;
			}
			else 
			{
				$query = "SELECT 
				Ser,EmpId,TypeId,DocId,WorkZoneId,ImgId,ExpireDate,FileTypeId,
				DATEDIFF(compliance.ExpireDate, CURRENT_DATE) as DaysLeft
				FROM compliance 
				WHERE DATEDIFF(compliance.ExpireDate, CURRENT_DATE) < 40 
				AND compliance.Status = 0
				AND $request->ColName = $request->QueryVal";
				
				return $query;		
			}
		}
		
		
	}
	mysqli_close($connect);
?>



