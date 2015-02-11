<?
$strExcelFileName="Call-Report.xls";
header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");
include('connect/connect.php');
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
list($date1,$month1,$year1) = split('[/.-]', $_POST['date_form']); 
$date_form= $date1."/".$month1."/".$year1;

list($date2,$month2,$year2) = split('[/.-]', $_POST['date_to']); 
$date_to= $date2."/".$month2."/".$year2;

$sql="select * from call_report_date";
$sql .=" where date_visited between '".$date1."' and '".$date2."' ";
$sql .=" and month_visited between '".$month1."' and '".$month2."'";
$sql .=" and year_visited between '".$year1."' and '".$year2."'";
$res=mysql_query($sql) or die ('Error '.$sql);
?>
<strong>Call Report : <?php echo $_POST['date_form'];?> - <?php echo $_POST['date_to'];?><br>
<br>
<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
<table x:str border=1 cellpadding=0 cellspacing=1 width=100% style="border-collapse:collapse">
<tr>
<td width="94" align="center" valign="middle"><strong>PM</strong></td>
<td width="94" align="center" valign="middle"><strong>Date</strong></td>
<td width="200" align="center" valign="middle"><strong>Company</strong></td>
<td width="200" align="center" valign="middle"><strong>Function</strong></td>
<td width="100" align="center" valign="middle"><strong>Type Customer</strong></td>
<td width="80" align="center" valign="middle"><strong>In office</strong></td>
<td width="80" align="center" valign="middle"><strong>Visited</strong></td>
<td width="80" align="center" valign="middle"><strong>Booth</strong></td>
<td width="80" align="center" valign="middle"><strong>Leave</strong></td>
</tr>
<?php 
$sum_in_office=0;
$sum_visited=0;
$sum_booth=0;
$sum_leave=0;
while($rs=mysql_fetch_array($res)){
	$sql_call="select * from call_report";
	$sql_call .=" where id_call_report='".$rs['id_call_report']."'";
	$res_call=mysql_query($sql_call) or die ('Error1 '.$sql_call);
	$rs_call=mysql_fetch_array($res_call);

	$sql_account="select * from account";
	$sql_account .=" where id_account='".$rs_call['create_by']."'";
	$res_account=mysql_query($sql_account) or die ('Error2 '.$sql_account);
	$rs_account=mysql_fetch_array($res_account);
	
	if($rs_call['id_company'] != 0){
		$sql_company="select * from company";
		$sql_company .=" where id_company='".$rs_call['id_company']."'";
		$res_company=mysql_query($sql_company) or die ('Error3 '.$sql_company);
		$rs_company=mysql_fetch_array($res_company);
		$company=$rs_company['company_name'];
	}else{$company=$rs_call['company_name'];}

	if($rs_call['type_customer']==1){$call_style='<span style="color:red;">';$call_style_end='</span>';$type_customer='New';}
	elseif($rs_call['type_customer']==2){$call_style='';$call_style_end='';$type_customer='Exiting';}
	
	if($rs_call['type_office']==1){
		//$sum_in_office=$sum_in_office+1;
		$sql_in_office="select count(type_office) as sum_in_office from call_report";
		$sql_in_office .=" where type_office='1' and  id_call_report='".$rs['id_call_report']."'";
		$res_in_office=mysql_query($sql_in_office) or die ('Error4 '.$sql_in_office);
		$rs_in_office=mysql_fetch_array($res_in_office);
		$sum_in_office=$rs_in_office['sum_in_office'];
		/*$sum_visited=0;
		$sum_booth=0;
		$sum_leave=0;*/
	}else
	if($rs_call['type_office']==2){
		$sql_in_office="select count(type_office) as sum_in_office from call_report";
		$sql_in_office .=" where type_office='2' and id_call_report='".$rs['id_call_report']."'";
		$res_in_office=mysql_query($sql_in_office) or die ('Error5 '.$sql_in_office);
		$rs_in_office=mysql_fetch_array($res_in_office);
		$sum_in_office=0;

		$status_office2=split(",",$rs_call['status_office']);
		if(in_array('1',$status_office2)){
			//$sum_visited=$sum_visited+1;
			$sql_visited="select count(status_office) as sum_visited from call_report";
			$sql_visited .=" where id_call_report='".$rs['id_call_report']."'";
			$res_visited=mysql_query($sql_visited) or die ('Error6 '.$sql_visited);
			$rs_visited=mysql_fetch_array($res_visited);
			$sum_visited=$rs_visited['sum_visited'];
		}
		if(in_array('2',$status_office2)){
			//$sum_booth=$sum_booth+1;
			$sql_booth="select count(status_office) as sum_booth from call_report";
			$sql_booth .=" where id_call_report='".$rs['id_call_report']."'";
			$res_booth=mysql_query($sql_booth) or die ('Error7 '.$sql_booth);
			$rs_booth=mysql_fetch_array($res_booth);
			$sum_booth=$rs_booth['sum_booth'];
		}
		if(in_array('3',$status_office2)){
			//$sum_leave=$sum_leave+1;
			$sql_leave="select count(status_office) as sum_booth from call_report";
			$sql_leave .=" where id_call_report='".$rs['id_call_report']."'";
			$res_leave=mysql_query($sql_leave) or die ('Error8 '.$sql_leave);
			$rs_leave=mysql_fetch_array($res_leave);
			$sum_leave=$rs_leave['sum_leave'];
		}
	}			
?>
<tr>
<td width="94" align="center" valign="top"><?php echo $rs_account['username']?></td>
<td width="94" align="center" valign="top"><?php echo $rs['date_visited'].'/'.$rs['month_visited'].'/'.$rs['year_visited']?></td>
<td width="200" align="center" valign="top"><?php echo $call_style.$company.$call_sytle_end?></td>
<td width="100" align="left" valign="top">
	<?php
	$roc_func=split(",",$rs_call['id_roc_func']);
	$roc_group_func=split(",",$rs_call['id_group_product']);
	$roc_other_func=split(",",$rs_call['roc_func_other']);
	$sql_roc_group_product="select * from roc_group_product";
	$res_roc_group_product=mysql_query($sql_roc_group_product) or die ('Error '.$sql_roc_group_product);
	while($rs_roc_group_product=mysql_fetch_array($res_roc_group_product)){
		if(in_array($rs_roc_group_product['id_group_product'],$roc_group_func)){
			echo '<b>'.$group =$rs_roc_group_product['title'].'<br></b>';
			$sql_roc_function="select * from roc_function";
			$sql_roc_function .=" where id_group_product='".$rs_roc_group_product['id_group_product']."'";
			$res_roc_function=mysql_query($sql_roc_function) or die ('Error '.$sql_roc_function);
			while($rs_roc_function=mysql_fetch_array($res_roc_function)){
				if(in_array($rs_roc_function['id_roc_func'],$roc_func)){
					echo $function = $rs_roc_function['title'].'<br>';
				}
			}
		}
	}
	?>
</td>
<td width="200" align="center" valign="top"><?php echo $type_customer?></td>
<td width="80" align="center" valign="top"><?php echo $sum_in_office?></td>
<td width="80" align="center" valign="top"><?php echo $sum_visited?></td>
<td width="80" align="center" valign="top"><?php echo $sum_booth?></td>
<td width="80" align="center" valign="top"><?php echo $sum_leave?></td>
</tr>
<?php }?>
</table>
</div>
<script>
window.onbeforeunload = function(){return false;};
setTimeout(function(){window.close();}, 10000);
</script>
</body>
</html>