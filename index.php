<?php
require_once 'functions.php';
$home = "/";
if (isset($_GET['page'])) { $page = $_GET['page']; } else { $page = NULL; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Noelinho.org live comments</title>
  <meta name="description" content="The new live comments site for noelinho.org">
  <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
  <link rel="stylesheet" href="/assets/css/style.min.css">
</head>
<body>  
  <a href="/">
  <header class="main-header">
    <h1 class="title">Live events</h1>
  </header>
  </a>
  
  <div class="main-content" id="main-content">
    
    <?php

    if ($page != NULL) {
      $output = NULL;
      $page = "/".$page;
      $sql = "SELECT `id`, `name`, `description`, `excerpt`, `url`, `event-time` as time, `comments-open`, `comments-close` FROM `events` WHERE `url` = :url";
      $query = $dbh->prepare($sql);
      $query->execute(array(':url' => $page));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      if ($query->rowCount() == 1) {
        $eventid = $row['id'];
        $commentsopen = $row['comments-open'];
        $commentsclose = $row['comments-close'];
        $time = gmdate('l d F Y, H:i T', $row['time']);
        //$description = $row['description'];
        $description = $row['excerpt'];
        $output .= "<article class=\"box\" data-event-id=\"".$eventid."\">\n";
        $output .= "<header class=\"article-header\">\n";
        $output .= "<h3>".$row['name']."</h3>";
        $output .="<p class=\"details\">".$time."</p></header><p>".$description."</p>";
        $output .="<div class=\"link\"><a href=\"#bottom\">Jump to bottom</a></div>\n";
        $output .= "</article>\n";
        comments($dbh, $eventid);
        $output .="<a name=\"bottom\"></a>\n";
        commentform();
        echo $output;
      }
      else {
        echo "<p>Error: page not found.</p>";
      }
    
    }

    else {

      $upcomingeventsoutput = upcomingEvents($dbh);
      if (strlen($upcomingeventsoutput) > 0) {
        echo $upcomingeventsoutput;
      }

      $eventsoutput = events($dbh);
      if (strlen($eventsoutput) > 0) {
        echo $eventsoutput;
      }
    
    }

    ?>
    
  </div>
  
  <footer>
    <!--<ul class="external-links">
      <li class="external-links-item"><a href="http://noelinho.org">Noelinho.org</a></li>
      <li class="external-links-item"><a href="https://twitter.com/Noelinho">Twitter</a></li>
      <li class="external-links-item"><a href="http://instagram.com/noelslevin">Instagram</a></li>
      <li class="external-links-item"><a href="https://plus.google.com/+NoelSlevin">Google Plus</a></li>
    </ul>-->
  
    <p class="copyright">
	  © Noel Slevin, 2005 – 2014. All rights reserved.
    </p>
  </footer>
  <script src="/assets/js/comments.noelinho.org.js"></script>
  <?php 
    if (($page != NULL) && (time() > $commentsopen) && (time() < $commentsclose)) {
      echo "<script src=\"/polling.js\"></script>";
    }
  ?>
</body>
</html>
