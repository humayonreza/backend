<?php

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: X-Requested-With, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers'); 
header('Content-Type: text/html; charset=utf-8');

	include('../connect/connect.php');

	$StartDate=date_create("2020-09-01");
	$EndDate=date_create("2020-09-30");
	$diff=date_diff($StartDate,$EndDate);
	$arrS = array (array('ShiftDate' => '0000-00-00', 'DaySer' => 10));
	 
	for ($i=0; $i <= $diff->format("%a"); $i++)
	{			
		if($i==0)
		{
			$date =$StartDate;//new DateTime('2019-06-10');
			$interval = new DateInterval('P0D');
			$date->add($interval);
			createshift($date);
		}
		else 
		{
			$date =$StartDate;//new DateTime('2019-06-10');
			$interval = new DateInterval('P1D');
			$date->add($interval);
			createshift($date);
		}
	}

	function createshift($date)
	{
		$ShiftDate = $date->format("Y-m-d");
		$DaySer=date('w', strtotime($ShiftDate));
		$response = array('ShiftDate' => $ShiftDate, 'DaySer' => $DaySer);			
		echo json_encode($response);  
	}
	
	mysqli_close($connect);
?>




