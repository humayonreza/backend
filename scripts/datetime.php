<?php
$t= date('H:i:s');
$Checkin = date('H:i:s',strtotime('+10 hour',strtotime($t)));
echo $Checkin . '</br>';

$dt = new DateTime();
	$a_date_time= $dt->format('Y-m-d');
	$attk_date_time=date('Y-m-d',strtotime('+0 hour',strtotime($a_date_time)));
echo "Time : " .$attk_date_time . '</br>';
$JobStartTime='101000';
$ActualSigningTime='141200';
$time1 = new DateTime($JobStartTime);//start time
$time2 = new DateTime($ActualSigningTime);// late sign in time
echo $time1;
if ($time2 > $time1)
{
$interval = $time1->diff($time2);
$c= $interval->format('%H');
$k= $interval->format('%i')/60;
$sum=round(($c+$k),4);
$dt = date("Y-m-d");
$FromDate = date("Y-m-d", strtotime('+10 hour',strtotime($dt)));

echo $sum . " <br> " . $dt . "<br>" . $FromDate;
}
else
{
	echo "In time";
}
?>