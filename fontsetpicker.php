<?php
##########################################################################
#  Please refer to the README file for licensing and contact information.
# 
#  This file has been updated for version 1.5.20050630 
# 
#  If you like this application, do support me in its development 
#  by sending any contributions at www.calendarix.com.
#
#
#  Copyright © 2002-2005 Vincent Hor
##########################################################################

include("cal_config.php"); 

if (!isset($_GET['frmname']))
  $frmname = 'someform';
else
  $frmname = $_GET['frmname'];
if (!isset($_GET['fontset']))
  $fontset = 'formelement';
else
  $fontset = $_GET['fontset'];

$fset = explode("|",$fontset);

?>
<HTML>
<HEAD>
<TITLE>Font Set Picker</TITLE>
<SCRIPT LANGUAGE="JavaScript">
var popupHandle;
function closePopup() {
  if(popupHandle != null && !popupHandle.closed) popupHandle.close();
  }

function displayPopup(position,url,name,height,width,evnt) {
// position=1 POPUP: makes screen display up and/or left, down and/or right 
// depending on where cursor falls and size of window to open
// position=2 CENTER: makes screen fall in center

var properties = "toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width="+width+",height="+height+"";

var leftprop, topprop, screenX, screenY, cursorX, cursorY, padAmt;

if(navigator.appName == "Microsoft Internet Explorer") {
screenY = document.body.offsetHeight;
screenX = window.screen.availWidth;
}
else {
screenY = screen.height;
screenX = screen.width;
}

if(position == 1)	{ // if POPUP not CENTER
  if(navigator.appName == "Microsoft Internet Explorer") {
    cursorX = evnt.screenX;
    cursorY = evnt.screenY;
    }
  else {
    cursorX = screenX;
    cursorY = screenY;
    }
padAmtX = 10;
padAmtY = 10;


if((cursorY + height + padAmtY) > screenY) {
// make sizes a negative number to move left/up
padAmtY = (-30) + (height * -1);
// if up or to left, make 30 as padding amount
}
if((cursorX + width + padAmtX) > screenX)	{
padAmtX = (-30) + (width * -1);	
// if up or to left, make 30 as padding amount
}

if(navigator.appName == "Microsoft Internet Explorer") {
  leftprop = cursorX + padAmtX;
  topprop = cursorY + padAmtY;
}
else {
  leftprop = (cursorX - pageXOffset + padAmtX);
  topprop = (cursorY - pageYOffset + padAmtY);
   }
}
else{
leftvar = (screenX - width) / 2;
rightvar = (screenY - height) / 2;

if(navigator.appName == "Microsoft Internet Explorer") {
  leftprop = leftvar;
  topprop = rightvar;
}
else {
  leftprop = (leftvar - pageXOffset);
  topprop = (rightvar - pageYOffset);
   }
}

if(evnt != null) {
  properties = properties + ", left = " + leftprop;
  properties = properties + ", top = " + topprop;
}
closePopup();
popupHandle = open(url,name,properties);
}

function pickColor(theURL) { 
  var version4 = (navigator.appVersion.charAt(0) == "4"); 
  displayPopup(1,theURL,'popupclr',200,320,(version4 ? self.event : null));
}

</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function getSet() {
  top.opener.document.<?php echo$frmname?>.<?php echo$fset[0]?>.value = document.colorform.ffam.value;
  top.opener.document.<?php echo$frmname?>.<?php echo$fset[1]?>.value = document.colorform.fcol.value;
  top.opener.document.<?php echo$frmname?>.<?php echo$fset[2]?>.value = document.colorform.fsize.value;

  for (var i=0; i<document.colorform.fstyle.length; i++) {
    if (document.colorform.fstyle.options[i].selected)  {
	top.opener.document.<?php echo$frmname?>.<?php echo$fset[3]?>.value =document.colorform.fstyle.options[i].value;
//	top.opener.document.<?php echo$frmname?>.<?php echo$fset[3]?>.value = document.colorform.fstyle.value ;
//    alert(document.colorform.fstyle.options[i].value) ;
    }
  }

  for (var i=0; i<document.colorform.fweight.length; i++) {
    if (document.colorform.fweight.options[i].selected)  {
	top.opener.document.<?php echo$frmname?>.<?php echo$fset[4]?>.value =document.colorform.fweight.options[i].value;
    }
  }

<?php if ($fset[5]=="nil") echo "/*" ?>

  for (var i=0; i<document.colorform.talign.length; i++) {
    if (document.colorform.talign.options[i].selected)  {
	top.opener.document.<?php echo$frmname?>.<?php echo$fset[5]?>.value =document.colorform.talign.options[i].value;
    }
  }

<?php if ($fset[5]=="nil") echo "*/" ?>
<?php if ($fset[6]=="nil") echo "/*" ?>

  for (var i=0; i<document.colorform.tdecor.length; i++) {
    if (document.colorform.tdecor.options[i].selected)  {
	top.opener.document.<?php echo$frmname?>.<?php echo$fset[6]?>.value =document.colorform.tdecor.options[i].value;
    }
  }

<?php if ($fset[6]=="nil") echo "*/" ?>

  top.opener.document.<?php echo$frmname?>.submit();
  loadSet();
}

function loadSet() {
  document.colorform.hexval.value = top.opener.document.<?php echo$frmname?>.<?php echo$fset[0]?>.value  
	+ " | " + top.opener.document.<?php echo$frmname?>.<?php echo$fset[1]?>.value 
	+ " | " + top.opener.document.<?php echo$frmname?>.<?php echo$fset[2]?>.value  
	+ " | " + top.opener.document.<?php echo$frmname?>.<?php echo$fset[3]?>.value
	+ " | " + top.opener.document.<?php echo$frmname?>.<?php echo$fset[4]?>.value 
<?php if ($fset[5]=="nil") echo "/*" ?>
	+ " | " + top.opener.document.<?php echo$frmname?>.<?php echo$fset[5]?>.value 
<?php if ($fset[5]=="nil") echo "*/" ?>
<?php if ($fset[6]=="nil") echo "/*" ?>
	+ " | " + top.opener.document.<?php echo$frmname?>.<?php echo$fset[6]?>.value 
<?php if ($fset[6]=="nil") echo "*/" ?>
	;
  document.colorform.currentset.value = document.colorform.hexval.value ;

  document.colorform.ffam.value = top.opener.document.<?php echo$frmname?>.<?php echo$fset[0]?>.value;
  document.colorform.fcol.value = top.opener.document.<?php echo$frmname?>.<?php echo$fset[1]?>.value;
  document.colorform.fsize.value = top.opener.document.<?php echo$frmname?>.<?php echo$fset[2]?>.value;

  for (var i=0; i<document.colorform.fstyle.length; i++) {
    if (top.opener.document.<?php echo$frmname?>.<?php echo$fset[3]?>.value==document.colorform.fstyle.options[i].value)  {
	document.colorform.fstyle.options[i].selected = true;
	}
    }

  for (var i=0; i<document.colorform.fweight.length; i++) {
    if (top.opener.document.<?php echo$frmname?>.<?php echo$fset[4]?>.value==document.colorform.fweight.options[i].value)  {
	document.colorform.fweight.options[i].selected = true;
	}
    }

<?php if ($fset[5]=="nil") echo "/*" ?>

  for (var i=0; i<document.colorform.talign.length; i++) {
    if (top.opener.document.<?php echo$frmname?>.<?php echo$fset[5]?>.value==document.colorform.talign.options[i].value)  {
	document.colorform.talign.options[i].selected = true;
	}
    }

<?php if ($fset[5]=="nil") echo "*/" ?>
<?php if ($fset[6]=="nil") echo "/*" ?>

  for (var i=0; i<document.colorform.tdecor.length; i++) {
    if (top.opener.document.<?php echo$frmname?>.<?php echo$fset[6]?>.value==document.colorform.tdecor.options[i].value)  {
	document.colorform.tdecor.options[i].selected = true;
	}
    }

<?php if ($fset[6]=="nil") echo "*/" ?>

}

//  End -->
</script>

<?php
include("cal_style.php");
?>

</HEAD>

<body class=cal onLoad="Javascript:loadSet();" >

<center>
<form name=colorform>
<table width=280 border=0 align=center>

<tr><td align=left class=normalfont><?php echo translate("Font Family"); ?></td><td align=left class=normalfont>
<input name=ffam type=text value='' size=25><br/>
</td>
</tr>

<tr>
<td align=left class=normalfont><?php echo translate("Font"); ?> <a class=menufont href='#' onClick="Javascript:pickColor('colorpicker.php?frmname=colorform&clrfrm=fcol');"><?php echo translate("Color"); ?></a>
</td><td align=left class=normalfont>
<input name=fcol type=text value='' size=25><br/>
</td>
</tr>

<tr><td align=left class=normalfont><?php echo translate("Font Size"); ?></td><td align=left class=normalfont>
<input name=fsize type=text value='' size=25><br/>
</td>
</tr>

<tr><td align=left class=normalfont>
<?php echo translate("Font Style"); ?>
</td><td align=left>
<select name=fstyle>
<option value='normal'>normal
<option value='italic'>italic
<option value='oblique'>oblique
</select>&nbsp;&nbsp;<br/>
</td></tr>

<tr><td align=left class=normalfont>
<?php echo translate("Font Weight"); ?>
</td><td align=left>
<select name=fweight>
<option value='normal'>normal
<option value='bold'>bold
<option value='bolder'>bolder
<option value='lighter'>lighter
<option value='100'>100
<option value='200'>200
<option value='300'>300
<option value='400'>400
<option value='500'>500
<option value='600'>600
<option value='700'>700
<option value='800'>800
<option value='900'>900
</select>&nbsp;&nbsp;<br/>
</td></tr>

<?php if ($fset[5]=="nil") echo "<!--" ?>

<tr><td align=left class=normalfont>
<?php echo translate("Text Align"); ?>
</td><td align=left>
<select name=talign>
<option value='left'>left
<option value='right'>right
<option value='center'>center
<option value='justify'>justify
</select>&nbsp;&nbsp;</br>
</td></tr>

<?php if ($fset[5]=="nil") echo "-->" ?>
<?php if ($fset[6]=="nil") echo "<!--" ?>

<tr><td align=left class=normalfont>
<?php echo translate("Text Decoration"); ?>
</td><td align=left>
<select name=tdecor>
<option value='none'>none
<option value='underline'>underline 
<option value='overline'>overline
<option value='line-through'>line-through
<option value='blink'>blink
</select>&nbsp;&nbsp;<br/>
</td></tr>

<?php if ($fset[6]=="nil") echo "-->" ?>

</table>
<br/>

<input type=hidden name=currentset value="">
<div class=menufont align=center><?php echo translate("Current Set"); ?><br/>
<input type=text name=hexval size=70 value='' readonly>
</div>

<br/>

<div align=center>
<input class=button type=button name=bSelect value='<?php echo translate("Apply")?>' onClick="Javascript:getSet();">
&nbsp; &nbsp; <input class=button type=button value='<?php echo translate("Close")?>' onClick="Javascript:window.close();">
</div>

</form>
</center>

</body>
</HTML>