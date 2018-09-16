<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  if (isset($_GET['id'])){
    
    $drop_prod="DELETE FROM prodotti WHERE prodotti.id = '{$_GET['id']}' ";
    $db->query($drop_prod);
    if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');}
    else{
      Header('Location: admin.php?drop=1');

    }
  }
 

?>