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
     isset($_POST['indirizzo']) && isset($_POST['citta']) && isset($_POST['cap'])) {

    if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['nome']) || empty($_POST['cognome']) ||
      empty($_POST['indirizzo']) || empty($_POST['citta']) || empty($_POST['cap'])) {
        Header('Location: signup.php?error=1001');
    }

    $_POST['username'] = utf8_decode(str_replace("'", "", $_POST['username']));
    $_POST['nome'] = utf8_decode(str_replace("'", "", $_POST['nome']));
    $_POST['cognome'] = utf8_decode(str_replace("'", "\'", $_POST['cognome']));
    $_POST['indirizzo'] = utf8_decode(str_replace("'", "\'", $_POST['indirizzo']));
    $_POST['citta'] = utf8_decode(str_replace("'", "\'", $_POST['citta']));
    $_POST['cap'] = utf8_decode(str_replace("'", "\'", $_POST['cap']));
    $id = rand();

    $signup_query = "INSERT INTO utenti (id, nome, cognome, indirizzo, citta, CAP, username, password)
                     VALUES ('$id',
                             '{$_POST['nome']}',
                             '{$_POST['cognome']}',
                             '{$_POST['indirizzo']}',
                             '{$_POST['citta']}',
                             '{$_POST['cap']}',
                             '{$_POST['username']}',
                             '".MD5($_POST['password'])."');";

    $db->query($signup_query);
    session_start();
    if($db->status == "ERROR") {
      Header('Location: signup.php?error=1005');
    }
      else {
        $_SESSION['user']=$id;
        $_SESSION['nome'] = $_POST['nome'];
        $_SESSION['cognome'] = $_POST['cognome'];
            
        session_start();
        Header('Location: index.php?'.$_SESSION['nome']);
      }
    }

  inject(FALSE, $main, $body, $db);
  $main->close();
?>
