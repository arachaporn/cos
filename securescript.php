<?php
##########################################################################
#  Please refer to the README file for licensing and contact information.
# 
#  This file has been updated for version 1.7.20070707 
# 
#  If you like this application, do support me in its development 
#  by sending any contributions at www.calendarix.com.
#
#
#  Copyright © 2002-2007 Vincent Hor
##########################################################################


$userid = "" ;
$uname = "" ;
$ugroup = 0 ;  // For usergroup definition when there is no login, default to 'user'

########################################################################
# 
# If using external login system, set and enable the following variables
# $userid = 1;
# $uname = "test";
# $ugroup = 0;
# $_SESSION["login"] = $uname;
# And comment out the rest of the code below.
#
########################################################################

if ($op=="NOUVIEW")
{
  if (isset($_SESSION["login"])){
    $callogin = $_SESSION["login"];
    $calpass = $_SESSION["password"];
    $row = 1;

    $query = "select username,password,user_id,group_id from ".$USER_TB." where username='".$callogin."' AND password='".$calpass."'";
    $result = mysql_query($query);
    $row = mysql_fetch_object($result);

    if (!$row) {
        header ("location: ".$PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_login.php");
	  exit();
		}
    else {
    	$userid = $row->user_id ;
	$ugroup = $row->group_id ;
	$uname = $row->username ;
	}

    }
  else {
    header ("location: ".$PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_login.php");
    exit();
    }
}
elseif ($UVIEW[0]==1) {
  if (isset($_SESSION["login"])){
    $callogin = $_SESSION["login"];
    $calpass = $_SESSION["password"];
    $row = 1;

    $query = "select username,password,user_id,group_id from ".$USER_TB." where username='".$callogin."' AND password='".$calpass."'";
    $result = mysql_query($query);
    $row = mysql_fetch_object($result);

    if (!$row) {
	if ($UVIEW[2]==0) {
        header ("location: ".$PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_login.php");
	  exit();
	  }
	}
    else {
      $userid = $row->user_id ;
	$ugroup = $row->group_id ;  
	$uname = $row->username ;
	}
  }
  else 
	{
	if ((($op=="VCAL")&&($ALLOWVIEW[13]==0))||($UVIEW[2]==0)||($op== "eventform")||($op== "addevent")||($op== "upeventform")) {
        header ("location: ".$PROTOCOL."://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/cal_login.php");
	  exit();
	  }
	}
}

?>