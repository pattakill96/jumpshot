<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-user-search.html");

  $body->setContent("action_script", "add-trophy");
  $body->setContent("action_title", "Aggiungi un trofeo a un utente");
  $body->setContent("action_desc", "Da qui puoi aggiungere un trofeo a un utente.");

  if(isset($_GET['add-trophy']) && ($_GET['add-trophy'] !== "")) {

    $user_get_query = "SELECT *
                      FROM user
                      WHERE username = '{$_GET['add-trophy']}';";

    $db->query($user_get_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $user = $db->getResult()[0];
    if($user) {
      $body = new Template("../themes/default/dtml-admin/admin-user-add-trophy.html");
      $body->setContent($user);
    } else
      $body->setContent("notifica", "L'utente '".$_GET['add-trophy']."' non esiste o non può essere modificato.");

  } else if(isset($_GET['username']) && $_GET['username'] !== "" && isset($_POST['trofeo']) && $_POST['trofeo'] !== "") {
    $check = "SELECT livello
              FROM user2trofeo
              WHERE username = '{$_GET['username']}'
              AND livello = {$_POST['trofeo']};";

    $db->query($check);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');
    if($db->getResult()) {
      $body = new Template("../themes/default/dtml-admin/admin-user-add-trophy.html");
      $body->setContent("action", "add-trophy");
      $body->setContent("username", $_GET['username']);
      $body->setContent("notifica", "L'utente '{$_GET['username']}' possiede già il trofeo {$_POST['trofeo']}!");
    } else {
      $set_user_trophy_query = "INSERT INTO `user2trofeo`
                                (`username`, `livello`, `data`)
                                VALUES (
                                  '{$_GET['username']}',
                                  {$_POST['trofeo']},
                                  DATE(NOW())
                                );";
                                
      $db->query($set_user_trophy_query);
      if($db->status == "ERROR") Header('Location: error.php?id=1005');
      else Header('Location: admin-user-add-trophy.php?page=1&success='.$_GET['username'].'&trophy='.$_POST['trofeo']);
    }
  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-user-add-trophy.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "Trofeo {$_GET['trophy']} aggiunto all'utente '{$_GET['success']}' con successo!");

    $user_search_query = "SELECT user.username
                          FROM user
                          WHERE user.username LIKE '%';";

    $db->query($user_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationUsers($body, "add-trophy", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun utente trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
