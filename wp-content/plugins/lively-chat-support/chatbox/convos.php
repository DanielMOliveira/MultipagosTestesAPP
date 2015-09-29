<?php
  
  if (isset($_GET["open"])) { setcookie("livelychatsupport_convo_open", $_GET["open"], mktime(0, 0, 0, 12, 31, date("Y") + 2), "/"); }
  
?>