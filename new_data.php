<?php
$since = (isset($_GET['string'])) ? $_GET['string'] : 0;
$since = abs(intval($since));
$string = $_GET['string'];
$eventid = $_GET['eventid'];
$sql = "SELECT `id`, `username`, `content`, `time-submitted` as time FROM `comments` WHERE `event-id` = :eventid AND `id` > :string";
include 'functions.php';
$latest = NULL; // For working out most recent update
$query = $dbh->prepare($sql);
$query->execute(array(':eventid' => $eventid, ':string' => $string));
$result = $query->fetchAll(PDO::FETCH_ASSOC);
$numrows = count($result);
if ($numrows > 0) {
  foreach ($result as $row) {
    $row['time'] = gmdate('H:i:s T', $row['time']);
    $latest[] = $row;
  }
 }
// ensure we return something
if($latest == null) $latest = (object)$latest;

// json encode
$latest = json_encode($latest);
// display
echo $latest;
?>