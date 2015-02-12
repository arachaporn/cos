<?php
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
$_SESSION["id_company"]=$_REQUEST['id_company'];
$_SESSION['company_name']=$_REQUEST['company_name'];

//create pdf
include("mpdf/mpdf.php");
ob_start();
?>
<!DOCTYPE html>
<body>
	<?php
	$id=$_GET["id_u"];
	$sql="select * from npd_project_evaluation where id_project_eva='".$id."'";
	$res=mysql_query($sql) or die ('Error '.$sql);
	$rs=mysql_fetch_array($res);

	$sql_roc="select * from roc where id_roc='".$rs['id_roc']."'";
	$res_roc=mysql_query($sql_roc) or die ('Error '.$sql_roc);
	$rs_roc=mysql_fetch_array($res_roc);

	$sql_product="select * from product where id_product='".$rs_roc['id_product']."'";
	$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
	$rs_product=mysql_fetch_array($res_product);

	$sql_company="select * from company where id_company='".$rs_roc['id_company']."'";
	$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
	$rs_company=mysql_fetch_array($res_company);

	$sql_contact="select * from company_contact where id_contact='".$rs_roc['id_contact']."'";
	$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);
	$rs_contact=mysql_fetch_array($res_contact);
	?>
	<table style="width:100%;line-height: 1.2em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="6" style="padding:1% 0 0 0;font-weight:bolde;font-size:1.5em;text-align:center;">Rawmaterail Cost</td>
		</tr>
		<tr>
			<td style="vertical-align:top;font-size:0.9em;width:10%;">Product name :</td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;width:23%;"><?php echo $rs_product['product_name']?></td>
			<td></td>
			<td></td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;width:15%;">REQ No:</td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;"><?php echo $rs_roc['roc_code']?></td>
		</tr>
		<tr>
			<td style="vertical-align:top;font-size:0.9em;padding:1% 0;">Customer :</td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;padding:1% 0;width:30%;"><?php if($rs_company['company_name'] != '' ){echo $rs_company['company_name'];}elseif($rs_contact['contact_name'] !=''){echo $rs_contact['contact_name'];}?></td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;width:5%;padding:1% 0;">Unit:</td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;width:15%;padding:1% 0;"><?php echo $rs['npd_total'].'mg/'.$rs['npd_unit']?></td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;width:5%;padding:1% 0;">Date:</td>
			<td style="vertical-align:top;font-size:0.9em;text-align:left;padding:1% 0;"><?php echo $rs['create_date']?></td>
		</tr>  
		<tr>
			<td colspan="6">
				<table style="width: 100%;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-left:1px solid #000;border-right:1px solid #000;border-top:1px solid #000;border-bottom:1px solid #000;width:5%;background:#92E7EE;">No.</td>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:30%;background:#92E7EE;">Ingredients</td>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8%;background:#92E7EE;">R/M Yield<br>%</td>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8%;background:#92E7EE;">Price/Unit<br>R/M Cost ฿/Kg.</td>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8%;background:#92E7EE;">Formula Used<br>(mg)</td>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8%;background:#92E7EE;">Formula<br>%</td>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8%;background:#92E7EE;">Quantity<br>g./unit</td>
						<td style="text-align:center;vertical-align:middle;font-size:1em;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;width:8%;background:#92E7EE;">Cost of Product<br>฿/unit</td>
					</tr>
					<?php
					$i=0;
					$sum_formula=0;
					$sum_formula_use=0;
					$total_formula_use=0;
					$total_formula=0;
					$sum_product=0;
					$a=0;
					$total_product=0;
					$sql_npd_rm="select * from npd_project_relation where id_roc='".$rs_roc['id_roc']."'";
					$res_npd_rm=mysql_query($sql_npd_rm) or die('Error '.$sql_npd_rm);
					$num_rm=mysql_num_rows($res_npd_rm);
					while($rs_npd_rm=mysql_fetch_array($res_npd_rm)){	
						$i++;
						$sum_formula_use=($rs_npd_rm['npd_rm_quantity']/$rs['npd_total'])*100;
						$total_formula_use=$total_formula_use+$sum_formula_use;
														
						$sum_formula=$rs_npd_rm['npd_rm_quantity']/1000;
						$total_formula=$total_formula+$sum_formula;

						$sum_product=($sum_formula*$rs_npd_rm['npd_rm_price'])/1000;
						$total_product=$total_product+$sum_product;
					?>
					<tr>
						<td style="text-align:center;font-size:0.9em;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo $i?></td>
						<td style="font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo $rs_npd_rm['npd_rm_name']?></td>
						<td style="text-align:center;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;">99</td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($rs_npd_rm['npd_rm_price'],2)?></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($rs_npd_rm['npd_rm_quantity'],3)?></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($sum_formula_use,3)?></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($sum_formula,3)?></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($sum_product,4)?></td>
					</tr>
					<?php }?>
					<?php $a=16-$num_rm;?>	
					<?php for($j=0;$j<=$a;$j++){
						echo '<tr>';
						echo '<td style="padding:1%;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<tr>';}?>
					<tr>
						<td style="text-align:center;font-size:0.9em;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;">Recommended dose = <?php echo $rs['how_use']?></td>
						<td style="text-align:center;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
					</tr>
					<tr>
						<td style="text-align:center;font-size:1em;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;">Selling point = <?php if($rs['selling_point']==''){echo '............................';}else{echo $rs['selling_point'];}?></td>
						<td style="text-align:center;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>	
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
					</tr>
					<tr>
						<td style="text-align:center;font-size:0.9em;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;" colspan="2">Total</td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($rs['npd_total'],3)?></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_formula_use,3)?></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_formula,3)?></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($total_product,4)?></td>
					</tr>
					<tr>
						<td style="text-align:left;font-size:0.9em;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;" colspan="2">Weight after cooked</td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;background:#818181;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
					</tr>
					<tr>
						<td style="text-align:left;font-size:0.9em;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;" colspan="2">%Yield Finished Product</td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;background:#3F94F1;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;background:#3F94F1;"></td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;background:#3F94F1;"></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;background:#3F94F1;font-weight:bold;">95.00</td>
						<td style="text-align:center;font-size:1em;border-right:1px solid #000;border-bottom:1px solid #000;background:#3F94F1;"></td>
						<td style="text-align:right;font-size:0.9em;border-right:1px solid #000;border-bottom:1px solid #000;background:#3F94F1;font-weight:bold;"><?php echo number_format(($total_product*100)/95,4)?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="6" style="font-size:1.1em;text-align:center;padding-left:60%;">จัดทำโดย <br>............NPD Team...........</td>
		</tr>
	</table>	
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4-L',0,'',13,13,32,3,3,3,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table style="width:100%;line-height:1.3em;" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="6" style="font-size:0.8em;text-align:right;">FR-NPD-RD-01 Rev.00 Effective date: 27/06/14</td>
			</tr>
			<tr>
			<td colspan="2" style="padding-bottom: 1%;border-bottom:1px solid #000;"><img src="img/logo.png" style="width:12%;"></td>
			<td colspan="4" style="text-align:center;padding-right:15%;vertical-align:bottom;padding-bottom:1%;font-family:Arial;font-size:0.9em;border-bottom:1px solid #000;">บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
			CDIP (Thailand) Co.,Ltd.<br>
			131 อาคารกลุ่มนวัตกรรม 1 ห้อง 227 อุทยานวิทยาศาสตร์แห่งประเทศไทย <br>
			ถ.พหลโยธิน ต.คลองหนึ่ง อ.คลองหลวง จ.ปทุมธานี 12120
			</td>
		</tr></table>');
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>