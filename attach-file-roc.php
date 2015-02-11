<?php
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("connect/connect.php");
include("paginator.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
//$id_com=substr($_REQUEST['id_com'], 2);
$id_roc=$_REQUEST['id_u'];
if($_GET["action"] == "del"){
	$id=$_GET['id_file'];
	$sql = "delete from roc_file ";
	$sql .="where id_roc_file = '".$_GET["id_u"]."'";
	$res = mysql_query($sql) or die ('Error '.$sql);
?>
	<script>
		window.location.href='attach-file-roc.php?id_u=<?=$id?>';
	</script>

<?php }?>
<html>
<head>
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/style.css">
<script language="javascript">
function fncSubmit()
{
	document.frm.submit();
}
</script>
</head>
<body>
	<div style="margin: 2%;">
		<form name="frm" method="post" action="dbattach-file-roc.php" enctype="multipart/form-data">
			<input type="hidden" name="id_roc" value="<?php echo $_REQUEST['id_u']?>">
			<input type="file" name="filUpload"><input name="btnSubmit" type="button" value="Upload file" OnClick="JavaScript:return fncSubmit();" class="button-create">
			<?php
			$id=substr($_REQUEST['id_u'], -2);
			$sql="select * from roc_file where id_roc='".$id."'";
			$res=mysql_query($sql) or die ('Error '.$sql);
			$num_row = mysql_num_rows($res);
			$Per_Page = 10;   // Per Page

			$Page = $_GET["Page"];
			if(!$_GET["Page"]){
				$Page=1;
			}

			$Prev_Page = $Page-1;
			$Next_Page = $Page+1;

			$Page_Start = (($Per_Page*$Page)-$Per_Page);
			if($num_row<=$Per_Page){
				$Num_Pages =1;
			}
			else if(($num_row % $Per_Page)==0){
				$Num_Pages =($num_row/$Per_Page) ;
			}
			else{
				$Num_Pages =($num_row/$Per_Page)+1;
				$Num_Pages = (int)$Num_Pages;
			}

			$sql .=" order by id_roc_file desc LIMIT $Page_Start , $Per_Page";
			$res=mysql_query($sql) or die ('Error '.$sql);			
			?>	
			<table style="width: 50%; margin-top: 2%;" cellpadding="0" cellspacing="0">
				<tr>
					<td style="border:1px solid #eee;padding: 0.5%;text-align:center;font-weight:bold;">No.</td>
					<td style="border-top:1px solid #eee;border-bottom:1px solid #eee;border-right:1px solid #eee;padding: 0.5%;text-align:center;font-weight:bold;">File name</td>
				</tr>
			<?php 
			$i=0;
			while($rs=mysql_fetch_array($res)){
				$i++;
				if($rs['roc_file'] != ''){
			?>	
				<tr>
					<td style="border-bottom:1px solid #eee;border-left:1px solid #eee;border-right:1px solid #eee;padding: 0.5%;text-align:center;"><?php echo $i?></td>
					<td style="border-bottom:1px solid #eee;border-right:1px solid #eee;padding: 1%;"><?php echo $rs['roc_file']?><span style="margin-left:2%;"><a href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='<?=$_SERVER["PHP_SELF"];?>?id_u=<?php echo $rs['id_roc_file'] ?>&action=del&id_file=<?php echo $id?>';}"><img src="img/delete.png" style="width:15px;" title="Delete"></a></span></td>
				</tr>
			<?php } ?>
			<?php }?>
			</table>			
			Total <?= $num_row;?> Record 
				<?php
				$pages = new Paginator;
				$pages->items_total = $num_row;
				$pages->mid_range = 10;
				$pages->current_page = $Page;
				$pages->default_ipp = $Per_Page;
				$pages->url_next = $_SERVER["PHP_SELF"]."?QueryString=value&Page=";

				$pages->paginate();
				echo $pages->display_pages()
				?>	
		</form>
	</div>
</body>
</html>
