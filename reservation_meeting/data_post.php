<?
	include("../connect/connect.php");

	/*$time1 = $_POST["myHour1"].":".$_POST["myMin1"].":00";
	$time2 = $_POST["myHour2"].":".$_POST["myMin2"].":00";

	$strSQL = "SELECT * FROM meeting_list WHERE ((strdate between '".$_POST["myDate1"]."' and '".$_POST["myDate2"]."') or ";
	$strSQL .= " (enddate between '".$_POST["myDate1"]."' and '".$_POST["myDate2"]."')) and ";
	$strSQL .= " ((strtime between '".$time1."' and '".$time2."') or ";
	$strSQL .= " (endtime between '".$time1."' and '".$time2."')) and room = '".$_POST["myRoom"]."' and mstatus in('Y','N') ";
	$objQuery = mysql_query($strSQL);
	$objResult = mysql_fetch_array($objQuery);*/

	list($month,$day,$year) = split('[/.-]', $_POST["myDate"]); 
	$date=$year.'-'.$month.'-'.$day;

	list($start_time) = split('[ AM. PM]',$_POST["myTime1"]); 
	list($end_time) = split('[ AM. PM]',$_POST["myTime2"]); 
	
	$sql="select * from meeting_room_list where (start_date ='".$date."')";
	$sql .=" and ((start_time between '".$start_time."' and '".$end_time."') or";
	$sql .=" (end_time between '".$start_time."' and '".$end_time."'))";
	$sql .=" and id_room_type='".$_POST["myRoom"]."'";
	$res=mysql_query($sql) or die ('Error room list : '.$sql);
	$rs=mysql_fetch_array($res);
	if($rs)
	{
		echo "ห้องไม่ว่าง มีคนจองแล้ว!";
	}
	else
	{
		echo "ห้องว่าง สามารถจองได้!";
	}

?>



