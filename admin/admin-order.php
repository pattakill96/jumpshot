<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("..admin/frame-public.html");
  $body = new Template("..admin/admin-order.html");

  adminInject($main, $body);

  $main->close();
?>
