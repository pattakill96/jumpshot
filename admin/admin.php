<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../dtml-admin/frame-public.html");
  $body = new Template("../dtml-admin/index.html");


      
      $row['id'] = $_SESSION['admin']['username'];
      $main->setContent($row);
    
  adminInject($main, $body);

  $main->close();
?>
