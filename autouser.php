<?php
include("connect/connect.php");
echo$sql = "select * from account where 1 and id_account = '".$_POST["sCusID"]."' ";
$res = mysql_query($sql) or die (mysql_error());
$intNumField = mysql_num_fields($res);
$resultArray = array();
while($rs = mysql_fetch_array($res)){
	$arrCol = array();
	for($i=0;$i<$intNumField;$i++){
		$arrCol[mysql_field_name($res,$i)] = $rs[$i];
	}
	array_push($resultArray,$arrCol);
}
echo json_encode($resultArray);
?>