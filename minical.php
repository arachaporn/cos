<?php
#############################################################
# Calendarix Advanced 1.8.20081228                          #
# You should only need to edit the path to cal_config.php   #
# and $urlpathtocal if minical.php is not in default path   #
# Copyright © 2002-2009 Vincent Hor                         #
#############################################################
$calpath = dirname(__FILE__) ; 
$pos = strpos($calpath, "/");
if (!is_integer($pos)) $calpath = $calpath.'\\' ;
else $calpath = $calpath."/" ;

require_once ($calpath."cal_config.php");
$urlpathtocal = "http://localhost/calendarix/advanced/";	// must be absolute path!

##########################################
#                                        #
# Allowing viewing details of events and #
# viewing events by category levels      #
#                                        #
##########################################
$DETAILED_ENABLED = 1 ;	// enable detailed view of events with popup
$CATLEVEL = 0;	// Category ID of the category. Itself and its next level sub-categories of events is viewed, 
			// active only if events viewed in category levels, otherwise must be 0.
$USERNAME = '';	// view events belonging to only this user. Blank for all users.
#####################################
# NO MORE USER SETTINGS BEYONG THIS #
#####################################
// Ensures overlib javascript lib only included once so IE can render
$included_files = get_included_files();
$gotHeader = false;
foreach ($included_files as $filename) {
    $pos = strpos($filename,'cal_utils.php');
    if ($pos===false) echo "";
    else $gotHeader = true;
}

global $calpath, $date;
include_once ($calpath."cal_utils.php");

if (!$gotHeader) {
	include_once ($calpath."cal_css.php") ;
	echo "\n<!-- overLIB (c) Erik Bosrup -->\n";
	echo "\n<div id=\"overDiv\" style=\"position:absolute; visibility:hidden; z-index:1000;\"></div>\n";
	echo "<script language=\"JavaScript\" src=\"".$urlpathtocal."overlib.js\"></script>\n";
	echo "\n<!-- overLIB (c) Erik Bosrup -->\n";
}

global $POPVIEW, $UVIEW, $MINICFG, $CAT_TB, $EVENTS_TB, $DTCONFIG, $ALLOWVIEW, $version, $ALLCAT ;

if (!function_exists("cleanurl"))
{
	function cleanurl($urlvar,$dval)
	{
		if (preg_match("/[\'*<>;+@]/",$urlvar)) $urlvar = $dval;// check to prevent sql injection
		if (preg_match("/and/",$urlvar)) $urlvar = $dval;	// check to prevent sql injection
		if (preg_match("/or/",$urlvar)) $urlvar = $dval;	// check to prevent sql injection
		return $urlvar;
	}
}

// popup script
if ($DETAILED_ENABLED)
{
  echo "<script language='Javascript'>\n";
  echo "// script used for popup events\n";
  echo "function popup(event)\n";
  echo "{\n";
  echo "var url = '".$urlpathtocal."cal_popup.php?op=view&id='+event ;\n";
  echo "window.open(url,'Calendar','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=$POPVIEW[1],height=$POPVIEW[2]');\n";
  echo "}\n";
  echo "</script>\n";
}


if ($MINICFG[0]==0) {
	echo "<div align=center>".translate("disabled")."</div>";
	//exit();
}
else {

if (!isset($_GET['date']))
  $date = correctTime("Y-m-d"); //date("Y-m-d",mktime(0,0,0,date("m"),date("d"),date("Y"))) ;  
else
  $date = cleanurl($_GET['date'],correctTime("Y-m-d"));

if (!checkdate(intval(substr($date,5,2)),1,intval(substr($date,0,4))))	$date = correctTime("Y-m-d");  
$smyear = substr($date,0,4) ;
$smmonth = substr($date,5,2) ;
if (substr($smmonth,0,1) == "0")
  $smmonth = str_replace("0","",$smmonth);

$showeventcount = true;
$showyear = true;

// Set today's date corrected by timezone
$um = correctTime("n");
$uy = correctTime("Y");
$ud = correctTime("j");
/*
if ((date("G")+$DTCONFIG[2])>24) {
	$ud = date("j",mktime(0,0,0,$um,date("j")+1,$uy)) ;
	$um = date("n",mktime(0,0,0,$um,date("j")+1,$uy)) ;
	$uy = date("Y",mktime(0,0,0,$um,date("j")+1,$uy)) ;
	}

if ((date("G")+$DTCONFIG[2])<0) {
	$ud = date("j",mktime(0,0,0,$um,date("j")-1,$uy)) ;
	$um = date("n",mktime(0,0,0,$um,date("j")-1,$uy)) ;
	$uy = date("Y",mktime(0,0,0,$um,date("j")-1,$uy)) ;
}
*/
  // number of days in month
  $firstday = date ("w", mktime(12,0,0,$smmonth,1,$smyear));
  $nr = date("t",mktime(12,0,0,$smmonth,1,$smyear));
  echo "\n\n<table align=center class=smallcalmth width=100%>";
  echo "<tr>";
  echo "<td align=center valign=middle width='100%'><div class=smallcalmth>";

  $pdate = date("Y-m-d",mktime(0,0,0,$smmonth-1,1,$smyear)) ;
  if (date("Y",mktime(0,0,0,$smmonth-1,1,$smyear))>=$DTCONFIG[4])
    echo "<a class=smallcalmth href=".$_SERVER['PHP_SELF']."?date=$pdate>&lt;&lt;</a>";
  echo "&nbsp; &nbsp; &nbsp;";

  if ($ALLOWVIEW[5]==1) {
    echo "<a class=smallcalmth href=\"".$urlpathtocal."calendar.php?op=cal&month=".$smmonth."&year=".$smyear."\" target=_WINPOP>" ;
    }
    echo $mth[$smmonth] ;
  if ($ALLOWVIEW[5]==1) echo "</a> " ;
  if ($showyear) echo " ".$smyear;

  echo "&nbsp; &nbsp; &nbsp;";
  $pdate = date("Y-m-d",mktime(0,0,0,$smmonth+1,1,$smyear)) ;
  if (date("Y",mktime(0,0,0,$smmonth+1,1,$smyear))<=date("Y")+$DTCONFIG[5])
  echo "<a class=smallcalmth href=".$_SERVER['PHP_SELF']."?date=$pdate>&gt;&gt;</a>";

  echo "</div></td></tr></table>\n\n";

  echo "<table align=center class=smallcalmth >";
  echo "<tr>";

  // make the header days of week
  for ($i=intval($DTCONFIG[3])+1;$i<=(intval($DTCONFIG[3]) + 7);$i++){
    echo "<td align=center ";
    if (($i<>1)&&($i<>7)) echo "width=14% >" ;
    else echo "width=15% >" ;
    if (dayinweek($i) == 1)
      echo "<div class=dayfont>".substr($week[dayinweek($i)],0,3)."</div></td>"; // sunday
    else    
      echo "<div class=dayfont>".substr($week[dayinweek($i)],0,3)."</div></td>"; // rest of week
    }
  echo "</tr>\n\n<tr>";

  // print initial blank squares
  for ($i=1;$i<=blankdays(intval($DTCONFIG[3]),$firstday);$i++)  echo "<td>&nbsp;</td>";
  $a=0;
  for ($i=1;$i<=$nr;$i++){
    // now get eventual events on $i 
    $query = "select id,user,title,starttime,endtime,cat_color,cat_bgcolor from ".$EVENTS_TB." left join ".$CAT_TB." on ".$EVENTS_TB.".cat=".$CAT_TB.".cat_id where day='$i' and month='$smmonth' and year='$smyear' and approved='1' " ;
    if ($ALLOWVIEW[11]==1) {
	$query .= " and (".$CAT_TB.".cat_id=$CATLEVEL ";
	foreach ($ALLCAT as $v) {
		$query .= " or ".$CAT_TB.".cat_id=$v" ;
		}
	$query .= ")";			
	}
// REPLACING THE NEXT LINE WITH THE ABOVE LINES IN THE 'IF'	PORTION USING FUNCTION DRILLCATID DOES
// BIRD'S EYE DRILL DOWN!
//    if ($ALLOWVIEW[11]==1) $query .= " and (".$CAT_TB.".parent_id=$CATLEVEL or ".$CAT_TB.".cat_id=$CATLEVEL)" ;

    else if ($CATLEVEL!=0)  $query .= " and ".$EVENTS_TB.".cat=$CATLEVEL";

  // user
  if ($USERNAME!="") $query .= " and ".$EVENTS_TB.".user='".$USERNAME."' ";

  // scope
  $query .= " and priority<>7 and priority<>5 and priority<>2 ";
  $query = $query." order by day,month,year,starttime,title ASC";
  $result = mysql_query($query);
  $devtcnt = mysql_num_rows($result) ;

  echo "<td align=center ";
  if ($i == $ud && $smmonth == $um && $smyear == $uy) // highlight today's day
    echo "class=todayclr ";
  elseif ($devtcnt!=0)
    echo "class=smallcalmth ";
  echo "valign=top>\n\n";

  if ($devtcnt!=0) {
    // overlib line
    echo "<a class=smallcalmth target=_WINPOP href=" ;
    if ($ALLOWVIEW[3]==1) echo "'".$urlpathtocal."cal_day.php?op=day&catview=".$CATLEVEL."&date=".date("Y-m-d", mktime(0,0,0,$smmonth,$i,$smyear))."'" ;
    else echo "Javascript:void(0);";
    echo " onmouseover=\"return overlib('";
    echo "<table border=0 cellspacing=0 cellpadding=0 width=100%>" ;
    while ($row = mysql_fetch_object($result)){
	echo "<tr><td align=center valign=top>" ;
	//if (($SHOW[2]==1)||($SHOW[3]==1)) 
	echo "<table class=eventborder cellspacing=0 cellpadding=0 width=100%><tr><td align=center>" ;
	if ($row->starttime!='') {
	  if ($DTCONFIG[0]==0) {
	    echo "<td align=center class=eventtimeborder valign=top>" ;
	    echo "<div class=smallcalevtime>";
	    showtime($row->starttime,$row->endtime,1);
	    echo "</div>" ;
	    echo "</td>" ;
	  }
	}
	echo "<td align=left valign=top width=80% class=eventborder>" ;
	echo "<div class=smallcalev>";
	if ($DETAILED_ENABLED) {
	    if ($POPVIEW[0]==1)
		echo "<a href=\\'Javascript:void(0);\\' onclick=\\'Javascript:popup(".$row->id.");\\'>";
	    if (trim($row->cat_color)!="") {
 			if (trim($row->cat_bgcolor)!="") echo "<div style=\\'background:".$row->cat_bgcolor."\\'>" ; 
			echo "<font color=\\'".$row->cat_color."\\' >" ; 
			}
          echo subquot(stripslashes($row->title));
	    if (trim($row->cat_color)!="") echo "</font>" ;
		if (trim($row->cat_bgcolor)!="") echo "</div>";
	    if ($POPVIEW[0]==1)
	      echo "</a>";
	  }
	else
        echo subquot(stripslashes($row->title));
	echo "</div>" ;

	echo "</td></tr></table>" ;
    }
  echo "</table>" ;
  // overlib line
  if (!$DETAILED_ENABLED)
    echo "',FGCOLOR,'$MINICFG[3]',TEXTSIZE,'1',WIDTH,'$MINICFG[1]',VAUTO,HAUTO,CLOSETEXT,'".translate("Close")."');\" onmouseout=\"return nd();\">";
  else
    echo "',FGCOLOR,'$MINICFG[3]',TEXTSIZE,'1',WIDTH,'$MINICFG[1]',VAUTO,HAUTO,STICKY,CAPTION,' ".$i." ".$mth[$smmonth]." ',CLOSETEXT,'".translate("Close")."');\" onmouseout=\"return nd();\">";
  }

  echo "<div class=smallcalmth>".$i."</div>" ;
  if ($devtcnt!=0) echo "</a>";
  echo "</td>\n\n";
  $a++;
  if (blankdays(intval($DTCONFIG[3]),date("w",mktime(0,0,0,$smmonth,$i,$smyear))) == 6) {
    echo "</tr>\n\n<tr>";
    $a = 0;
    }
  }
  if ($a!=0) {
    for ($i=$a;$i<7;$i++) {
    echo "<td>&nbsp;</td>";
    }
  }
  echo "</tr>";
  if ($showeventcount) {
    echo "<tr><td align=center valign=top colspan=7>" ;
    // get total number of events for month
    $tquery = "select id from ".$EVENTS_TB." left join ".$CAT_TB." on ".$EVENTS_TB.".cat=".$CAT_TB.".cat_id where ".$EVENTS_TB.".month='".$smmonth."' and ".$EVENTS_TB.".year='".$smyear."' and ".$EVENTS_TB.".approved='1' and ".$EVENTS_TB.".priority<>2 and ".$EVENTS_TB.".priority<>5 and ".$EVENTS_TB.".priority<>7 " ;
    if ($ALLOWVIEW[11]==1) { $tquery .= " and (".$CAT_TB.".cat_id=$CATLEVEL ";
		foreach ($ALLCAT as $v) {
			$tquery .= " or ".$CAT_TB.".cat_id=$v" ;
			}
		$tquery .= ")";			
	}
    else if ($CATLEVEL!=0)  $tquery .= " and ".$EVENTS_TB.".cat=$CATLEVEL";

    if ($USERNAME!="") {  // view user specific events only
	$tquery .= " and ".$EVENTS_TB.".user='".$USERNAME."' ";
      }    

    $tresult = mysql_query($tquery);
    $trows = mysql_num_rows($tresult) ;
    echo "<table class=txtbox cellspacing=0 cellpadding=0><tr><td align=center><div class=smallcalmth> &nbsp;";

echo "<a class=datenumfont href='#' onmouseover=\"return overlib('Calendarix version ".$version."\\nCopyright © 2002-2009 Vincent Hor',FGCOLOR,'#FFFFFF',TEXTSIZE,'1',WIDTH,'225',VAUTO,HAUTO,CAPTION,'".translate("About Calendarix")."');\" onmouseout=\"return nd();\">";

    echo $trows." ".translate("events")."&nbsp; </div></td></tr></table>" ;
    echo "</td></tr>" ;
    }
  echo "</table>";
}
?>

