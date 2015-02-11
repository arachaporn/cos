<?php
include("connect/connect.php");

$date=date("Y-m-d");
$modify=date("Y-m-d H:i:s");
if($_POST['mode']=='New'){
	switch($_POST['type_product']){
		case 1 : 
				$sql="insert into moh_profit(id_type_product,weight_per_product,appea_size,moh";
				$sql .=",loss,profit_rate,moq,create_by,create_date) values ";
				$sql .="('".$_POST['type_product']."','".$_POST['weight']."','".$_POST['appearance']."'";
				$sql .=",'".$_POST['moh']."','".$_POST['loss']."','".$_POST['profit_rate']."'";
				$sql .=",'".$_POST['moq']."','admin','".$date."')";
				$res=mysql_query($sql) or die ('Error '.$sql);
		break;
	}
	?>
	<script>
		window.location.href='hq-profit.php';
	</script>
<?php 
}else{
	switch($_POST['type_product']){
		case 1 : 
				$sql="update moh_profit set weight_per_product='".$_POST['weight']."'";
				$sql .=",appea_size='".$_POST['appearance']."',moh='".$_POST['moh']."'";
				$sql .=",loss='".$_POST['loss']."',profit_rate='".$_POST['profit_rate']."'";
				$sql .=",moq='".$_POST['moq']."',moq='".$_POST['moq']."'";
				$sql .=",modify_date='".$modify."'";
				$sql .=" where id_moh_profit='".$_POST['mode']."'";
				$res=mysql_query($sql) or die ('Error '.$sql);
		break;
	}
?>
	<script>
		window.location.href='hq-profit.php';
	</script>
<?php }?>