$(document).ready(function () {

  $('.text-height-up, .text-height-down').on('click', function (e) { // click to increase or decrease
    e.preventDefault();
    var fontSize = parseInt($(".filecontent").css("font-size"));
    var lineHeight = parseInt($(".filecontent").css("line-height"));
    if ($(this).hasClass('text-height-up')) { // detect the button being clicked
      if (fontSize > 35)
        return;
      fontSize++; // increase the base font size
      lineHeight++;
    } else {
      if (fontSize < 10)
        return;
      fontSize--; // or decrease
      lineHeight--;
    }

    setCookie("fontSize", fontSize, 30);
    setCookie("lineHeight", lineHeight, 30);

    $(".filecontent").css({"fontSize": fontSize + "px"});
    $(".filecontent").css({"line-height": lineHeight + "px"});

  });

  $(".filecontent").css({"fontSize": getCookie('fontSize') + "px"});
  $(".filecontent").css({"line-height": getCookie('lineHeight') + "px"});

});


function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  var expires = "expires=" + d.toGMTString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function deleteCookie(cname) {
  setCookie(cname, "", -1);
}