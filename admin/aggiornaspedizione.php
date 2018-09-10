<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  if(isset($_GET['id']) && isset($_POST['spedizione'])){
      $set_spedizione="UPDATE ordinepagatoext SET spedizione = '{$_POST['spedizione']}' WHERE ordine='{$_GET['id']}'";
      $db->query($set_spedizione);
    if($db->status == "ERROR") {
        Header('Location: index.php?error=1008');
      } else {
          Header('Location: admin.php');
  }}