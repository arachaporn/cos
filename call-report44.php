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
<script type="text/javascript" src="js/jquery/js/jquery-1.4.2.min.js"></script> 
<script type="text/javascript" src="js/jquery/js/jquery-ui-1.8.2.custom.min.js"></script> 
<script type="text/javascript"> 
	jQuery(document).ready(function(){
		$('.company_name').autocomplete({
			source:'return-company.php', 
			//minLength:2,
			select:function(evt, ui)
			{
				// when a zipcode is selected, populate related fields in this form
				this.form.id_company.value = ui.item.id_company;
				this.form.company_address.value = ui.item.company_address;
				this.form.company_contact.value = ui.item.company_contact;
				this.form.company_email.value = ui.item.company_email;
			}
		});
	});
</script> 
<link rel="stylesheet" href="js/jquery/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
</head>
<body>
	<?php
	function func_conv_status( $input ){
		$EngMonth = array( "01" => "January", "02" => "February", "03" => "March", "04" => "April",
							"05" => "May","06" => "June", "07" => "July", "08" => "August",
							"09" => "September", "10" => "October", "11" => "November", "12" => "December");
		return $EngMonth[ $input ] ;
	}
	$sql="select * from month inner join call_report_relation_month";
	$sql .=" on month.id_month=call_report_relation_month.id_month";
	$sql .=" group by month.id_month order by call_report_relation_month.call_year desc";
	$res=mysql_query($sql) or die ('Error '.$sql);
	while($rs=mysql_fetch_array($res)){
		echo $rs['title_month'].'&nbsp;'; 
		$sql2="select * from call_report_relation_month inner join call_report";
		$sql2 .=" on call_report_relation_month.id_month='".$rs['id_month']."'";
		$sql2 .=" and call_report_relation_month.id_call_report=call_report.id_call_report";
		$res2=mysql_query($sql2) or die ('Error '.$sql2);
		while($rs2=mysql_fetch_array($res2)){		
			echo $rs2['title_call_report'].'&nbsp;';
			$sql3="select * from company where id_company='".$rs2['id_company']."'";
			$res3=mysql_query($sql3) or die ('Error '.$sql3);
			$rs3=mysql_fetch_array($res3);
			echo $rs3['company_name'].'&nbsp;';
			$sql4="select * from product where id_product='".$rs2['id_product']."'";
			$res4=mysql_query($sql4) or die ('Error '.$sql4);
			$rs4=mysql_fetch_array($res4);
			echo $rs4['product_name'].'&nbsp;';	
			echo '<br>';
			
		}
		echo $rs['create_by'].'&nbsp;';
		echo $rs['create_date'].'&nbsp;';
		echo '<br>';
	}
	?>
	
</body>
</html>
