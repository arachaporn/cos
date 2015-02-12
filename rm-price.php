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
?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="en" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en" > <!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
<title>COS Project</title>
<link rel="stylesheet" href="css/foundation.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="rmm-css/responsivemobilemenu.css" type="text/css"/>
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" src="rmm-js/responsivemobilemenu.js"></script>
<script src="js/vendor/custom.modernizr.js"></script>
</head>
<body>
	<?php 
	include("menu.php");	
	include("paginator.php");

	$sql="select * from rm_price";
	$res=mysql_query($sql) or die('Error '.$sql);
	$num_row = mysql_num_rows($res);

	$Per_Page = 20;   // Per Page

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

	$sql .=" order  by id_rm_price desc LIMIT $Page_Start , $Per_Page";
	$res = mysql_query($sql) or die ('Error '.$sql);
	?>
	<div class="row">
		<div class="background">
			<form name="frmMain" action="del-rm-price.php" method="post">
			<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
				<tr>
					<td class="b-bottom" style="text-align:left;"><div class="large-4 columns"><a href="npd.php"><?php echo '< NPD'?></a></div></td>
					<td class="b-bottom"><div class="large-4 columns"><h4>RM Data</h4></div></td>
					<td class="b-bottom" style="text-align:right;">
						<div class="large-4 columns">
							<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-rm-price.php?id_u=New'" title="New">
							<input type="submit" name="del" value="Delete" class="btn-trash" OnClick="return chkdel();" title="Delete">
							<script language="JavaScript">
								function ckall(vol){					
									var i=1;
									for(i=1;i<=document.frmMain.hdnCount.value;i++){
										if(vol.checked == true){
											eval("document.frmMain.ck_del"+i+".checked=true");
										}
										else{
											eval("document.frmMain.ck_del"+i+".checked=false");
										}
									}
								}
							</script>
						</div>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;" colspan="3">
						<div class="large-4 columns">
							<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
								<tr class="top">
									<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
									<td width="3%" class="center">No.</td>
									<td width="10%" class="center">RM Code</td>
									<td width="30%" class="center">Name</td>
									<td width="10%" class="center">From</td>
									<td width="10%" class="center">Unit</td>
									<td width="10%" class="center">Price/Unit</td>
									<td width="10%" class="center">Standard Price</td>
								</tr>
								<?php
								$i=0;
								while($rs=mysql_fetch_array($res)){
									$i++;
								?>
								<tr>
									<script language="JavaScript">
										function chkdel(){
											if(confirm('Are you sure you want to delete?')){
												return true; // delete 
											}else{
												return false; // Cancel 
											}
										}
									</script>
									<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_rm_price'];?>"></td>
									<td class="center"><?php echo $i;?></td>
									<td><?php echo $rs['rm_code'];?></td>
									<td><a href="ac-rm-price.php?id_u=<?php echo $rs['id_rm_price']?>"><?php echo $rs['rm_name'];?></a></td>
									<td class="center">
										<?php
											switch($rs['rm_from']){
												case 1 : echo 'Factory';
												break;
												case 2 : echo 'CDIP';
												break;
											}
										?>
									</td>
									<td class="center"><?php echo $rs['unit'];?></td>
									<td class="center"><?php echo $rs['price_unit']?></td>
									<td class="center"><?php echo $rs['standard_price'];?></td>
								</tr>
								<?php } ?>
								<input type="hidden" name="hdnCount" value="<?=$i;?>">
							</table>
							</form>
						</div>
					</td>
				</tr>
				<tr>
					<td>
					<div class="large-4 columns">Total <?= $num_row;?> Record 
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
					</div>
					</td>
				</tr>
			</table>
		</div>
	</div>

  <script>
  document.write('<script src=' +
  ('__proto__' in {} ? 'js/vendor/zepto' : 'js/vendor/jquery') +
  '.js><\/script>')
  </script>
  
  <script src="js/foundation.min.js"></script>
  <!--
  
  <script src="js/foundation/foundation.js"></script>
  
  <script src="js/foundation/foundation.alerts.js"></script>
  
  <script src="js/foundation/foundation.clearing.js"></script>
  
  <script src="js/foundation/foundation.cookie.js"></script>
  
  <script src="js/foundation/foundation.dropdown.js"></script>
  
  <script src="js/foundation/foundation.forms.js"></script>
  
  <script src="js/foundation/foundation.joyride.js"></script>
  
  <script src="js/foundation/foundation.magellan.js"></script>
  
  <script src="js/foundation/foundation.orbit.js"></script>
  
  <script src="js/foundation/foundation.reveal.js"></script>
  
  <script src="js/foundation/foundation.section.js"></script>
  
  <script src="js/foundation/foundation.tooltips.js"></script>
  
  <script src="js/foundation/foundation.topbar.js"></script>
  
  <script src="js/foundation/foundation.interchange.js"></script>
  
  <script src="js/foundation/foundation.placeholder.js"></script>
  
  <script src="js/foundation/foundation.abide.js"></script>
  
  -->
  
  <script>
    $(document).foundation();
  </script>
</body>
</html>
