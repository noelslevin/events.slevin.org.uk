/*global $ */
$(function () {
  "use strict";
  $('.error').hide();
  $('#message').hide();
  $('input.text-input').css({backgroundColor: "#FFFFFF"});
  $('input.text-input').focus(function () {
    $(this).css({backgroundColor: "#CCCCCC"});
  });
  $('input.text-input').blur(function () {
    $(this).css({backgroundColor: "#FFFFFF"});
  });
  $(".button").click(function () {
    $('.error').hide();
    var name = $("input#name").val(), email = $("textarea#email").val(), eventid = $("input#eventid").val(); //dataString = 'name=' + name + '&email=' + email + '&eventid=' + eventid;
    if (name === "") {
      $("label#name_error").show();
      $("input#name").focus();
      return false;
    }
    if (email === "") {
      $("label#email_error").show();
      $("textarea#email").focus();
      return false;
    }
    document.getElementById('submit_btn').disabled = true;
    localStorage.setItem("username", name);
    $.ajax({
      type: "POST",
      url: "/submit_comment.php",
      data: {"name": name, "email": email, "eventid": eventid},
      success: function (data) {
        $('#message').html("<div id='success'></div>");
        $('#message').html("<strong>Comment submitted!</strong>").hide().fadeIn(1000);
        window.setTimeout(function () {
          $('#message').fadeOut(2000);
        }, 3000);
        document.contact.email.value = '';
        document.contact.email.focus();
        document.getElementById('submit_btn').disabled = false;
      },
      failure: function (data) {
        $('#contact_form').html("<div id='message'></div>");
        $('#message').html("<strong>Error: comment not submitted.</strong>").hide().fadeIn(1000);
        window.setTimeout(function () {
          $('#message').fadeOut(2000);
        }, 3000);
        document.getElementById('submit_btn').disabled = false;
      }
    });
    return false;
  });
});