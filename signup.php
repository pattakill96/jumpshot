<?php
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";
$main = new Template("html/frame-public.html");
$body = new Template("html/signup.html");
if (isset($_GET['error'])) {
    if ($_GET['error'] == 1005)
        $body->setContent("errore", "Errore nel server");
    else if ($_GET['error'] == 1001)
        $body->setContent("errore", "ERRORE! Devi compilare i campi per registrarti");
}
inject(FALSE, $main, $body, $db);
$main->close();
?>