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
	<?php include("menu.php");
	include("paginator.php");
	if($rs_account['role_user']==3){
		$where=" where create_by='".$rs_account['id_account']."'";
	}
	else{ $where="";}
		
	$sql="select * from quotation";
	$sql.= $where;
	
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

	$sql .=" order by id_quotation desc LIMIT $Page_Start , $Per_Page";
	$res = mysql_query($sql) or die ('Error '.$sql);
	?>
	<div class="row">
		<div class="background">
		<form name="frmMain" action="del-quotation.php" method="post">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom" style="text-align:left;width:42%;">
					<div class="large-4 columns"><a href="sales-marketing.php"><?php echo '< New Business Development'?></a></div>	
				</td>
				<td class="b-bottom"><div class="large-4 columns"><h4>Pre Quotation</h4></div></td>
				<td class="b-bottom" style="text-align:right;">
					<div class="large-4 columns">
						<input type="button" value="Create" class="btn-new" onclick="window.location.href='ac-quotation.php?id_u=New'" title="New">
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
				<td style="background: #fff;" colspan='3'>
					<div class="large-4 columns">
						<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%"class="center"><input type="checkbox" name="ck_all" id="ck_all" value="Y" onclick="ckall(this);" style="margin:0;padding:0;"></td>
								<td width="3%">No.</td>
								<td width="8%">Quotation no</td>
								<td width="20%">Product name</td>
								<td width="20%">Company name</td>
								<td width="8%">Create date</td>
								<?php if($rs_account['role_user']!=3){?><td width="8%">Create by</td><?php }?>
							</tr>
							<?php
							$i=0;
							while($rs=mysql_fetch_array($res)){
								$i++;
								//echo $rs['rev'];
								if($rs['rev']>0){$rev='(Rev.'.$rs['rev'].')';}
							?>
							<tr class="row-top">
								<script language="JavaScript">
									function chkdel(){
										if(confirm('Are you sure you want to delete?')){
											return true; // delete 
										}else{
											return false; // Cancel 
										}
									}
								</script>
								<td class="center"><input type="checkbox" name="ck_del[]" id="ck_del<?php echo $i?>" value="<?php echo $rs['id_quotation'];?>"></td>
								<td style="line-height: 2em;"><?php echo $i;?></td>
								<td style="line-height: 2em;"><a href="ac-quotation.php?id_u=<?php echo $rs['id_quotation']?>"><?php echo $rs['quotation_no'].$rev?></a></td>
								<td style="line-height: 2em;">
								<?php 
								$sql_product="select * from product where id_product='".$rs['id_product']."'";
								$res_product=mysql_query($sql_product) or die ('Error '.$sql_product);
								$rs_product=mysql_fetch_array($res_product);
								echo $rs_product['product_name'];
								?>
								</td>
								<td style="line-height: 2em;">
								<?php 
								if($rs['comapany_name']==''){
									$sql_company="select * from company where id_company='".$rs['id_company']."'";
									$res_company=mysql_query($sql_company) or die ('Error '.$sql_company);
									$rs_company=mysql_fetch_array($res_company);
									echo $rs_company['company_name'];	
								}else{echo $rs['company_name'];}
								?>
								</td>		
								<td style="line-height: 2em;"><?php echo $rs['create_date']?></td>								
								<?php if($rs_account['role_user']!=3){?>
								<td style="line-height: 2em;">
									<?php
									$sql_account2="select * from account where id_account='".$rs['create_by']."'";
									$res_account2=mysql_query($sql_account2) or die('Error '.$sql_account2);
									$rs_account2=mysql_fetch_array($res_account2);
									echo $rs_account2['name'];
									?>
								</td>
								<?php }?>
							</tr>
							<?php } ?>
							<input type="hidden" name="hdnCount" value="<?=$i;?>">
						</table>
					</div>
				</td>
			</tr>
		</table>
		</form>
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
