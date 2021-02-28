<?php
header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers');
include('../connect/connect.php');

$output = array();  
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$queryId = mysqli_real_escape_string($connect, $request->queryId);  
$d = date("Y-m-d");
$currDate = date("Y-m-d", strtotime($d. ' + 0 days'));
$t= date('H:i:s');
$currTime = date('H:i:s',strtotime('+8 hour',strtotime($t)));

switch ($queryId) 
{
	case "20":
		
	break;

    case "21":
		$Ser = mysqli_real_escape_string($connect, $request->Ser); 
		$OrgId = mysqli_real_escape_string($connect, $request->OrgId); 
		$SiteId = mysqli_real_escape_string($connect, $request->SiteId); 
		$JobId = mysqli_real_escape_string($connect, $request->JobId);
		$JobType = mysqli_real_escape_string($connect, $request->JobType);
		$StartDte = mysqli_real_escape_string($connect, $request->StartDate); 
		$StartDate = date("Y-m-d", strtotime($StartDte. ' + 1 days'));
		$EndDte = mysqli_real_escape_string($connect, $request->EndDate); 
		$EndDate = date("Y-m-d", strtotime($EndDte. ' + 1 days'));
		$ContractorId = mysqli_real_escape_string($connect, $request->ContractorId); 
		$Instruction = mysqli_real_escape_string($connect, $request->Instruction); 
		$Stage = mysqli_real_escape_string($connect, $request->Stage); 
		// $response = array('Ser' => $Ser, 'ResponseCode' => "201");	
		// Ser	OrgId	JobId	SiteId	JobType	Instruction	StartDate	EndDate	ContractorId	Stage

		if ($Ser == 0) 
		{
			$sql="SELECT Max(Ser), Max(JobId) FROM jobinfo";		
			$sql = mysqli_query($connect,$sql) or die(mysqli_error());
			while($row = mysqli_fetch_array($sql))
			{
				$Ser = $row['0'] == 0 ? 1 : $row['0'] + 1;
				$JobId = $row['1'] == 0 ? 1001 : $row['1'] + 1;			
			}
			$query = "INSERT INTO jobinfo VALUES ($Ser,$OrgId,$JobId,$SiteId,$JobType,
			'$Instruction','$StartDate','$EndDate',$ContractorId,$Stage)";
			$resp = mysqli_query($connect, $query) ? $JobId : mysqli_error($connect);				
			$response = array('Ser' => $Ser, 'JobId' => $JobId,'ResponseCode' => "201");				
			echo json_encode($response);
		}
		else if ($Ser > 0)  
		{
			$updateSql = "UPDATE jobinfo SET 			
			SiteId = $SiteId,
			JobType = $JobType,
			Instruction = '$Instruction',
			StartDate = '$StartDate',
			EndDate = '$EndDate',
			Instruction = '$Instruction',
			Stage = $Stage 
			WHERE Ser = $Ser";
			$respUpdate = mysqli_query($connect, $updateSql) ? "201" : mysqli_error($connect);
			if ($respUpdate) {				
				$response = array('Updated Ser' => $Ser, 'Response' => "201");
				echo json_encode($response);
			}
			else {
				$response = array('Updated Ser' => $Ser, 'Response' => "401");
				echo json_encode($response);
			}
		}
		else 
		{
			$response = array('Updated Ser' => $Ser, 'Response' => "401");
			echo json_encode($response);
		}
    break; 

	case "22":
		$query = "SELECT *FROM JobInfo ORDER BY SiteId, JobId, StartDate";
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
	break; 

	case "23":
		$query = "SELECT *FROM jobtemplate ORDER BY DayId, SiteId, ClientId";
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
	break; 

	case "24":

		$StartDte = mysqli_real_escape_string($connect, $request->StartDate); 
		$sDate = date("Y-m-d", strtotime($StartDte. ' + 1 days'));
		
		$EndDte = mysqli_real_escape_string($connect, $request->EndDate); 
		$eDate = date("Y-m-d", strtotime($EndDte. ' + 1 days'));

		$StartDate=date_create($sDate);
		$EndDate=date_create($eDate);
		$diff=date_diff($StartDate,$EndDate);
		$arr = array();
		for ($i=0; $i <= $diff->format("%a"); $i++)
		{			
			if($i==0)
			{
				$date =$StartDate;//new DateTime('2019-06-10');
				$interval = new DateInterval('P0D');
				$date->add($interval);
				$ShiftDate = $date->format("Y-m-d");
				$DaySer=date('w', strtotime($ShiftDate));
				$response = array('ShiftDate' => $ShiftDate, 'DayId' => $DaySer);	
				array_push($arr,$response);				
			}else 
			{
				$date =$StartDate;//new DateTime('2019-06-10');
				$interval = new DateInterval('P1D');
				$date->add($interval);
				$ShiftDate = $date->format("Y-m-d");
				$DaySer=date('w', strtotime($ShiftDate));
				$response = array('ShiftDate' => $ShiftDate, 'DayId' => $DaySer);
				array_push($arr,$response);					
			}
		}
		echo json_encode($arr); 
	break; 

    default:
       echo "Choice Not Found...";
}
mysqli_close($connect);

?> 


