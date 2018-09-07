<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-user-search.html");

  $body->setContent("action_script", "demote");
  $body->setContent("action_title", "Retrocedi utente");
  $body->setContent("action_desc", "Da qui puoi retrocedere un utente.");

  if(isset($_GET['demote']) && ($_GET['demote'] !== "")) {

    $user_demote_query = "UPDATE user_groups
                          SET id_groups = id_groups + 1
                          WHERE id_groups = 1
                          AND username = '{$_GET['demote']}';";
    
    $db->query($user_demote_query);

    if($db->status == "ERROR") Header('Location: error.php?id=1005');
    else Header('Location: admin-user-demote.php?page=1&success='.$_GET['demote']);

  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-user-demote.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "L'utente '".$_GET['success']."' è stato retrocesso con successo!");

    $user_search_query = "SELECT user.username
                          FROM user, user_groups
                          WHERE user.username = user_groups.username
                          AND id_groups = 1
                          AND user.username LIKE '%'
                          AND user.username <> '{$_SESSION['auth']['username']}';";

    $db->query($user_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationUsers($body, "demote", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun utente trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
