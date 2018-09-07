<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-user-search.html");

  $body->setContent("action_script", "promote");
  $body->setContent("action_title", "Promuovi utente");
  $body->setContent("action_desc", "Da qui puoi promuovere un utente.");
    
  if(isset($_GET['promote']) && ($_GET['promote'])) {
    $user_promote_query = "UPDATE user_groups
                          SET id_groups = id_groups - 1
                          WHERE id_groups > 1
                          AND username = '{$_GET['promote']}';";
    $db->query($user_promote_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');
    else Header('Location: admin-user-promote.php?page=1&success='.$_GET['promote']);
  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-user-promote.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "Utente '".$_GET['success']."' promosso!");

    $user_search_query = "SELECT user.username
                          FROM user, user_groups
                          WHERE user.username = user_groups.username
                          AND id_groups > 1
                          AND user.username LIKE '%';";

    $db->query($user_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationUsers($body, "promote", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun utente trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
