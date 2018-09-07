<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-user.html");

  adminInject($main, $body);

  $main->close();
?>
