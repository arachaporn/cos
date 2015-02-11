<?
$zero=$_GET["month"];
$month=$_GET["month"];
$year=$_GET["year"];
$month_name=date('F', mktime(0, 0, 0, $month));
$strExcelFileName="Sales Forecast ".$month_name." ".$year.".xls";
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
<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
<table x:str border=1 cellpadding=0 cellspacing=1 width=100% style="border-collapse:collapse">
	<tr>
		<td colspan="2" height="80" style="border-right:none;"><img src="http://cdipthailand.com/cos/img/logo.png" width="100"></td>
		<td colspan="5" style="vertical-align:middle;text-align:center;font-size:20px;border-left:none;"><?php echo $po='Sales forecast <span style="color:red">'.$month_name.'</span>&nbsp;'.$year;?></td>
	</tr>
	<tr>
		<td colspan="7" style="text-align:center;font-size:20px;font-weight:bold;">Vat</td>
	</tr>
</table>
</div>
<?php
for($i=0;$i<=5;$i++){
	if($i !=0){?>
		<br>
		<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
		<table x:str border=1 cellpadding=0 cellspacing=1 width=100% style="border-collapse:collapse">
			<tr>
				<td style="text-align:center;vertical-align:middle;">PM</td>
				<td style="text-align:center;;vertical-align:middle;">Customer</td>
				<td style="text-align:center;vertical-align:middle;">Product</td>
				<td style="text-align:center;">Quantities<br>(Unit)</td>
				<td style="text-align:center;">Price per<br>Unit (Baht)</td>
				<td style="text-align:center;vertical-align:middle;">Total</td>
				<td style="text-align:center;vertical-align:middle;">Note</td>
			</tr>		
			<?php 
			if($i==1){$pm=25;}elseif($i==2){$pm=30;}elseif($i==3){$pm=32;}elseif($i==4){$pm=31;}elseif($i==5){$pm=26;}elseif($i==5){$pm=27;}
			$total=0;
			$reject=0;
			$sum_reject=0;
			$sql="select * from sm_sales_forecast where month_visited='".$zero."'";
			$sql .=" and year_visited='".$year."' and create_by='".$pm."'";
			$sql .=" and status_forecast='PO'";
			$res=mysql_query($sql) or die ('Error '.$sql);
			$num_row=mysql_num_rows($res);
			if($num_row==0){
			?>
			<table style="width:100%;" border=1 cellpadding="0" cellspacing="0">
				<?php for($j=0;$j<=3;$j++){?>
				<?php
				$sql_acc2="select * from account where id_account='".$pm."'";
				$res_acc2=mysql_query($sql_acc2) or die ('Error '.$sql_acc2);
				$rs_acc2=mysql_fetch_array($res_acc2);			
				?>
				<tr>
					<td style="text-align:center;"><?php if($pm != 27){echo /*0substr($rs_acc2['username'],2);*/ $rs_acc2['username'];}else{echo $rs_acc2['username'];}?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>	
				<?php }//end for?>
				<tr>
					<td colspan="5"></td>
					<td style="text-align:right;"><?php echo '<span style="text-decoration:underline;">'.number_format($sum_reject,2).'</span>'?></td>
				</tr>
			</table>
			<?php 
			}else{
				while($rs=mysql_fetch_array($res)){	
					if($rs['po_reject']==1){
						$reject=$reject+$rs['total'];
						$style_red='<span style="color:red;">';
						$style_end_red='</span>';
					}else{
						$style_red='';
						$style_end_red='';
						$total=$total+$rs['total'];
					}		
			?>
			<table style="width:100%;" border=1 cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align:center;vertical-align:top;">
						<?php
						$sql_acc="select * from account where id_account='".$rs['create_by']."'";
						$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
						$rs_acc=mysql_fetch_array($res_acc);
						if($pm==0){echo 'N/A';}
						elseif($pm != 27){echo /*substr($rs_acc['username'],2);*/$rs_acc['username'];}
						else{echo $rs_acc['username'];}
						?>
					</td>
					<td style="vertical-align:top;">
						<?php
						$sql_customer="select * from company where id_company='".$rs['id_customer']."'";
						$res_customer=mysql_query($sql_customer) or die ('Error '.$sql_customer);
						$rs_customer=mysql_fetch_array($res_customer);
						echo $rs_customer['company_name'];
						?>
					</td>
					<td style="vertical-align:top;">
						<?php
						/*$sql_product="select * from product where id_product='".$rs['id_product']."'";
						$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
						$rs_product=mysql_fetch_array($res_product);*/
						echo $style_red.$rs['product_name'].$style_end_red;
						?>
					</td>
					<td style="text-align:right;vertical-align:top;"><?php echo $style_red;echo number_format($rs['quantities'],2);echo $style_end_red;?></td>
					<td style="text-align:right;vertical-align:top;"><?php echo $style_red;echo number_format($rs['price_per_baht'],2);echo $style_end_red;?></td>
					<td style="text-align:right;vertical-align:top;"><?php echo $style_red;echo number_format($rs['total'],2);echo $style_end_red;?></td>			
					<td style="vertical-align:top;"><?php echo $style_red.$rs['note'].$style_end_red?></td>	
				</tr>		
			</table>
			<?php }//end while?>
			<table style="width:100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td colspan="6" style="text-align:right;"><?php $sum_reject=$total-$reject;echo '<span style="text-decoration:underline;">'.number_format($sum_reject,2).'</span>'?></td>
				</tr>
			</table>			
		<?php }//end if?>
		</div>
	<?php }//end if i?>	
<?php }//end for pm1-pm6?>
<?php
$reject2=0;
$total2=0;
$sum_reject2=0;
$grand_total=0;
$sql2="select * from sm_sales_forecast where month_visited='".$zero."'";
$sql2 .=" and year_visited='".$year."'";
$sql2 .=" and status_forecast='PO'";
$res2=mysql_query($sql2) or die ('Error '.$sql2);
while($rs2=mysql_fetch_array($res2)){	
	if($rs2['po_reject']==1){
		$reject2=$reject2+$rs2['total'];
		$style_red='<span style="color:red;">';
		$style_end_red='</span>';
	}else{
		$style_red='';
		$style_end_red='';
		$total2=$total2+$rs2['total'];
	}	
	$sum_reject2=$total2-$reject2;
}?><br>
<table style="width:100%;" border=1 cellpadding="0" cellspacing="0">		
	<tr>
		<td colspan="4" style="text-align:center;"></td>	
		<td style="padding:0.5%;">Total</td>
		<td style="text-align:right;"><?php echo number_format($sum_reject2,2)?></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align:center;"></td>	
		<td>Vat7%</td>
		<td><?php $vat=$sum_reject2*0.07;echo number_format($vat,2)?></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align:center;"></td>	
		<td>Grand Total</td>
		<td style="text-align:right;"><?php $grand_total=$vat+$sum_reject2;echo number_format($grand_total,2)?></td>
	</tr>
</table><br>
<table style="width:100%;" cellpadding="0" cellspacing="0">		
	<tr>
		<td colspan="2" style="text-align:left;">สรุปยอดสั้งซื้อทั้งหมด</td>	
		<td style="text-align:right;"><?php $sum=$grand_total-$reject2;echo number_format($grand_total,2)?></td>
		<td style="text-align:left;">บาท</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:left;">ยอดที่ถูกยกเลิก**</td>	
		<td style="text-align:right;"><?php echo number_format($reject2,2)?></td>
		<td style="text-align:left;">บาท</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:left;">หมายเหตุ</td>	
	</tr>
</table>
<table style="width:100%;" cellpadding="0" cellspacing="0">	
	<?php 
	$sql_target="select * from sm_sales_target where month_visited='".$zero."'";
	$sql_target .=" and year_visited='".$year."' and type_target='PO'";
	$res_target=mysql_query($sql_target) or die ('Error '.$sql_target);
	$rs_target=mysql_fetch_array($res_target);
	?>
	<tr>
		<td colspan="2" style="text-align:left;">เป้าหมาย</td>	
		<td style="text-align:right;"><?php if($rs_target['sales_target']==''){echo '0';}else{echo number_format($rs_target['sales_target'],2);}?></td>
		<td style="text-align:left;">บาท</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:left;">เปอร์เซ็นต์จากเป้า</td>	
		<td style="text-align:right;"><?php if($rs_target['sales_target']==0){echo$persent=0;}else{$persent=(($grand_total*100)/$rs_target['sales_target']);echo number_format($persent,2);}?></td>
		<td style="text-align:left;">%</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:left;">ยอดต่าง</td>	
		<td style="text-align:right;"><?php if($rs_target['sales_target']==''){echo$total_diff=0;}else{$total_diff=$rs_target['sales_target']-$grand_total;echo number_format($total_diff,2);}?></td>
		<td style="text-align:left;">บาท</td>
	</tr>
</table><br><br><br>
<table style="width:100%;" cellpadding="0" cellspacing="0">		
	<tr>
		<td colspan="6" style="text-align:right;">
			Approved by.<br><br><br>
			...................................................................<br>
			(Ms.Pichchakarn Pitthayakornpisuth)<br>
			Chief Operating Officer<br>
			Date..............................................
		</td>	
	</tr>
</table>
<script>
window.onbeforeunload = function(){return false;};
setTimeout(function(){window.close();}, 10000);
</script>
</body>
</html>