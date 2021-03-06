<?php
include "header.php";

echo toJavaScript();

function toJavaScript(){
	ob_start();
	?>
	<script>
		$(document).ready(function(){
			$("#entryTable").tablesorter();
		});
		
		function deleteEntry(intEntryID){
			$.ajax({
					url: 'viewbooks.php',
					async: true,
					type: 'post',
					contentType: "application/x-www-form-urlencoded;charset=ISO-8859-15",
					data: {
						intEntryID: intEntryID
					},
					beforeSend: function(){
						$('#entryRow' + intEntryID).remove();
					},
					success: function(data){
						alert('Entry deleted');
					},
					error: function(textStatus, errorThrown){
						alert(textStatus);
					}
			});
		}
	</script>
	<?
		$strJS = ob_get_contents();
		ob_end_clean();
		
		return $strJS;
}

$result = mysql_query("SELECT intEntryID, intUserID, strBookName, dblPrice, dtmDate FROM tblEntry");

echo "<table id='entryTable' border='1'>
<thead>
<tr id='headingRow'>
<th id='userCol'>User</th>
<th id='bookCol'>Book</th>
<th id='priceCol'>Price</th>
<th id='emailCol'>Email</th>
<th id='dateCol'>Date</th>
</tr>
</thead>";

echo "<tbody>";
while($row = mysql_fetch_array($result))
{
  echo "<tr id='entryRow" . $row['intEntryID'] . "'>";
  echo "<td>" . getUsernameFromUserID($row['intUserID']) . "</td>";
  echo "<td>" . $row['strBookName'] . "</td>";
  echo "<td>" . $row['dblPrice'] . "</td>";
  echo "<td>" . getEmailFromUserID($row['intUserID']) . "</td>";
  echo "<td>" . $row['dtmDate'] . "</td>";
  echo "<td><a href='bookdetails.php?intEntryID=" . $row['intEntryID'] . "'>Details</a></td>";
  if(logged_in() && is_admin()){
	  echo "<td><input type='button' id='deleteButton" . $row['intEntryID'] . "' value='delete' onclick='deleteEntry(" . $row['intEntryID'] . ")'></td>";
  }
  echo "</tr>";
}
echo "</tbody>";
echo "</table>";


if(isset($_POST['intEntryID']) && !empty($_POST['intEntryID'])){
	$intEntryID = $_POST['intEntryID'];
	delete_entry($intEntryID);
}

include "footer.php";
?>


