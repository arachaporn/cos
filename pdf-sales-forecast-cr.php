<?php
ob_start();
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + (1* 10) ; // ending a session in 30 seconds
}
$now = time(); // checking the time now when home page starts

if($now > $_SESSION['expire']){
	session_destroy();
	echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
}else{
	echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
}

include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
$_SESSION["id_company"]=$_REQUEST['id_company'];
$_SESSION['company_name']=$_REQUEST['company_name'];

//create pdf
include("mpdf/mpdf.php");
?>
<!DOCTYPE html>
<body>
	<?php
	/*if($_GET["month"]<10){$zero='0'.$_GET["month"];}else{$zero=$_GET["month"];}*/
	$zero=$_GET["month"];
	$month=$_GET["month"];
	$year=$_GET["year"];
	$st=$_GET['st'];	
	$month_name=date('F', mktime(0, 0, 0, $month));
	$po='CR forecast <span style="color:red">'.$month_name.'</span>&nbsp;'.$year;
	for($i=0;$i<=5;$i++){
	if($i !=0){
	?>
	<table style="border:1px solid #000;border-bottom:none;width:100%;font-size:0.9em;margin:5% 0 0 0;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;width:8.5%;">PM</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;width:11.4%;">PO. No.</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;width:15.5%;">Customer</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;width:20.5%;">Product</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;width:10%;">Quantities<br>(Unit)</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;width:15%;">Price per<br>Unit (Baht)</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;width:15%;">Total</td>
			<td style="border-bottom:1px solid #000;text-align:center;width:21%;">Note</td>
		</tr>
	</table>
	<?php 
	if($i==1){$pm=25;}elseif($i==2){$pm=30;}elseif($i==3){$pm=32;}elseif($i==4){$pm=31;}elseif($i==5){$pm=26;}elseif($i==5){$pm=27;}
	$total=0;
	$reject=0;
	$sum_reject=0;
	$sql="select * from sm_sales_forecast where month_visited='".$zero."'";
	$sql .=" and year_visited='".$year."' and create_by='".$pm."'";
	$sql .=" and status_forecast='CR'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$num_row=mysql_num_rows($res);
	if($num_row==0){
	?>
	<table style="width:100%;font-size:0.9em;line-height:1.5em;" cellpadding="0" cellspacing="0">
	<?php for($j=0;$j<=3;$j++){?>
	
		<?php
			$sql_acc2="select * from account where id_account='".$pm."'";
			$res_acc2=mysql_query($sql_acc2) or die ('Error '.$sql_acc2);
			$rs_acc2=mysql_fetch_array($res_acc2);			
		?>
		<tr>
			<td style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8.5%;text-align:center;"><?php if($pm != 27){echo substr($rs_acc2['username'],2);}else{echo $rs_acc2['username'];}?></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;width:11.5%;"></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;width:15.4%;"></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;width:20.4%;"></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;width:10%;"></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;width:15%;"></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;width:15%;"></td>
			<td style="border-bottom:1px solid #000;border-right:1px solid #000;width:21%;"></td>
		</tr>	
	<?php }//end for?>
		<tr>
			<td colspan="6"></td>
			<td style="text-align:right;padding:0.5%;"><?php echo '<span style="text-decoration:underline;border-bottom: 1px solid #000;">'.number_format($sum_reject,2).'</span>'?></td>
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
	<table style="width:100%;font-size:0.9em;line-height:1.5em;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;text-align:center;vertical-align:top;width:8.5%;">
				<?php
				$sql_acc="select * from account where id_account='".$rs['create_by']."'";
				$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
				$rs_acc=mysql_fetch_array($res_acc);
				if($pm==0){echo 'N/A';}
				elseif($pm != 27){echo substr($rs_acc['username'],2);}
				else{echo $rs_acc['username'];}
				?>
			</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;vertical-align:top;width:11.5%;padding:0.5%;"><?php echo $rs['po_no']?></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;vertical-align:top;width:15.4%;padding:0.5%;">
				<?php
				$sql_customer="select * from company where id_company='".$rs['id_customer']."'";
				$res_customer=mysql_query($sql_customer) or die ('Error '.$sql_customer);
				$rs_customer=mysql_fetch_array($res_customer);
				echo $rs_customer['company_name'];
				?>
			</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;vertical-align:top;width:20.4%;padding:0.5%;">
				<?php
				$sql_product="select * from product where id_product='".$rs['id_product']."'";
				$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
				$rs_product=mysql_fetch_array($res_product);
				echo $style_red.$rs_product['product_name'].$style_end_red;
				?>
			</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:right;vertical-align:top;width:10%;padding:0.5%;"><?php echo $style_red;echo number_format($rs['quantities'],2);echo $style_end_red;?></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:right;vertical-align:top;width:15%;padding:0.5%;"><?php echo $style_red;echo number_format($rs['price_per_baht'],2);echo $style_end_red;?></td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:right;vertical-align:top;width:15%;padding:0.5%;"><?php echo $style_red;echo number_format($rs['total'],2);echo $style_end_red;?></td>			
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;vertical-align:top;width:21%;padding:0.5%;"><?php echo $style_red.$rs['note'].$style_end_red?></td>	
		</tr>		
	</table>
	<?php }//end while?>
	<table style="width:100%;font-size:0.7em;line-height:1.5em;" cellpadding="0" cellspacing="0">
		<tr>
			<td></td>
			<td style="text-align:right;padding:0.5%;"><?php $sum_reject=$total-$reject;echo '<span style="text-decoration:underline;border-bottom: 1px solid #000;">'.number_format($sum_reject,2).'</span>'?></td>
			<td style="width:18%;"></td>
		</tr>
	</table>
	<?php }//end if?>
	<?php }//end if i?>	
	<?php }//end for pm1-pm6?>
	<?php
	$reject2=0;
	$total2=0;
	$sum_reject2=0;
	$grand_total=0;
	$sql2="select * from sm_sales_forecast where month_visited='".$zero."'";
	$sql2 .=" and year_visited='".$year."'";
	$sql2 .=" and status_forecast='CR'";
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
	 }?>
	<table style="width:100%;font-size:0.7em;line-height:1.8em;margin:5% 0 0 0;display: none;" cellpadding="0" cellspacing="0">		
		<tr>
			<td colspan="6" style="border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000;width:8.5%;text-align:center;"></td>	
			<td style="border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:13%;padding:0.5%;">Total</td>
			<td style="border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:15%;text-align:right;padding:0.5%;"><?php echo number_format($sum_reject2,2)?></td>
			<td colspan="2" style="border-top:1px solid #000;border-right:1px solid #000;"></td>
		</tr>
		<tr>
			<td colspan="6" style="border-left:1px solid #000;border-right:1px solid #000;width:8.5%;text-align:center;"></td>	
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;padding:0.5%;">Vat7%</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:right;padding:0.5%;"><?php $vat=$sum_reject2*0.07;echo number_format($vat,2)?></td>
			<td colspan="2" style="border-right:1px solid #000;"></td>
		</tr>
		<tr>
			<td colspan="6" style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8.5%;text-align:center;"></td>	
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;padding:0.5%;">Grand Total</td>
			<td style="border-right:1px solid #000;border-bottom:1px solid #000;text-align:right;padding:0.5%;"><?php $grand_total=$vat+$sum_reject2;echo number_format($grand_total,2)?></td>
			<td colspan="2" style="border-bottom:1px solid #000;border-right:1px solid #000;"></td>
		</tr>
	</table>
	<table style="width:100%;font-size:0.8em;line-height:1.8em;margin:5% 0 0 0;" cellpadding="0" cellspacing="0">		
		<tr>
			<td style="width:10%;text-align:left;padding:2% 0.5% 0.5% 5%;">สรุปยอดสั้งซื้อทั้งหมด</td>	
			<td style="width:20%;padding:2% 0.5% 0.5% 0.5%;text-align:right;"><?php $sum=$grand_total-$reject2;echo number_format($grand_total,2)?></td>
			<td style="width:42%;text-align:left;padding:2% 0.5% 0.5% 0.5%;">บาท</td>
		</tr>
		<tr>
			<td style="width:10%;text-align:left;padding:0.5% 0.5% 0.5% 5%;">ยอดที่ถูกยกเลิก**</td>	
			<td style="padding:0.5%;text-align:right;"><?php echo number_format($reject2,2)?></td>
			<td style="text-align:left;padding:0.5%;">บาท</td>
		</tr>
		<tr>
			<td style="width:10%;text-align:left;padding:0.5% 0.5% 0.5% 5%;">หมายเหตุ</td>	
			<td colspan="2" style="padding:0.5%;text-align:left;"></td>
		</tr>
	</table>
	<table style="width:100%;font-size:0.8em;line-height:1.8em;margin:0 0 0 20%;" cellpadding="0" cellspacing="0">	
		<?php 
		$sql_target="select * from sm_sales_target where month_visited='".$zero."'";
		$sql_target .=" and year_visited='".$year."' and type_target='CR'";
		$res_target=mysql_query($sql_target) or die ('Error '.$sql_target);
		$rs_target=mysql_fetch_array($res_target);
		?>
		<tr>
			<td style="width:10%;text-align:left;padding:0.5% 0.5% 0.5% 0.5%;">เป้าหมาย</td>	
			<td style="width:20%;padding:0.5% 0.5% 0.5% 0.5%;text-align:right;"><?php echo number_format($rs_target['sales_target'],2)?></td>
			<td style="width:55%;text-align:left;padding:0.5% 0.5% 0.5% 0.5%;">บาท</td>
		</tr>
		<tr>
			<td style="width:10%;text-align:left;padding:0.5% 0.5% 0.5% 0.5%;">เปอร์เซ็นต์จากเป้า</td>	
			<td style="width:30%;padding:0.5%;text-align:right;"><?php $persent=(($grand_total*100)/$rs_target['sales_target']);echo number_format($persent,2)?></td>
			<td style="text-align:left;padding:0.5%;">%</td>
		</tr>
		<tr>
			<td style="width:10%;text-align:left;padding:0.5% 0.5% 0.5% 0.5%;">ยอดต่าง</td>	
			<td style="width:30%;padding:0.5%;text-align:right;"><?php $total_diff=$rs_target['sales_target']-$grand_total;echo number_format($total_diff,2)?></td>
			<td style="text-align:left;padding:0.5%;">บาท</td>
		</tr>
	</table>
	
	<table style="width:100%;font-size:0.9em;line-height:2.0em;margin:5% 0 0 0;" cellpadding="0" cellspacing="0">		
		<tr>
			<td style="text-align:right;padding:0.5% 5%;">
				Approved by.<br><br><br>
				...................................................................<br>
				(Ms.Pichchakarn Pitthayakornpisuth)<br>
				Chief Operating Officer<br>
				Date..............................................
			</td>	
		</tr>
	</table>
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4',0,'',10,10,34,5,5,5,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table cellspacing="0" cellpadding="0" style="border:1px solid #000;width:100%;"><tr>
			<td colspan="2" style="padding:2%;"><img src="img/logo.png" style="width:10%;"></td>
			<td colspan="7" style="vertical-align:bottom;padding:3% 2%;font-family:Arial;"><span style="font-size:1.5em;"><b>'.$po.'</b><br></td>
			</tr></table>
			<table style="border:1px solid #000;width:100%;" cellpadding="0" cellspacing="0" id="tb-quotation">
				<tr>
					<td style="text-align:center;font-size:1.8em;">Vat</td>
				</tr>
			</table>'
		);
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>