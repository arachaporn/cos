<?
$host="localhost";
$user_name="root";
$pass_word="root";
$db="cosdb";
mysql_connect( $host,$user_name,$pass_word) or die ("Can't connect database");
mysql_select_db($db) or die("Can't connect database"); 
//database connect thai
mysql_query("SET character_set_results=utf8");
mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_connection=utf8");
?>