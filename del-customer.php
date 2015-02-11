<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){
			
			$sql="select * from company where id_company='".$_POST['ck_del'][$i]."'";
			$res=mysql_query($sql) or die ('Error '.$sql);
			$rs=mysql_fetch_array($res) or die ('Error '.$sql);
			$address=$rs['id_address'];

			$sql_del ="delete from company where id_company='".$_POST['ck_del'][$i]."'";
			$res_del = mysql_query($sql_del) or die ('Error '.$sql_del);

			$sql_address="delete from company_address where id_address='".$address."'";
			$res_address=mysql_query($sql_address) or die ('Error '.$sql_address);

			$sql_contact="delete from company_contact where id_company='".$_POST['ck_del'][$i]."'";
			$res_contact=mysql_query($sql_contact) or die ('Error '.$sql_contact);

			$sql_file="delete from company_file where id_company='".$_POST['ck_del'][$i]."'";
			$res_file=mysql_query($sql_file) or die ('Error '.$sql_file);

			$sql_approve="delete from company_approve where id_company='".$_POST['ck_del'][$i]."'";
			$res_approve=mysql_query($sql_approve) or die ('Error '.$sql_approve);
		}
	}//end for
	if($_POST['open-customer']==1){
	?>
	<script type='text/javascript'>
		window.location.href = "open-customer.php";
	</script>
	<?php }else{ ?>
	<script type='text/javascript'>
		window.location.href = "customer.php";
	</script>
	<?php }
}//end delete
?>