<?php
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";
$main = new Template("html/frame-public.1.html");
$body = new Template("html/order.html");
session_start();
if (isset($_GET['error'])) {
    if ($_GET['error'] == 1005)
        $body->setContent("errore", "Errore nel server");
    else if ($_GET['error'] == 1001)
        $body->setContent("errore", "Devi compilare i campi per registrarti");
} else if ($_GET['id'] == 102) {
    if (isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['indirizzo']) && isset($_POST['citta']) && isset($_POST['cap']) && isset($_POST['payment'])) {
        if (empty($_POST['nome']) || empty($_POST['cognome']) || empty($_POST['username']) || empty($_POST['payment']) || empty($_POST['indirizzo']) || empty($_POST['citta']) || empty($_POST['cap'])) {
            Header('Location: chechout.php?error=1001');
        } else if (isset($_SESSION['ext']['id'])) {
            $_POST['nome'] = utf8_decode(str_replace("'", "", $_POST['nome']));
            $_POST['cognome'] = utf8_decode(str_replace("'", "\'", $_POST['cognome']));
            $_POST['indirizzo'] = utf8_decode(str_replace("'", "\'", $_POST['indirizzo']));
            $_POST['citta'] = utf8_decode(str_replace("'", "\'", $_POST['citta']));
            $app = rand();
            $add_to_order1 = "INSERT INTO ordiniext (id,utente,nome,cognome,indirizzo,citta,CAP,username,password,totale,pagamento)
                                    VALUES ($app,
                                    '{$_SESSION['ext']['id']}',                                    
                                    '{$_POST['nome']}',
                                    '{$_POST['cognome']}',
                                    '{$_POST['indirizzo']}',
                                    '{$_POST['citta']}',
                                    '{$_POST['cap']}',
                                    '{$_POST['username']}',
                                    '{$_POST['password']}',
                                    '{$_GET['t']}',                                    
                                    '{$_POST['payment']}');";
            $db->query($add_to_order1);
            if ($db->status == "ERROR") {
                Header('Location: error.php?id=1005');
            } else {
                $change_carr = "UPDATE carrelloext SET pagato = 1, ordine =$app
               WHERE utente='{$_SESSION['ext']['id']}' AND pagato=0;";
                $db->query($change_carr);
                Header('Location: ./ordini.php');
            }
        }
    }
} else if ($_GET['id'] == 101) {
    if (isset($_POST['payment'])) {
        if (isset($_SESSION['user'])) {
            $app = rand();
            $add_to_order = "INSERT INTO ordini (id,utente,totale,pagamento)
                                            VALUES ($app,'{$_SESSION['user']['id']}','{$_GET['t']}','{$_POST['payment']}');";
            $db->query($add_to_order);
            if ($db->status == "ERROR") {
                Header('Location: error.php?id=1005');
            } else {
                $change_carr = "UPDATE carrello SET pagato = 1, ordine =$app
               WHERE utente='{$_SESSION['user']['id']}' AND pagato =0;";
                $db->query($change_carr);
                Header('Location:  ./ordini.php');
            }
        }
    }
}
if (isset($_SESSION['user'])) {
    inject(TRUE, $main, $body, $db);
} else if (isset($_GET['product_error'])) {
    if ($_GET['product_error'] == 1006)
        $body->setContent("errore_prodotti", "<span style=\"color:red;\">Errore nel server: non ci sono riuscito a recuperare i prodotti.</span>");
    else if ($_GET['product_error'] == 1007)
        $body->setContent("errore_prodotti", "<span style=\"color:#31708f;\">Non ci sono prodotti... :(</span>");
} else {
    inject(FALSE, $main, $body, $db);
}
$main->close();
?>