<?php

include 'functions.php';

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
  $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}

// These functions remove all html tags from comments.
$name = htmlspecialchars(strip_tags($_POST['name']));
$content = htmlspecialchars(strip_tags($_POST['email']));
$unixtime = time('now');
$eventid = $_POST['eventid'];
$ip = $_SERVER['REMOTE_ADDR'];

if ((ctype_digit($eventid)) && (strlen(trim($name)) > 0) && (strlen(trim($content)) > 0)) {
  // Eventid is a valid number
  $sql = "INSERT INTO `comments` (`event-id`, `username`, `content`, `time-submitted`, `ip-address`) VALUES (:eventid, :name, :content, :unixtime, :ip)";
  $query = $dbh->prepare($sql);
  $query->execute(array(':eventid' => $eventid, ':name' => $name, ':content' => $content, ':unixtime' => $unixtime, ':ip' => $ip));
  $result = $query->fetch(PDO::FETCH_ASSOC);
  if ($query->rowCount() == 1) {
    // Comment submitted.
  }
  else {
	// Error! One row, and one row only, should be entered.
    header('HTTP/1.1 500 Internal Server Error');
  }
}
else {
  // Error! Eventid is not valid
  header('HTTP/1.1 500 Internal Server Error');
}

?>