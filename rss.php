<?php
#############################################################
# Calendarix Advanced 1.8.20081228                          #
# You should only need to edit the path to cal_config.php   #
#                                                           #
#  Copyright © 2008-2009 Vincent Hor                        #
#############################################################
require_once ("cal_config.php");

if ($RSSCFG[0]==0) exit();	// RSS is disabled

$CATLEVEL = $RSSCFG[1];		// Category ID of the category. Itself and its next level sub categories of events is viewed, active only if events viewed in category levels, otherwise must be 0.
$DAYSBEFORE = $RSSCFG[2];	// Get events for number of days before current day
$DAYSTOVIEW = $RSSCFG[3]; 	// Get events for number of days ahead of current day. starts at 0 - current day, 1 - tomorrow... 
$GROUP_REPEATS = $RSSCFG[4];	// Specifies if repeated events for consecutive days should be grouped to 1 display, 
				// $ORDERBY must be 0 for this to work; 0 = group; 1 = no group.
$ORDERBY = $RSSCFG[5];	// Defines if the events are listed by 
				// 0 = event date; 
				// 1 = event entry time stamp, latest last; 
				// 2 = event entry time stamp, latest first.

#####################################
# NO MORE USER SETTINGS BEYONG THIS #
#####################################
include_once ($calpath."cal_utils.php");
include_once ("feedcreator.class.php"); 

function parseToXML($str)
{
  $xmlstr = $str;
  $xmlstr = str_replace("&nbsp;"," ",$xmlstr);
  $xmlstr = str_replace("&","&#38;",$xmlstr);
  $xmlstr = str_replace("<br />"," ",$xmlstr);
  $xmlstr = str_replace("<br/>"," ",$xmlstr);
  $xmlstr = strip_tags($xmlstr);
  return $xmlstr;
}

$rss = new UniversalFeedCreator(); 
//$rss->useCached(); // use cached version if age<1 hour
$rss->title = parseToXML($CALNAME); 
$rss->description = ""; 
$rss->link = $PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/calendar.php"; 
$rss->syndicationURL = $PROTOCOL."://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; 
$rss->cssStyleSheet = "";

$image = new FeedImage(); 
$image->title = "Calendarix Logo"; 
$image->url = "http://www.calendarix.com/calendarix.gif"; 
$image->link = "http://www.calendarix.com/"; 
$image->description = "Feed provided by Calendarix. Click to visit."; 

$rss->image = $image; 

?>
<?php
$m = correctTime("n");
$y = correctTime("Y");
$d = correctTime("j");

$d = $d - $DAYSBEFORE;

// Generate the query
$query = "select id,idgroup,title,description,url,email,user,cat_name,day,starttime,endtime,month,year,location,timezone,DATE_FORMAT(timestamp,'%Y-%m-%d %H:%i:%s') as tstamp from ".$EVENTS_TB." left join ".$CAT_TB." on ".$EVENTS_TB.".cat=".$CAT_TB.".cat_id where approved = '1' and priority<>7 and priority<>5 and priority<>2 ";
// Filter events by categories
if ($ALLOWVIEW[11]==1) {
	$query .= " and (".$CAT_TB.".cat_id=$CATLEVEL ";
	foreach ($ALLCAT as $v) {
		$query .= " or ".$CAT_TB.".cat_id=$v" ;
		}
	$query .= ")";			
	}
// REPLACING THE NEXT LINE WITH THE ABOVE LINES IN THE 'IF'	PORTION USING FUNCTION DRILLCATID DOES
// BIRD'S EYE DRILL DOWN!
//if ($ALLOWVIEW[11]==1) $query .= " and (".$CAT_TB.".parent_id=$CATLEVEL or ".$CAT_TB.".cat_id=$CATLEVEL)" ;

else if ($CATLEVEL!=0)  $query .= " and ".$EVENTS_TB.".cat=$CATLEVEL";

// Filter events by days ahead
$endday = date("j",mktime(0,0,0,$m,$d+$DAYSBEFORE+$DAYSTOVIEW,$y));
$endmth = date("n",mktime(0,0,0,$m,$d+$DAYSBEFORE+$DAYSTOVIEW,$y));
$endyr = date("Y",mktime(0,0,0,$m,$d+$DAYSBEFORE+$DAYSTOVIEW,$y));

$query .= " and ( ( ((year>'$y')&&(year<'$endyr')) || ( (month>'$m' and year='$y')" ;
if ($y>=$endyr) $query .= "&&";
else $query .= "||" ;
$query .= "(month<'$endmth' and year='$endyr'))|| ((day>=$d and month='$m' and year='$y')" ;
if (($endmth==$m)&&($y==$endyr)) $query .= "&&" ;
else if ($y>$endyr) $query .= "&&" ;
else if (($m>=$endmth)&&($y==$endyr)) $query .= "&&" ;
else $query .= "||" ;
$query .= "(day<=$endday and month='$endmth' and year='$endyr')) ) ) ";
if ($ORDERBY==0) $query .= " order by year, month, day, idgroup" ;
else if ($ORDERBY==1) $query .= " order by tstamp asc, idgroup";
else if ($ORDERBY==2) $query .= " order by tstamp desc, idgroup";

$result = mysql_query($query);

//echo "<h3>".$query."</h3>";
$EventData = array();
if ($GROUP_REPEATS==1)
{
$i = 0;
while($row = mysql_fetch_object($result)) {
$EventData[$i]['ID'] = $row->id;
$EventData[$i]['Title'] = $row->title;
$EventData[$i]['Description'] = $row->description;
$EventData[$i]['Category'] = $row->cat_name;
$EventData[$i]['Location'] = $row->location;
$EventData[$i]['URL'] = $row->url;
$EventData[$i]['EMail'] = $row->email;
$EventData[$i]['STime'] = $row->starttime;
$EventData[$i]['ETime'] = $row->endtime;
$EventData[$i]['Start'] = $row->year."-".$row->month."-".$row->day;
$EventData[$i]['End'] = $row->year."-".$row->month."-".$row->day;
$EventData[$i]['IDgroup'] = $row->idgroup;
$EventData[$i]['User'] = $row->user;
$EventData[$i]['TimeStamp'] = $row->tstamp;
$EventData[$i]['TimeZone'] = $row->timezone;
$i++;
}
unset($i);

$EventGroup = array();
for ($i=0; $i<count($EventData); $i++) {
if ((!is_null($EventData[$i]['IDgroup']))&&($EventData[$i]['IDgroup']!="")) {
	//echo "<h3>EventData IDGroup: ".$grp."</h3>";
	if (!isset($EventGroup[$EventData[$i]['IDgroup']])) {
	  $EventGroup[$EventData[$i]['IDgroup']] = array();
	  //echo "<h3>IDGroup: ".$EventData[$i]['IDgroup']."</h3>";
	  $EventGroup[$EventData[$i]['IDgroup']]['Tag'] = $i ;
	  $EventGroup[$EventData[$i]['IDgroup']]['LastDay'] = date("Y-m-d",mktime(0,0,0,date("n",strtotime($EventData[$i]['Start'])),date("j",strtotime($EventData[$i]['Start'])),date("Y",strtotime($EventData[$i]['Start']))));
	  }
	else {
	  if ($EventGroup[$EventData[$i]['IDgroup']]['LastDay'] == 
date("Y-m-d",mktime(0,0,0,date("n",strtotime($EventData[$i]['Start'])),date("j",strtotime($EventData[$i]['Start']))-1,date("Y",strtotime($EventData[$i]['Start'])))))
			{
			$num = $EventGroup[$EventData[$i]['IDgroup']]['Tag'];
			$EventData[$num]['End'] = $EventData[$i]['End'];
			$EventGroup[$EventData[$i]['IDgroup']]['LastDay'] = date("Y-m-d",mktime(0,0,0,date("n",strtotime($EventData[$i]['Start'])),date("j",strtotime($EventData[$i]['Start'])),date("Y",strtotime($EventData[$i]['Start']))));
			$EventData[$i]['Title'] = "";
			}
		}
	}
}

unset($i);
for ($i=0; $i<count($EventData); $i++) 
{
  if ($EventData[$i]['Title']!="") {
	$sdate = explode("-",$EventData[$i]['Start']);
	$edate = explode("-",$EventData[$i]['End']);
	$weekday = date ("w", mktime(12,0,0,$sdate[1],$sdate[2],$sdate[0]));
	$weekday++;
	$rsshead = translate("Date").": ".$sdate[2]." ".$mth[$sdate[1]]." ".$sdate[0].", ".ucfirst($week[$weekday]) ;
	if ($DTCONFIG[0]==0) {
	  if (trim($EventData[$i]['STime'])!='') {
		$rsshead = $rsshead.", ".translate("From").": " ;
		$rsshead = $rsshead.show12hour($EventData[$i]['STime']) ;
	    }
	  if (trim($EventData[$i]['ETime'])!='') {
		$rsshead = $rsshead." ".translate("To").": " ;
		$rsshead = $rsshead.show12hour($EventData[$i]['ETime']) ;
	    }
	}
	if (trim(stripslashes($EventData[$i]['Location']))!="")
	  $location = "[".translate("Location").": ".parseToXML(stripslashes($EventData[$i]['Location']))."] ";
	else $location = "";

	$cItem = new FeedCalItem();
	$cItem->calNameSpace = "http://www.calendarix.com/rss/define.html";
	$cItem->day = $sdate[2];
	$cItem->month = $sdate[1];
	$cItem->year = $sdate[0];
	$cItem->wkday = ucfirst($week[$weekday]);
	$cItem->ev_title = stripslashes($EventData[$i]['Title']);
	$cItem->ev_cat = stripslashes($EventData[$i]['Category']);
	$cItem->ev_location = stripslashes($EventData[$i]['Location']);
	$cItem->description = stripslashes($EventData[$i]['Description']);
	if ($EventData[$i]['End']!=$EventData[$i]['Start']) $cItem->description .= "<br/><br/><b><u> ".strtoupper(translate("till"))." ".$edate[2]." ".strtoupper($mth[$edate[1]])." ".$edate[0]."</u></b>";
	$cItem->ev_link = $PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_popup.php?id=".$EventData[$i]['ID'];
	if ($DTCONFIG[0]==0) {
	  if (trim($EventData[$i]['STime'])!='') {
		$cItem->ev_start = $EventData[$i]['STime'] ;
	    }
	  if (trim($EventData[$i]['ETime'])!='') {
		$cItem->ev_end = $EventData[$i]['ETime'] ;
	    }
	}
	$cItem->ev_timezone = $EventData[$i]['TimeZone'];
	$rss->addCalItem($cItem);

	$item = new FeedItem(); 
	$item->title = stripslashes($EventData[$i]['Title'])." [".$rsshead."]"; 
	$item->link = $PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_popup.php?id=".$EventData[$i]['ID']; 
	$item->description = $location.stripslashes($EventData[$i]['Description']); 
	$item->source = $PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/rssfeed.php\">"; 
	$item->author = $EventData[$i]['User']; 
	$item->category = stripslashes($EventData[$i]['Category']);
	$item->guid = strParamQry("calendarixid")."-".$EventData[$i]['ID'];
	$item->descriptionHtmlSyndicated = true;
    
	$tyear = substr($EventData[$i]['TimeStamp'],0,4) ;
	$tmonth = substr($EventData[$i]['TimeStamp'],5,2) ;
	if (substr($tmonth,0,1) == "0") $tmonth = str_replace("0","",$tmonth);
	$tday = substr($EventData[$i]['TimeStamp'],8,2) ;
	if (substr($tday,0,1) == "0") $tday = str_replace("0","",$tday);
	$ttime = substr($EventData[$i]['TimeStamp'],11,8);
      $item->date = date("r",mktime(substr($ttime,0,2),substr($ttime,3,2),substr($ttime,6,2),$tmonth,$tday,$tyear)); 
	$rss->addItem($item); 
  	}
  }
}
else
{
  while ($row = mysql_fetch_object($result))
  {
	$weekday = date ("w", mktime(12,0,0,$row->month,$row->day,$row->year));
	$weekday++;
	$rsshead = translate("Date").": ".$row->day." ".$mth[$row->month]." ".$row->year.", ".ucfirst($week[$weekday]) ;
	if ($DTCONFIG[0]==0) {
	  if (trim($row->starttime)!='') {
		$rsshead = $rsshead.", ".translate("From").": " ;
		$rsshead = $rsshead.show12hour($row->starttime) ;
	    }
	  if (trim($row->endtime)!='') {
		$rsshead = $rsshead." ".translate("To").": " ;
		$rsshead = $rsshead.show12hour($row->endtime) ;
	    }
	}
	if (trim(stripslashes($row->location))!="")
	  $location = "[".translate("Location").": ".parseToXML(stripslashes($row->location))."] ";
	else $location = "";

	$cItem = new FeedCalItem();
	$cItem->calNameSpace = "http://www.calendarix.com/rss/define.html";
	$cItem->day = $row->day;
	$cItem->month = $row->month;
	$cItem->year = $row->year;
	$cItem->wkday = ucfirst($week[$weekday]);
	$cItem->ev_title = stripslashes($row->title);
	$cItem->ev_cat = stripslashes($row->cat_name);
	$cItem->ev_location = stripslashes($row->location);
	$cItem->description = stripslashes($row->description);
	$cItem->ev_link = $PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_popup.php?id=".$row->id;
	if ($DTCONFIG[0]==0) {
	  if (trim($row->starttime)!='') {
		$cItem->ev_start = $row->starttime ;
	    }
	  if (trim($row->endtime)!='') {
		$cItem->ev_end = $row->endtime ;
	    }
	}
	$cItem->ev_timezone = $row->timezone;	
	$rss->addCalItem($cItem);
    $item = new FeedItem(); 
    $item->title = stripslashes($row->title)." [".$rsshead."]"; 
    $item->link = $PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_popup.php?id=".$row->id; 
    $item->description = $location.stripslashes($row->description); 
    $item->source = $PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/rssfeed.php\">"; 
    $item->author = $row->user; 
    $item->category = stripslashes($row->cat_name);
    $item->guid = strParamQry("calendarixid")."-".$row->id;
    $item->descriptionHtmlSyndicated = true;
    
    $tyear = substr($row->tstamp,0,4) ;
    $tmonth = substr($row->tstamp,5,2) ;
    if (substr($tmonth,0,1) == "0") $tmonth = str_replace("0","",$tmonth);
    $tday = substr($row->tstamp,8,2) ;
    if (substr($tday,0,1) == "0") $tday = str_replace("0","",$tday);
    $ttime = substr($row->tstamp,11,8);
  
    $item->date = date("r",mktime(substr($ttime,0,2),substr($ttime,3,2),substr($ttime,6,2),$tmonth,$tday,$tyear)); 
    $rss->addItem($item); 
  }
}
echo $rss->sendFeed("RSS2.0","rss.xml");

?>
