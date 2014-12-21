/*global $ */
$(function ($) {
  "use strict";
  (function polling() {
    var latest, eventid, number = 0, snd = new Audio("/notification.mp3");
    latest = $("#comments").find("div:last-child").data("last-id") || 0;
    eventid = $("#main-content").find("article:first-child").data("event-id") || 0;
    $.ajax({
      url: "/new_data.php",
      data: { string: latest, eventid: eventid },
      cache: false,
      dataType: "json",
      success: function (json, status, xhr) {
        var div, html;
        div = $("#comments");
        $.each(json, function (k, v) {
          html = "";
          html += "<div class=\"comment\" data-last-id=" + v.id + ">";
          html += "<p class=\"comment-header\">" + v.username + " <small>(" + v.time + ")</small></p>";
          html += "<p>" + v.content + "</p>";
          html += "</div>";
          div.append(html);
          if (localStorage.getItem("username") !== v.username) {
            number = number + 1;
          }
        });
        if (number > 0) {
          snd.play();
        }
        setTimeout(polling, 10e3);
      }
    });
  }());
  document.contact.name.value = localStorage.getItem("username");
});