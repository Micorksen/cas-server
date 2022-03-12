/*$('.toggle').on('click', function() {
  $('.container').stop().addClass('active');
});

$('.close').on('click', function() {
  $('.container').stop().removeClass('active');
});*/
(function() {
  // Check if cookies are enabled
  if (!("cookie" in document && (document.cookie.length > 0 ||
  (document.cookie = "test").indexOf.call(document.cookie, "test") > -1))) {
      var cookieDiv = document.getElementById("cookiesDisabled")
      if (cookieDiv) {
          cookieDiv.style.display = "block";
      }
  }
  var capsDiv = document.getElementById("capsOn")

  document.onkeypress = function ( e ) {
      e = (e) ? e : window.event;

      var kc = ( e.keyCode ) ? e.keyCode : e.which; // get keycode
      var isUp = !!(kc >= 65 && kc <= 90); // uppercase
      var isLow = !!(kc >= 97 && kc <= 122); // lowercase
      var isShift = ( e.shiftKey ) ? e.shiftKey : ( (kc == 16) ); // shift is pressed -- works for IE8-

      // uppercase w/out shift or lowercase with shift == caps lock
      if (capsDiv) {
          if ((isUp && !isShift) || (isLow && isShift)) {
              capsDiv.style.display = "block";
          } else {
              capsDiv.style.display = "none";
          }
      }

  }
})();