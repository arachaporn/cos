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
	<table style="width:100%;text-align:left;" cellpadding="0" cellspacing="0">
		<!--<tr>
			<td style="width:10%;"></td>
			<td style="width:45%;"></td>
			<td colspan="2" style="font-size:0.9em;width:10%;padding-top: 1.5%;">เลขที่เอกสาร<span style="border-bottom: 1px dotted #000;display: inline;"><?php echo '&nbsp;&nbsp;'.$rs['npd_code'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'?></span></td>
		</tr>-->
		<tr>
			<td style="width:10%;border-left: 1px solid #000;"></td>
			<td style="width:45%;"></td>
			<td style="font-size:1em;width:15%;padding: 4% 2% 0 0;">เลขที่เอกสารอ้างอิง</td>
			<td style="font-size:1em;width:15%;padding: 4% 2% 0 0;border-right: 1px solid #000;"><?php echo $rs_roc['roc_code']?></td>
		</tr>
		<tr>
			<td style="font-size:1em;width:20%;padding: 1% 0 1% 2%;border-left: 1px solid #000;">Project name :</td>
			<td colspan="3" style="font-size:1em;text-align:left;padding:1% 0;border-right: 1px solid #000;"><?php echo $rs_product['product_name']?></td>
		</tr>
		<tr>
			<td style="font-size:1em;padding:0.5% 0 0 2%;border-left: 1px solid #000;">ชื่อลูกค้า/บริษัท :</td>
			<td colspan="3" style="font-size:1em;text-align:left;padding:0.5% 0 0;border-right: 1px solid #000;"><?php if($rs_company['company_name'] != '' ){echo $rs_company['company_name'];}elseif($rs_contact['contact_name'] !=''){echo $rs_contact['contact_name'];}?></td>
		</tr>  
		<tr>
			<td colspan="4" style="border-right:1px solid #000;border-left:1px solid #000;border-bottom:1px solid #000;">
				<table style="width: 100%;padding:2% 2% 0.5% 2%;" cellpadding="0" cellspacing="0">
					<tr>
						<td rowspan="2" style="font-size:0.9em;padding:1% 0.5%;width:15%;text-align:center;border-top:1px solid #000;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">ลำดับ</td>
						<td colspan="3" style="font-size:0.9em;padding:1% 0.5%;text-align:center;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">ส่วนประกอบ</td>
						<td colspan="3" style="font-size:0.9em;padding:1% 0.5%;text-align:center;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">เทียบเท่ากับ (Equivalent Active)</td>
						<td rowspan="2" style="font-size:0.9em;padding:1% 0.5%;width:10%;text-align:center;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">ผู้ขาย</td>
						<td rowspan="2" style="font-size:0.9em;padding:1% 0.5%;width:10%;text-align:center;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">ราคาวัตถุดิบ<br>ต่อกิโลกรัม</td>
					</tr>
					<tr>
						<td style="font-size:0.9em;padding:1% 0.5%;width:15%;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">สารสำคัญ</td>
						<td style="font-size:0.9em;padding:1% 0.5%;width:10%;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">ปริมาณ</td>
						<td style="font-size:0.9em;padding:1% 0.5%;width:5%;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">หน่วย</td>
						<td style="font-size:0.9em;padding:1% 0.5%;width:15%;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">สารสำคัญ</td>
						<td style="font-size:0.9em;padding:1% 0.5%;width:10%;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">ปริมาณ</td>
						<td style="font-size:0.9em;padding:1% 0.5%;width:5%;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">หน่วย</td>
					</tr>
					<?php 
					$i=0;
					$a=0;
					$sql_npd_rm="select * from npd_project_relation where id_roc='".$rs_roc['id_roc']."'";
					$res_npd_rm=mysql_query($sql_npd_rm) or die('Error '.$sql_npd_rm);
					$num_rm=mysql_num_rows($res_npd_rm);
					while($rs_npd_rm=mysql_fetch_array($res_npd_rm)){	
						$i++;											
					?>
					<tr>
						<td style="font-size:0.9em;padding:1% 0.5%;text-align:center;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo $i?></td>
						<td style="font-size:0.9em;padding:1% 0.5%;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo $rs_npd_rm['npd_rm_name']?></td>
						<td style="font-size:0.9em;padding:1% 0.5%;text-align:right;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($rs_npd_rm['npd_rm_quantity'],2)?></td>
						<td style="font-size:0.9em;padding:1% 0.5%;text-align:center;border-right:1px solid #000;border-bottom:1px solid #000;">mg</td>
						<td style="font-size:0.9em;padding:1% 0.5%;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo $rs_npd_rm['npd_rm_equl']?></td>
						<td style="font-size:0.9em;text-align:right;padding:1% 0.5%;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($rs_npd_rm['npd_rm_quantity_equl'],2)?></td>
						<td style="font-size:0.9em;text-align:center;padding:1% 0.5%;border-right:1px solid #000;border-bottom:1px solid #000;">mg</td>
						<td style="font-size:0.9em;text-align:right;padding:1% 0.5%;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo $rs_npd_rm['npd_supplier']?></td>
						<td style="font-size:0.9em;text-align:right;padding:1% 0.5%;border-right:1px solid #000;border-bottom:1px solid #000;"><?php echo number_format($rs_npd_rm['npd_rm_price'],2)?></td>
					</tr>					
					<?php }?>
					<?php $a=21-$num_rm;?>	
					<?php for($j=0;$j<=$a;$j++){
						echo '<tr>';
						echo '<td style="padding:2.5%;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:2.5%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:2.5%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:2.5%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:2.5%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:2%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<td style="padding:1%;border-right:1px solid #000;border-bottom:1px solid #000;"></td>';
						echo '<tr>';}?>
					<tr>
						<td colspan="4" style="font-size:0.9em;text-align:center;padding:1% 0;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">น้ำหนักสุทธิต่อหน่วย</td>
						<td colspan="5" style="font-size:0.9em;text-align:center;padding:1% 0;border-right:1px solid #000;border-bottom:1px solid #000;background:#F8E9BB;"><?php echo number_format($rs['npd_total'],2)?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table><br><br><br><br>
	<table style="width:100%;line-height:1.5em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:10%;font-size:1em;vertical-align:top;padding:4% 0 0 2%;border-left:1px solid #000;">รูปแบบของผลิตภัณฑ์</td>
			<td colspan="3" style="font-size:1em;vertical-align:top;padding:4% 0 0 2%;width:30%;border-right:1px solid #000;padding:4% 0 0;"><?php echo $rs['product_app_rd']?>
			</td>
		</tr>
		<tr>
			<td style="width:10%;font-size:1em;padding:1% 0 0 2%;border-left:1px solid #000;">วิธีรับประทาน</td>
			<td style="width:20%;font-size:1em;padding:1% 0 0 0;"><?php echo $rs['how_use']?></td>
			<td style="width:15%;font-size:1em;padding:1% 0 0 0;">อายุการเก็บรักษา</td>
			<td style="width:40%;font-size:1em;padding:1% 2% 0 0;border-right:1px solid #000;"><?php echo $rs['storage']?></td>
		</tr>	
		<tr>
			<td style="font-size:1em;padding:1% 0 0 2%;border-left:1px solid #000;">Manufacturer ระบุ</td>
			<td colspan="3" style="font-size:1em;padding:1% 0 0 0;border-right:1px solid #000;">
				<?php 
				$sql_factory="select * from type_manufactory where id_manufacturer='".$rs['id_manufacturer']."'";
				$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
				$rs_factory=mysql_fetch_array($res_factory);
				echo $rs_factory['title'];
				?>
			</td>
		</tr>
		<tr>
			<td style="font-size:1em;padding:1% 0 0 2%;border-left:1px solid #000;">Pack ระบุ</td>
			<td colspan="3" style="font-size:1em;padding:1% 0 0 0;border-right:1px solid #000;">
				<?php 
				$sql_factory="select * from type_manufactory where id_manufacturer='".$rs['id_pack']."'";
				$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);
				$rs_factory=mysql_fetch_array($res_factory);
				echo $rs_factory['title'];
				?>
			</td>
		</tr>
		<tr>
			<td style="font-size:1em;padding:1% 0 0 2%;border-left:1px solid #000;">เจ้าหน้าที่ RD</td>
			<td colspan="3" style="font-size:1em;padding:1% 0 0 0;border-right:1px solid #000;">
				<?php
				$sql_acc="select * from account where id_account='".$rs['rd_account']."'";
				$res_acc=mysql_query($sql_acc) or die ('Error '.$sql_acc);
				$rs_acc=mysql_fetch_array($res_acc);
				echo $rs_acc['name'];
				?>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1em;padding:1% 0 0 2%;border-left:1px solid #000;border-right:1px solid #000;">ประเภทการขึ้นทะเบียน</td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1em;padding:1% 0 0 5%;border-left:1px solid #000;border-right:1px solid #000;">
				<?php
				$sql_npd_type_product="select * from npd_type_product";
				$res_npd_type_product=mysql_query($sql_npd_type_product) or die ('Error '.$sql_npd_type_product);
				while($rs_npd_type_product=mysql_fetch_array($res_npd_type_product)){
				?>
				<input type="checkbox" <?php if($rs['type_fda']==$rs_npd_type_product['id_npd_type_product']){echo 'checked="checked"';}?>><?php echo $rs_npd_type_product['npd_title'].'&nbsp;&nbsp;&nbsp;'?>
				<?php }?>
				<input type="checkbox" <?php if($rs['type_fda']==0){echo 'checked="checked"';}?>><?php echo 'อื่น ๆระบุ&nbsp;&nbsp;';if($rs['other_fda']==''){echo '.............';}else{echo $rs['other_fda'];}?>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1em;padding:1% 0 0 2%;border-left:1px solid #000;border-right:1px solid #000;">ข้อเสนอแนะเพิ่มเติม</td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1.0em;padding:1% 0 0 2%;border-left:1px solid #000;border-right:1px solid #000;"><?php echo $rs['description']?></td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1.0em;padding:3.5% 0 0 2%;border-left:1px solid #000;border-right:1px solid #000;"></td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1.0em;padding:3.5% 0 0 2%;border-left:1px solid #000;border-right:1px solid #000;"></td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1.0em;padding:3.5% 0 0 2%;border-left:1px solid #000;border-right:1px solid #000;"></td>
		</tr>
		<tr>
			<td colspan="4" style="font-size:1.0em;padding:3.5% 0 0 2%;border-left:1px solid #000;border-right:1px solid #000;"></td>
		</tr>
	</table>
	<table style="width:100%;padding:50% 0 0 0;border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;" cellpadding="0" cellspacing="0">
		<tr>
			<td style="font-size:1em;text-align:center;border-top:1px solid #000;padding:2% 0 3%;">ผู้ร้องขอ<br><br><br><br><br>................................................<br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>______/______/______</td>
			<td style="font-size:1em;text-align:center;border-top:1px solid #000;padding:2% 0 3%;">ผู้ทบทวน<br><br><br><br><br>................................................<br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>______/______/______</td>
			<td style="font-size:1em;text-align:center;border-top:1px solid #000;padding:2% 0 3%;">ผู้อนุมัติ<br><br><br><br><br>................................................<br>(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)<br>______/______/______</td>
		</tr>
	</table>
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4',0,'',15,15,32,4,4,4,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table style="width:100%;line-height:1.6em;" cellspacing="0" cellpadding="0">
			<tr>	
				<td colspan="6" style="font-size:0.8em;text-align:right;">FR-NPD-RD-03 Rev.00 Effective date: 27/06/14</td>
			</tr>
			<tr>
			<td colspan="2" style="border-top:1px solid #000;border-left:1px solid #000;border-bottom:1px solid #000;padding:1% 2%;"><img src="img/logo.png" style="width:20%;"></td>
			<td colspan="4" style="text-align:center;font-size:1.0em;vertical-align:bottom;padding-bottom:2%;font-family:Arial;padding-right:15%;border-top:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">
			บริษัท ซีดีไอพี (ประเทศไทย) จำกัด<br>
			CDIP (Thailand) Co.,Ltd.<br>
			แบบฟอร์มการตั้งสูตรผลิตภัณฑ์ (Project Evaluation)
			</td>
		</tr></table>');
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>