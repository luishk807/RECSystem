<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
<title>Untitled Document</title>
<script type="text/javascript" language="javascript">
$(function() {
  var moveLeft = 30;
  var moveDown = -10;

  $('a#trigger').hover(function(e) {
    $('div#pop-up').show();
  }, function() {
    $('div#pop-up').hide();
  });

  $('a#trigger').mousemove(function(e) {
    $("div#pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
  });

});
</script>
<style>
/* HOVER STYLES */
div#pop-up {
  display: none;
  position: absolute;
  width: 500px;
  height:300px;
  overflow:auto;
  padding: 10px;
  background: #eeeeee;
  color: #000000;
  border: 1px solid #1a1a1a;
  font-size: 90%;
}
</style>
</head>

<body>

<div id="container">
    <h1>jQuery Tutorial - Pop-up div on hover</h1>
    <p>
      To show hidden div, simply hover your mouse over
      <a href="#" id="trigger">this link</a>.
    </p>

    <!-- HIDDEN / POP-UP DIV -->
    <div id="pop-up">
      <h3>Pop-up div Successfully Displayed</h3>
      <p>
        This div only appears when the trigger link is hovered over.
        Otherwise it is hidden from view.
      </p>
    </div>

  </div>
</body>
</html>