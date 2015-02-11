<?php
include("connect/connect.php");
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
	<?php include("menu.php");?>
	<div class="row">
		<div class="background">
		<table style="border: 0; width: 100%;" cellpadding="0" cellspacing="0">
			<tr>
				<td class="b-bottom"><div class="large-4 columns"><h4>Sample Request</h4></div></td>
			</tr>
			<tr>
				<td style="background: #fff;">
					<div class="large-4 columns">
						<input type="button" value="Create" class="button-create" onclick="window.location.href='ac-req-rd.php?id_u=new&p=1'">
						<table style="border: 1 solid #eee; width: 100%;" cellpadding="0" cellspacing="0">
							<tr class="top">
								<td width="2%"class="center"><input type="checkbox" value="all[]" style="margin:0;padding:0;"></td>
								<td width="3%">No.</td>
								<td width="10%">Company Name</td>
								<td width="10%">Contact Name</td>
								<td width="10%" class="center">Company Category</td>
								<td width="10%" class="center">Create by</td>
								<td width="10%" class="center">Create Date</td>
								<td width="10%" class="center">Modify Date</td>
							</tr>
							<?php
							$i=0;
							$sql="select * from account";
							$res=mysql_query($sql) or die('Error '.$sql);
							while($rs=mysql_fetch_array($res)){
								$i++;
							?>
							<tr>
								<td class="center"><input type="checkbox" value="all[]" style="margin:0;padding:0;"></td>
								<td><?php echo $i;?></td>
								<td><a href="ac-account.php?id_u=<?php echo $rs['id_account']?>"><?php echo $rs['username'];?></td>
								<td><?php echo $rs['name'];?></td>
								<td class="center"><?php echo $rs['email'];?></td>
								<td class="center"><?php echo $rs['create_by'];?></td>
								<td class="center"><?php echo $rs['create_date'];?></td>
								<td class="center"><?php echo $rs['modify_date'];?></td>
							</tr>
							<?php } ?>
						</table>
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
