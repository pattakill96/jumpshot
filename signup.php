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
  } else if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['nome']) && isset($_POST['cognome']) &&
     isset($_POST['stato']) && isset($_POST['citta']) && isset($_POST['indirizzo']) && isset($_POST['cap']) && isset($_POST['cellulare']) ) {

    if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email']) || empty($_POST['nome']) || empty($_POST['cognome']) ||
      empty($_POST['stato']) || empty($_POST['citta']) || empty($_POST['indirizzo']) || empty($_POST['cap']) || empty($_POST['cellulare'])) {
        Header('Location: signup.php?error=1001');
    }

    $_POST['username'] = utf8_decode(str_replace("'", "", $_POST['username']));
    $_POST['email'] = utf8_decode(str_replace("'", "", $_POST['email']));
    $_POST['nome'] = utf8_decode(str_replace("'", "\'", $_POST['nome']));
    $_POST['cognome'] = utf8_decode(str_replace("'", "\'", $_POST['cognome']));
    $_POST['stato'] = utf8_decode(str_replace("'", "\'", $_POST['stato']));
    $_POST['citta'] = utf8_decode(str_replace("'", "\'", $_POST['citta']));
    $_POST['indirizzo'] = utf8_decode(str_replace("'", "\'", $_POST['indirizzo']));
    $_POST['cap'] = str_replace("'", "", $_POST['cap']);
    $_POST['cellulare'] = str_replace("'", "", $_POST['cellulare']);

    $signup_query = "INSERT INTO `user` (`username`, `password`, `email`, `nome`, `cognome`, `stato`, `citta`, `indirizzo`, `cap`, `cellulare`)
                     VALUES ('{$_POST['username']}',
                             '".MD5($_POST['password'])."',
                             '{$_POST['email']}',
                             '{$_POST['nome']}',
                             '{$_POST['cognome']}',
                             '{$_POST['stato']}',
                             '{$_POST['citta']}',
                             '{$_POST['indirizzo']}',
                             '{$_POST['cap']}',
                             '{$_POST['cellulare']}');";

    $db->query($signup_query);

    if($db->status == "ERROR") {
      Header('Location: signup.php?error=1005');
    } else {
      $signup_query = "INSERT INTO `user_groups` (`username`, `id_groups`)
                      VALUES ('{$_POST['username']}', '2');";
      $db->query($signup_query);

      if($db->status == "ERROR") {
        Header('Location: signup.php?error=1005');
      } else {
        session_start();
        $_SESSION['auth'] = $_POST;
        $user_permission_query = "SELECT service.script,
                        service.attivo,
                        service.filtering
                        FROM user
                   LEFT JOIN user_groups
                          ON user_groups.username = user.username
                   LEFT JOIN groups
                          ON groups.id_groups = user_groups.id_groups
                   LEFT JOIN groups_service
                          ON groups_service.id_groups = groups.id_groups
                   LEFT JOIN service
                          ON service.id_service = groups_service.id_service
                       WHERE user.username = '{$_POST['username']}'
                    GROUP BY service.script";

        $db->query($user_permission_query);
        $result = $db->getResult();

        foreach($result as $row) {
            $permission[$row['script']] = $row;
        }

        $_SESSION['auth']['service'] = $permission;

        Header('Location: index.php');
      }
    }
  }

  inject(FALSE, $main, $body, $db);

  $main->close();
?>
