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

require_once('cal_config.php');
$dname = dirname($_SERVER['PHP_SELF']);
if ($dname=="\\") $dname = '' ;	// fix windows based root hosting returning just "\"
header("location: ".$PROTOCOL."://".$_SERVER['HTTP_HOST'].$dname."/calendar.php");
?>
