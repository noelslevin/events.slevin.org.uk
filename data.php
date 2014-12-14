<?php

include 'mysql_connect.php';
$query = "SELECT id, name, content, image, highlight, unixtime FROM comments ORDER BY id DESC";
$latest = NULL; // For working out most recent update
$result = @mysql_query($query);
if ($result) {
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$id = $row['id'];
		$name = stripslashes($row['name']);
		$content = stripslashes($row['content']);
		$image = $row['image'];
		$highlight = $row['highlight'];
		$time = date("l d F, Y @ G:i", $row['unixtime']);
		if ($latest == NULL) {
			$latest = $row['id'];
		}
		if ($highlight == 1) {
				echo "<div class=\"author\" data-last-id=\"".$id."\">\n";
			}
		else {
			echo "<div data-last-id=\"".$id."\">\n";
		}
		if ($image != 'None') {
			echo "<img src=\"images/".$image."\" alt=\"image\" class=\"hd\" />\n";
			}
		echo "<h3>".$name." says…</h3>\n";
		echo "<small>(".$time." GMT)</small>\n";
		echo "<p>".$content."</p>\n";
		echo "</div>\n\n";
		}
	}
else {
	// No content yet.
	echo "<p>No content found.</p>";
}

?>