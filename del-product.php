<?php
include("connect/connect.php");
if($_POST['del']=='Delete'){
	for($i=0;$i<count($_POST['ck_del']);$i++){
		if($_POST['ck_del'][$i] != ""){

			$sql="select * from product_detail where id_product_detail='".$_POST['ck_del'][$i]."'";
			$res=mysql_query($sql) or die ('Error '.$sql);
			$rs=mysql_fetch_array($res);
			
			$id_product_factory=$rs['id_product_factory'];
			$id_packing_factory=$rs['id_packing_factory'];
			$id_product_rm=$rs['id_product_rm'];
			$id_product_box=$rs['id_product_box'];
			$id_product_bottle=$rs['id_product_bottle'];
			$id_product_sticker=$rs['id_product_sticker'];
			$id_product_alu=$rs['id_product_alu'];
			$id_product_foil=$rs['id_product_foil'];
			$id_product_other=$rs['id_product_other'];
			
			$sql_factory="delete from product_factory where id_product_factory='".$id_product_factory."'";
			$res_factory=mysql_query($sql_factory) or die ('Error '.$sql_factory);

			$sql_packing="delete from  product_packing where id_packing_factory='".$id_packing_factory."'";
			$res_packing=mysql_query($sql_packing) or die ('Error '.$sql_packing);

			$sql_rm="delete from product_rm where id_product_rm='".$id_product_rm."'";
			$res_rm=mysql_query($sql_rm) or die ('Error '.$sql_rm);

			$sql_box="delete from product_box where id_product_box='".$id_product_box."'";
			$res_box=mysql_query($sql_box) or die ('Error '.$sql_box);

			$sql_bottle="delete from product_bottle where id_product_bottle='".$id_product_bottle."'";
			$res_bottle=mysql_query($sql_bottle) or die ('Error '.$sql_bottle);

			$sql_sticker="delete from product_sticker where id_product_sticker='".$id_product_sticker."'";
			$res_sticker=mysql_query($sql_sticker) or die ('Error '.$sql_sticker);

			$sql_alu="delete from product_alu where id_product_alu='".$id_product_alu."'";
			$res_alu=mysql_query($sql_alu) or die ('Error '.$sql_alu);

			$sql_foil="delete from product_foil where id_product_foil='".$id_product_foil."'";
			$res_foil=mysql_query($sql_foil) or die ('Error '.$sql_foil);

			$sql_other="delete from product_other where id_product_other='".$id_product_other."'";
			$res_other=mysql_query($sql_other) or die ('Error '.$sql_other);

			$sql_del ="delete from product_detail where id_product_detail='".$_POST['ck_del'][$i]."'";
			$res_del = mysql_query($sql_del) or die ('Error '.$sql_del);

		}
	}//end for
	?>
	<script type='text/javascript'>
		window.location.href = "product.php";
	</script>
<?php }//end delete
?>