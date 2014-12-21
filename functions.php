<?php

require_once '../mysql.php';

function upcomingEvents($dbh) {
  $output = NULL;
  $upcomingevents = 0;
  $sql = "SELECT `name`, `description`, `url`, `event-time` as time, `comments-open` FROM `events` WHERE `comments-open` > UNIX_TIMESTAMP(NOW()) ORDER BY `comments-open` ASC";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  $numrows = count($result);
  if ($numrows > 0) {
    $output .= "<h2 class=\"section-heading\">Upcoming events</h2>\n";
    $output .= "<table>\n";
    $output .= "<thead>\n<tr><th>Event</th><th>Date</th></tr>\n</thead>\n<tbody>\n";
    foreach ($result as $row) {
      $name = $row['name'];
      $description = $row['description'];
      $url = $row['url'];
      $time = gmdate('l d F Y, H:i T', $row['time']);
      $opens = $row['comments-open'];
      $output .= "<tr><td><a class=\"upcoming-link\"href=\"".$url."\">".$name."</a></td><td>".$time."</td></tr>\n";
    }
    $output .= "</tbody>\n</table>\n";
    return $output;
  }
}

function events($dbh) {
  $output = NULL;
  $pastevents = 0;
  $sql = "SELECT `name`, `excerpt`, `url`, `event-time` as time, `comments-open` FROM `events` WHERE `comments-open` < UNIX_TIMESTAMP(NOW()) ORDER BY `event-time` DESC";
  $stmt = $dbh->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetchAll();
  $numrows = count($result);
  if ($numrows > 0) {
    foreach ($result as $row) {
      $title = $row['name'];
      $time = gmdate('l d F Y, H:i T', $row['time']);
      $excerpt = $row['excerpt'];
      $url = $row['url'];
      $output .= "<article class=\"box\"><header class=\"article-header\"><h3><a href=\"".$url."\">".$title."</a></h3><p class=\"details\">".$time."</p></header><p>".$excerpt."</p></article>";
    }
  }
  return $output;
}

function comments($dbh, $eventid) {
  global $output;
  $sql = "SELECT `id`, `username`, `content`, `time-submitted` as time FROM `comments` WHERE `event-id` = :id";
  $query = $dbh->prepare($sql);
  $query->execute(array(':id' => $eventid));
  $result = $query->fetchAll(PDO::FETCH_ASSOC);
  $numrows = count($result);
    $output .= "<div id=\"comments\">\n";
  if ($numrows > 0) {
    foreach ($result as $row) {
      $time = gmdate('H:i:s T', $row['time']);
      $output .= "<div class=\"comment\" data-last-id=\"".$row['id']."\">\n";
      $output .= "<p class=\"comment-header\">".$row['username']." <small>(".$time.")</small></p>\n";
      $output .= "<p>".$row['content']."</p>\n";
      $output .= "</div>\n";
    }
  }
  else {
    
  }
  $output .= "</div>\n";
  return $output;
}

function commentform() {
  global $commentsopen, $commentsclose, $output, $eventid;
  if (time() > $commentsopen && time() < $commentsclose) {
    $output .= "<div class=\"comment-box\">\n";
    $output .= "<h3>Comment</h3>\n";
    $output .= "<form name=\"contact\" method=\"post\" action=\"#\">\n";
    $output .= "<label for=\"name\" id=\"name_label\">Name</label>\n";
    $output .= "<input type=\"text\" name=\"name\" id=\"name\" size=\"30\" value=\"\" class=\"text-input\" />\n";
    $output .= "<label for=\"email\" id=\"email_label\">Comment</label>\n";
    $output .= "<textarea name=\"email\" id=\"email\" class=\"text-input\"></textarea>\n";
    $output .= " <input type=\"hidden\" id=\"eventid\" name=\"eventid\" value=\"".$eventid."\"> \n";
    $output .= "<input type=\"submit\" name=\"submit\" class=\"button\" id=\"submit_btn\" value=\"Submit Comment\" /><br/>\n";
    $output .= "<label class=\"error\" for=\"name\" id=\"name_error\">Error: please fill in your name.</label>\n";
    $output.= "<label class=\"error\" for=\"email\" id=\"email_error\">Error: the point of commenting is to leave a comment.</label><br/>\n";
    $output .= "</form>\n";
    $output .="<div id=\"message\"></div>\n";
    $output .= "</div>\n";
  }
  else if (time() < $commentsopen) {
    $output .= "<p class=\"info\">Comments for this event are not yet open.</p>";
  }
  else {
    $output .= "<p class=\"info\">Comments for this event are now closed.</p>";
  }
  return $output;
}

?>