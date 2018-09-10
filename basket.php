<?php
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";
$main = new Template("html/frame-public.html");
$body = new Template("html/index.html");
$app = "Basket";
$query_prod = "SELECT DISTINCT prodotti.*, immagini.immagine
                 FROM prodotti, immagini
                WHERE prodotti.id = immagini.prodotto AND prodotti.tipologia = '{$app}'";
$db->query($query_prod);
if ($db->status == "ERROR") {
    Header('Location: index.php?error=1006');
} else {
    $result = $db->getResult();
    if (!$result)
        Header('Location: index.php?error=1007');
    foreach ($result as $row) {
        $row['tipo'] = "style1";
        if ($row['tipologia'] == "Basket")
            $row['tipo'] = "style2";
        $row['marca'] = utf8_encode($row['marca']);
        $row['modello'] = $row['modello'];
        $row['prezzo'] = $row['prezzo'];
        $row['immagine'] = $row['immagine'];
        $row['id'] = $row['id'];
        $body->setContent($row);
    }
}
session_start();
if (isset($_GET['logout'])) {
    session_unset();
    inject(FALSE, $main, $body, $db);
} else if (isset($_SESSION['user'])) {
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