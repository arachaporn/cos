<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			$sql ="delete from customer_business where id_customer_bu='".$_POST['ck_del'][$i]."'";
			$res = mysql_query($sql) or die ('Error '.$sql);
		}
	}
	?>
	<script type='text/javascript'>
		window.location.href = "customer-bu.php";
	</script>
	<?
}else{
	$strExcelFileName="Customer Business Evaluation Report.xls";
	header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
	header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
	header("Pragma:no-cache");
?>
	<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">
	 
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	</head>
	<body>
	<?php
	list($date1,$month1,$year1) = split('[/.-]', $_REQUEST['date_form']); 
	$date_form= $year1."-".$month1."-".$date1;

	list($date2,$month2,$year2) = split('[/.-]', $_REQUEST['date_to']); 
	$date_to= $year2."-".$month2."-".$date2;

	$sql="select * from customer_business";
	$sql .=" where create_date between '".$date_form."' and '".$date_to."' ";
	$res=mysql_query($sql) or die ('Error '.$sql);
	?>
	<strong>Customer Business Evaluation : <?php echo $_REQUEST['date_form'];?> - <?php echo $_REQUEST['date_to'];?><br>
	<br>
	<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
	<table x:str border=1 cellpadding=0 cellspacing=1 width=100% style="border-collapse:collapse">
	<tr>
		<td width="94" align="center" valign="middle"><strong>Date</strong></td>
		<td width="200" align="center" valign="middle"><strong>Customer name</strong></td>
		<td width="200" align="center" valign="middle"><strong>Company</strong></td>
		<td width="200" align="center" valign="middle"><strong>Function</strong></td>
		<td width="80" align="center" valign="middle"><strong>Category Product</strong></td>
	</tr>
	<?php 
	$sum_in_office=0;
	while($rs=mysql_fetch_array($res)){
		$sql_account="select * from account";
		$sql_account .=" where id_account='".$rs_call['create_by']."'";
		$res_account=mysql_query($sql_account) or die ('Error '.$sql_account);
		$rs_account=mysql_fetch_array($res_account);	
		
		$sql_cate_product="select * from product_appearance";
		$sql_cate_product .=" where id_product_appearance='".$rs['id_product_appearance']."'";
		$res_cate_product=mysql_query($sql_cate_product) or die ('Error '.$sql_cate_product);
		$rs_cate_product=mysql_fetch_array($res_cate_product);
	?>
	<tr>
		<td width="94" align="center" valign="top"><?php list($date3,$month3,$year3) = split('[/.-]', $rs['create_date']); 
		echo$date3= $date3."/".$month3."/".$year3;?></td>
		<td width="200" align="left" valign="top"><?php echo $rs['name_bu'].'&nbsp;&nbsp;&nbsp;'.$rs['surname']?></td>
		<td width="200" align="left" valign="top"><?php echo $rs['company_name']?></td>
		<td width="200" align="left" valign="top">
			<?php
			$roc_func=split(",",$rs['id_roc_func']);
			$roc_group_func=split(",",$rs['id_group_product']);
			$roc_other_func=split(",",$rs['roc_func_other']);
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
		<td width="200" align="center" valign="top"><?php echo $rs_cate_product['title']?></td>
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
<script type='text/javascript'>
	window.location.href = "customer-bu.php";
</script>
<?php } ?>