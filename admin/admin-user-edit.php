<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-user-search.html");

  $body->setContent("action_script", "edit");
  $body->setContent("action_title", "Modifica dati utente");
  $body->setContent("action_desc", "Da qui puoi modificare i dati di un utente.");

  if(isset($_GET['edit']) && ($_GET['edit'] !== "")) {

    $user_get_query = "SELECT *
                      FROM user
                      WHERE username = '{$_GET['edit']}';";

    $db->query($user_get_query);

    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $user = $db->getResult()[0];
    if($user) {
      $body = new Template("../themes/default/dtml-admin/admin-user-edit.html");
      $body->setContent($user);
    } else
      $body->setContent("notifica", "L'utente '".$_GET['edit']."' non esiste o non può essere modificato.");

  } else if(isset($_POST['username']) && isset($_POST['username-profilo']) && isset($_POST['email']) && isset($_POST['nome']) && isset($_POST['cognome']) &&
      isset($_POST['stato']) && isset($_POST['citta']) && isset($_POST['indirizzo']) && isset($_POST['cap']) && isset($_POST['cellulare']) ) {

    if(empty($_POST['username']) || empty($_POST['username-profilo']) || empty($_POST['email']) || empty($_POST['nome']) || empty($_POST['cognome']) ||
      empty($_POST['stato']) || empty($_POST['citta']) || empty($_POST['indirizzo']) || empty($_POST['cap']) || empty($_POST['cellulare']))
        Header('Location: error.php?id=1005');

    $_POST['username'] = utf8_decode(str_replace("'", "", $_POST['username']));
    $_POST['username-profilo'] = utf8_decode(str_replace("'", "", $_POST['username-profilo']));
    $_POST['email'] = utf8_decode(str_replace("'", "", $_POST['email']));
    $_POST['nome'] = utf8_decode(str_replace("'", "\'", $_POST['nome']));
    $_POST['cognome'] = utf8_decode(str_replace("'", "\'", $_POST['cognome']));
    $_POST['stato'] = utf8_decode(str_replace("'", "\'", $_POST['stato']));
    $_POST['citta'] = utf8_decode(str_replace("'", "\'", $_POST['citta']));
    $_POST['indirizzo'] = utf8_decode(str_replace("'", "\'", $_POST['indirizzo']));
    $_POST['cap'] = str_replace("'", "", $_POST['cap']);
    $_POST['cellulare'] = str_replace("'", "", $_POST['cellulare']);

    $user_update_query = "UPDATE user SET
                          ".(($_POST['nuovapassword']!='') ? 'password=\''.MD5($_POST['nuovapassword']).'\',' : '')."
                          email='{$_POST['email']}',
                          nome='{$_POST['nome']}',
                          cognome='{$_POST['cognome']}',
                          stato='{$_POST['stato']}',
                          citta='{$_POST['citta']}',
                          indirizzo='{$_POST['indirizzo']}',
                          cap='{$_POST['cap']}',
                          cellulare='{$_POST['cellulare']}'
                          WHERE username='{$_POST['username']}';";

    $db->query($user_update_query);

    if($db->status == "ERROR")
      Header('Location: error.php?id=1005');
    else
      Header('Location: admin-user-edit.php?page=1&success='.$_POST['username-profilo']);

  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-user-edit.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "L'utente '".$_GET['success']."' è stato modificato con successo!");

    $user_search_query = "SELECT username
                          FROM user
                          WHERE username LIKE '%';";

    $db->query($user_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationUsers($body, "edit", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun utente trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
