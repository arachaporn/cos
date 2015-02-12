<?php
session_start();
if($_SESSION["Username"] == ""){
	header("location:index.php");
	exit();
}
include("../connect/connect.php");
$sql_account = "SELECT * FROM account WHERE username = '".$_SESSION["Username"]."'  ";
$res_account = mysql_query($sql_account) or die ('Error '.$sql_account);
$rs_account = mysql_fetch_array($res_account);
$_SESSION["id_company"]=$_REQUEST['id_company'];
$_SESSION['company_name']=$_REQUEST['company_name'];

//create pdf
include("../mpdf/mpdf.php");
ob_start();
?>
<!DOCTYPE html>
<body>
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
	<table style="width:100%;line-height: 1.2em; text-align:left;" cellpadding="0" cellspacing="0">
		<tr>
			<td>
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

					echo $title.' '.$year_head;
					echo "<table id='tb_calendar' cellpadding='0' cellspacing='0'>"; //เปิดตาราง
					echo "<tr><th class='th-calendar th-calendar-right th-calendar-top'>Sun</th><th class='th-calendar th-calendar-right th-calendar-top'>Mon</th><th class='th-calendar th-calendar-right th-calendar-top'>Tue</th><th class='th-calendar th-calendar-right th-calendar-top'>Wed</th><th class='th-calendar th-calendar-right th-calendar-top'>Thu</th><th class='th-calendar th-calendar-right th-calendar-top'>Fri</th><th class='th-calendar th-calendar-top'>Sat</th></tr>";
					echo "<tr>";    //เปิดแถวใหม่
					$col = $startPoint;          //ให้นับลำดับคอลัมน์จาก ตำแหน่งของ วันในสับดาห์
					if($startPoint < 7){         //ถ้าวันอาทิตย์จะเป็น 7
						echo str_repeat("<td class='th-calendar-right th-calendar-bottom'> </td>", $startPoint); //สร้างคอลัมน์เปล่า กรณี วันแรกของเดือนไม่ใช่วันอาทิตย์
					}
					?>
					<?php
					if(($rs_account['role_user']==1)||($rs_account['role_user']==2)){$and='';}else{$and=' and create_by='.$rs_account['id_account'];}				
					$j=0;								
					for($i=1; $i <= $lastDay; $i++){ //วนลูป ตั้งแต่วันที่ 1 ถึงวันสุดท้ายของเดือน
						$sql="select * from meeting_room_list";
						$sql .=$and;
						$res=mysql_query($sql) or die ('Error '.$sql);							

						if($rs1['date_visited'] != null){
							if($rs_account['role_user']==3){$back="background:#DFF8DA;";}
						}else{$back='';}
						
						if($_REQUEST['year']){$year=$_REQUEST['year'];}else{$year=$year2;}
						
						if(($date2 == $i)&&($month2 == $title) && ($year == $year2)){
							$style="background:#f5f5f5;width:14%;vertical-align: top;padding-left:0;padding-right:0;";
						}else{
							$style="background:#fff;vertical-align: top;padding-left:0;padding-right:0;width:14%;";
							$text_center='text-align:center;font-size:1.5em;';
						}//ตรวจสอบว่าวันที่ต้องกับวันที่ปัจจุบันหรือเปล่า ให้ใส่สีพื้นหลัง
						
						$col++;       //นับจำนวนคอลัมน์ เพื่อนำไปเช็กว่าครบ 7 คอลัมน์รึยัง 
						echo "<td style='".$style.$back."' class='th-calendar-right th-calendar-bottom'>";
						echo "<div style='".$text_center.$back."'>".$i."</div>";
						echo '<br>';						
						while($rs=mysql_fetch_array($res)){
							list($year,$month,$day) = split('[/.-]', $rs["start_date"]); 
							$j++;
							if($j % 2 == 0){$title_header='background:none;padding:2%;font-size:1.0em;';}
							else{$title_header='background:none;padding:2%;';}
							if($day==$i){
								echo '<a class="various" data-fancybox-type="iframe" href="reserveration.php?mode='.$rs['id_room_list'].'">';
								echo $rs['title'].'&nbsp;'.$rs['start_time'].'-'.$rs['end_time'];	
								$sql_account2="select * from account where id_account='".$rs['create_by']."'";
								$res_account2=mysql_query($sql_account2) or die ('Error '.$sql_account2);
								$rs_account2=mysql_fetch_array($res_account2);
								echo ' by '.$rs_account2['name'];
								echo '<br>';
							}
						}//end while
								
						echo "</td>";  //สร้างคอลัมน์ แสดงวันที่
						if($col % 7 == false){   //ถ้าครบ 7 คอลัมน์ให้ขึ้นบรรทัดใหม่
							echo "</tr><tr>";   //ปิดแถวเดิม และขึ้นแถวใหม่
							$col = 0;     //เริ่มตัวนับคอลัมน์ใหม่
						}
					}//end for
					if($col < 7){         // ถ้ายังไม่ครบ7 วัน
						 echo str_repeat("<td class='th-calendar-bottom'> </td>", 7-$col); //สร้างคอลัมน์ให้ครบตามจำนวนที่ขาด
					}
					echo '</tr>';  //ปิดแถวสุดท้าย
					echo '</table>'; //ปิดตาราง
					echo '</main>';
				?>
			</td>
		</tr>
	</table>	
</body>
</html>
<?php
	$html = ob_get_contents();
	ob_end_clean();
	$mpdf=new mPDF('th','A4-L',0,'',13,13,32,3,3,3,'THSaraban');
	$mpdf-> SetAutoFont();
	$mpdf-> SetHTMLHeader('<table style="width:100%;line-height:1.3em;" cellspacing="0" cellpadding="0">			
			<tr>
			<td colspan="2" style="padding-bottom: 1%;border-bottom:1px solid #000;"><img src="img/logo.png" style="width:12%;"></td>			
		</tr></table>');
	$mpdf-> WriteHTML($html);
	$mpdf-> Output("roc/aaa.pdf","I");
?>