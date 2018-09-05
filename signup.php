<?php
  require "include/dbms.inc.php";
  require "include/template2.inc.php";
  require "include/utils.inc.php";

  $main = new Template("html/frame-public.html");
  $body = new Template("html/signup.html");

  if(isset($_GET['error'])) {
    if($_GET['error'] == 1005)
      $body->setContent("errore", "Errore nel server");
    else if($_GET['error'] == 1001)
      $body->setContent("errore", "Devi compilare i campi per registrarti");
  } else if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['nome']) && isset($_POST['cognome']) &&
     isset($_POST['indirizzo']) && isset($_POST['citta']) && isset($_POST['cap']) && isset($_POST['taglia']) ) {

    if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['nome']) || empty($_POST['cognome']) ||
      empty($_POST['indirizzo']) || empty($_POST['citta']) || empty($_POST['cap']) ||  empty($_POST['taglia'])) {
        Header('Location: signup.php?error=1001');
    }

    $_POST['username'] = utf8_decode(str_replace("'", "", $_POST['username']));
    $_POST['nome'] = utf8_decode(str_replace("'", "", $_POST['nome']));
    $_POST['cognome'] = utf8_decode(str_replace("'", "\'", $_POST['cognome']));
    $_POST['indirizzo'] = utf8_decode(str_replace("'", "\'", $_POST['indirizzo']));
    $_POST['citta'] = utf8_decode(str_replace("'", "\'", $_POST['citta']));
    $_POST['cap'] = utf8_decode(str_replace("'", "\'", $_POST['cap']));
    $_POST['taglia'] = str_replace("'", "", $_POST['taglia']);

    $signup_query = "INSERT INTO `utenti` (`nome`, `cognome`, `indirizzo`, `citta`, `CAP`, `username`, `password`, `tagliaScarpe`)
                     VALUES ('{$_POST['nome']}',
                             '{$_POST['cognome']}',
                             '{$_POST['indirizzo']}',
                             '{$_POST['citta']}',
                             '{$_POST['cap']}',
                             '{$_POST['username']}',
                             '".MD5($_POST['password'])."',
                             '{$_POST['taglia']}');";

    $db->query($signup_query);
    session_start();
    if($db->status == "ERROR") {
      Header('Location: signup.php?error=1005');
    }

      if($db->status == "ERROR") {
        Header('Location: signup.php?error=1005');
      } else {
        
        session_start();
        $permission = TRUE;
        $_SESSION['auth']['service'] = $permission;

        Header('Location: index.php');
      }
    }

  inject(FALSE, $main, $body, $db);
  $main->close();
?>
