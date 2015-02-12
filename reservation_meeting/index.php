<?php
ob_start();
session_start();
/*if($_SESSION["Username"] == ""){
	header("location:../index.php");
	exit();
}
$_SESSION['start'] = time(); // taking now logged in time
if(!isset($_SESSION['expire'])){
	$_SESSION['expire'] = $_SESSION['start'] + 3600 ; // ending a session in 1 Hr.
}
$now = time(); // checking the time now when home page starts

if($now > $_SESSION['expire']){
	session_destroy();
	//echo "Your session has expire !  <a href='logout.php'>Click Here to Login</a>";
}else{
	//echo "This should be expired in 1 min <a href='logout.php'>Click Here to Login</a>";
}*/
include("../connect/connect.php");
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
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/foundation.css">
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
					<td class="b-bottom" style="text-align:left;width:42%;">
						<div class="large-4 columns"><a href="../index2.php"><?php echo '< Home'?></a></div>	
					</td>
					<td class="b-bottom"><div class="large-4 columns"><h4>Reservations Meeting</h4></div></td>
					<!--<td class="b-bottom"><div class="large-4 columns"><input type="button" value="send-mail" class="btn-send-mail" onclick="window.location.href='send-mail-itenary.php?account=<?php echo $rs_account['id_account']?>'" title="Send E-mail"></div></td>-->
				</tr>	
				<!--light box -->
				<!--<script type="text/javascript" src="js/fancybox/scripts/jquery-1.4.3.min.js"></script>-->
				<script type="text/javascript" src="js/fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
				<script type="text/javascript" src="js/fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
				<link rel="stylesheet" type="text/css" href="js/fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
				<!--<link rel="stylesheet" href="js/style/style.css" />-->
				<script type="text/javascript">
					$(document).ready(function($) {
						$(".various").fancybox({
							maxWidth	: 800,
							maxHeight	: 600,
							fitToView	: false,
							width		: '80%',
							height		: '80%',
							autoSize	: false,
							closeClick	: false,
							hideOnOverlayClick	: false,
							openEffect	: 'none',
							closeEffect	: 'none',
							type		: 'iframe',
							onClosed	:	function() {
								parent.location.reload(true); 
							}		
						});
					});
				</script>	
				<tr>
					<td style="background: #fff;" colspan="2">
						<a class='various' data-fancybox-type='iframe' href='reserveration.php?mode=New'>
							<div class="btn-home">
								<?php echo 'จองห้องประชุม'?></a>			
							</div>
						</a>
						<a class='various' data-fancybox-type='iframe' href='detail_room.php'>
							<div class="btn-home">
								<?php echo 'รายการจองห้องประชุม'?></a>			
							</div>
						</a>
					</td>
				</tr>
				<tr>
					<td style="background: #fff;" colspan="2">
						<div class="large-4 columns">
							<?php
								$weekDay = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
								$EngMonth = array( "01" => "January", "02" => "February", "03" => "March", "04" => "April",
											"05" => "May","06" => "June", "07" => "July", "08" => "August",
											"09" => "September", "10" => "October", "11" => "November", "12" => "December");
										 
								//Sun - Sat
								$month = isset($_GET['month']) ? $_GET['month'] : date('m'); //ถ้าส่งค่าเดือนมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้เดือนปัจจุบัน
								$year = isset($_GET['year']) ? $_GET['year'] : date('Y'); //ถ้าส่งค่าปีมาใช้ค่าที่ส่งมา ถ้าไม่ส่งมาด้วย ใช้ปีปัจจุบัน
								//วันที่
								$startDay = $year.'-'.$month."-01";   //วันที่เริ่มต้นของเดือน
										 
								$timeDate = strtotime($startDay);   //เปลี่ยนวันที่เป็น timestamp
								$lastDay = date("t", $timeDate);   //จำนวนวันของเดือน
										 
								$endDay = $year.'-'.$month."-". $lastDay;  //วันที่สุดท้ายของเดือน
								 
								$startPoint = date('w', $timeDate);   //จุดเริ่มต้น วันในสัปดาห์
										 
								//echo "
								$data ;
								//print_r($data);
								//echo "<hr>";
							?>
							<script type='text/javascript'>
								function goTo(month, year){
									window.location.href = "index.php?year="+ year +"&month="+ month;
								}
							</script>
							<?php
								/*echo "
								ตำแหน่งของวันที่ $startDay คือ <strong>", $startPoint , " (ตรงกับ วัน" , $weekDay[$startPoint].")</strong>";
								*/ 
								$title = $EngMonth[$month];
								$date2=date('d');
								$month2=date('F');
								$year2=date('Y');
								//ลดเวลาลง 1 เดือน
								$prevMonTime = strtotime ( '-1 month' , $timeDate  );
								$prevMon = date('m', $prevMonTime);
								$prevYear = date('Y', $prevMonTime);
								$prevMonText = date('F', $prevMonTime);
								//เพิ่มเวลาขึ้น 1 เดือน
								$nextMonTime = strtotime ( '+1 month' , $timeDate  );
								$nextMon = date('m', $nextMonTime);
								$nextYear = date('Y', $nextMonTime);
								$nextMonText = date('F', $nextMonTime);

								if($_GET['year'] == ''){$year_head=date('Y');}
								else{$year_head=$_GET['year'];}

								echo '<div id="main">';
								echo '<div id="nav"><button class="navLeft" onclick="goTo(\''.$prevMon.'\', \''.$prevYear.'\');">'.$prevMonText.'</button>
									<div class="title_calendar">'.$title.' '.$year_head.'</div>
									<button class="navRight" onclick="goTo(\''.$nextMon.'\', \''.$nextYear.'\');">'.$nextMonText.'</button></div>
									<div style="clear:both"></div>';
								echo "<table id='tb_calendar' cellpadding='0' cellspacing='0'>"; //เปิดตาราง
								echo "<tr><th class='th-calendar th-calendar-right th-calendar-top'>Sun</th><th class='th-calendar th-calendar-right th-calendar-top'>Mon</th><th class='th-calendar th-calendar-right th-calendar-top'>Tue</th><th class='th-calendar th-calendar-right th-calendar-top'>Wed</th><th class='th-calendar th-calendar-right th-calendar-top'>Thu</th><th class='th-calendar th-calendar-right th-calendar-top'>Fri</th><th class='th-calendar th-calendar-top'>Sat</th></tr>";
								echo "<tr>";    //เปิดแถวใหม่
								$col = $startPoint;          //ให้นับลำดับคอลัมน์จาก ตำแหน่งของ วันในสับดาห์
								if($startPoint < 7){         //ถ้าวันอาทิตย์จะเป็น 7
								 echo str_repeat("<td class='th-calendar-right th-calendar-bottom'> </td>", $startPoint); //สร้างคอลัมน์เปล่า กรณี วันแรกของเดือนไม่ใช่วันอาทิตย์
								}
								?>
								
								<?php
								if(($rs_account['role_user']==1)||($rs_account['role_user']==2)){$and='';}else{$and='';}							
								$j=0;								
								for($i=1; $i <= $lastDay; $i++){ //วนลูป ตั้งแต่วันที่ 1 ถึงวันสุดท้ายของเดือน
									$sql="select * from meeting_room_list";
									$sql .=$and;
									$res=mysql_query($sql) or die ('Error '.$sql);							

									if($rs1['date_visited'] != null){if($rs_account['role_user']==3){$back="background:#DFF8DA;";}}else{$back='';}
									if($_REQUEST['year']){$year=$_REQUEST['year'];}else{$year=$year2;}
									if(($date2 == $i)&&($month2 == $title) && ($year == $year2)){$style="background:#f5f5f5;width:14%;vertical-align: top;padding-left:0;padding-right:0;";}else{$style="background:#fff;vertical-align: top;padding-left:0;padding-right:0;width:14%;";$text_center='text-align:center;font-size:1.5em;';}//ตรวจสอบว่าวันที่ต้องกับวันที่ปัจจุบันหรือเปล่า ให้ใส่สีพื้นหลัง
									$col++;       //นับจำนวนคอลัมน์ เพื่อนำไปเช็กว่าครบ 7 คอลัมน์รึยัง 
									echo "<td style='".$style.$back."' class='th-calendar-right th-calendar-bottom'>";
									echo "<div style='".$text_center.$back."'>".$i."</div>";
									echo '<br>';						
									while($rs=mysql_fetch_array($res)){
										list($year,$month_db,$day) = split('[/.-]', $rs["start_date"]); 
										$j++;
										if($j % 2 == 0){$title_header='background:none;padding:2%;font-size:1.0em;';}
										else{$title_header='background:none;padding:2%;';}

										if($_GET['month']){$month3=$_GET['month'];}else{$month3=date('m');}
										if(($day==$i) && ($month_db==$month3)){
											echo '<a class="various" data-fancybox-type="iframe" href="reserveration.php?mode='.$rs['id_room_list'].'">';
											echo $rs['title'].'&nbsp;'.$rs['time_room'].'-'.$rs['end_time_room'];	
											$sql_account2="select * from account where id_account='".$rs['create_by']."'";
											$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
											$rs_account2=mysql_fetch_array($res_account2);
											echo ' by '.$rs_account2['name'];
											if($rs_account['id_account']==$rs['create_by']){
												echo '<a href="del-meeting.php?mode='.$rs['id_room_list'].'&month='.$_GET['month'].'&year='.$_GET['year'].'">';
												echo '<img src="img/cancel.png" width="13">';
												echo '</a>';
											}
											echo '<br>';
										}
									 }
								 echo "</td>";  //สร้างคอลัมน์ แสดงวันที่
								  if($col % 7 == false){   //ถ้าครบ 7 คอลัมน์ให้ขึ้นบรรทัดใหม่
								  echo "</tr><tr>";   //ปิดแถวเดิม และขึ้นแถวใหม่
								  $col = 0;     //เริ่มตัวนับคอลัมน์ใหม่
								 }
								}
								if($col < 7){         // ถ้ายังไม่ครบ7 วัน
								 echo str_repeat("<td class='th-calendar-bottom'> </td>", 7-$col); //สร้างคอลัมน์ให้ครบตามจำนวนที่ขาด
								}
								echo '</tr>';  //ปิดแถวสุดท้าย
								echo '</table>'; //ปิดตาราง
								echo '</main>';
							?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
 <!-- <script>
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
  
 <!-- <script>
    $(document).foundation();
  </script>-->
</body>
</html>
