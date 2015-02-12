<?php
##########################################################################
#  Please refer to the README file for licensing and contact information.
# 
#  This file has been updated for version 1.5.20050501 
# 
#  If you like this application, do support me in its development 
#  by sending any contributions at www.calendarix.com.
#
#
#  Copyright © 2002-2005 Vincent Hor
##########################################################################

/*******************/
/* search function */
/*******************/

function search(){
global $SEARCHCFG ;
echo "<form action=";
if ($SEARCHCFG[0]==1) {
  echo "cal_search.php method=post>\n";
  }
else echo ">";
echo "<input type=text name=search size=20>\n";
echo "<input class=button type=submit value=\"".translate("search")."\">";
echo "</form>" ;
}
?>
