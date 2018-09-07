<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-user-search.html");

  $body->setContent("action_script", "delete");
  $body->setContent("action_title", "Elimina utente");
  $body->setContent("action_desc", "Da qui puoi eliminare definitivamente un utente.");
  
  if(isset($_GET['delete']) && ($_GET['delete'] !== "")) {

    $user_delete_query = "DELETE FROM user_groups
                          WHERE username = '{$_GET['delete']}';";
    
    $db->query($user_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?error=1005');

    $user_delete_query = "DELETE FROM user
                          WHERE username = '{$_GET['delete']}';";
    
    $db->query($user_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?error=1005');
    else Header('Location: admin-user-delete.php?page=1&success='.$_GET['delete']);

  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-user-delete.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "L'utente '".$_GET['success']."' è stato rimosso con successo!");

    $user_search_query = "SELECT *
                          FROM user
                          WHERE username LIKE '%'
                          AND username <> '{$_SESSION['auth']['username']}';";

    $db->query($user_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationUsers($body, "delete", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun utente trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
