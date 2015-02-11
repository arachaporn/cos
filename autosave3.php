<?php 

include("connect/connect.php");

echo "<h3>Database fields</h3>";
$resultwhole = mysql_query("SELECT * FROM meeting");
echo "<table><tr><td>ID</td><td>Name</td></tr>";
while ($rowwhole = mysql_fetch_array($resultwhole)){
echo "<tr>";
echo "<td>".$rowwhole['id_meeting']."</td>";
echo "<td>".$rowwhole['meeting_name']."</td>";
echo "</tr>";
}
echo "</table>";
?>