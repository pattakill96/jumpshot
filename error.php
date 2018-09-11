<?php
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";
$main = new Template("html/frame-public.html");
$body = new Template("html/error.html");
$loginForm = new Template("html/form-login.html");
$err = "Impossibile accedere alla pagina: ";
if (isset($_GET['id'])) {
    if ($_GET['id'] == "1001" || $_GET['id'] == "1002") {
        $err .= "Per favore esegui il login per accedere alla pagina!";
        $err .= "<br>Premere <a href='index.php' >qui</a> per tornare alla pagina iniziale.";
    } else if ($_GET['id'] == "1003") {
        $err .= "Non hai i permessi necessari per accedere a questa pagina!";
        $err .= "<br>Premere <a href=\"index.php\">qui</a> per tornare alla home.";
    } else if ($_GET['id'] == "1004") {
        $err .= "Questa pagina non è attiva!";
        $err .= "<br>Premere <a href=\"index.php\">qui</a> per tornare alla home.";
    } else if ($_GET['id'] == "1005") {
        $err .= "Errore nel server!";
        $err .= "<br>Premere <a href=\"index.php\">qui</a> per tornare alla home.";
    } else if ($_GET['id'] == "1007") {
        $err .= "Non hai effettuato nessun ordine!";
        $err .= "<br>Premere <a href=\"index.php\">qui</a> per tornare alla home.";
    }
    else if ($_GET['id'] == "1006") {
        $err .= "Nessun dato presente nel database!";
        $err .= "<br>Premere <a href=\"index.php\">qui</a> per tornare alla home.";
    }
}
$body->setContent("errore", $err);
session_start();
if (!isset($_SESSION['user']))
    inject(FALSE, $main, $body, $db);
else
    inject(TRUE, $main, $body, $db, isset($_SESSION['admin']));
$main->close();
?>