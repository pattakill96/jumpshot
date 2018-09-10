<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/carrello.html");
if (isset($_GET['empty'])) {
    $query_empty = "DELETE FROM carrellofornitore WHERE carrellofornitore.pagato=0";
    $db->query($query_empty);
    if ($db->status == "ERROR") {
        Header('Location: index.php?error=1009');
    }
    Header('Location: index.php');
}
if (isset($_GET['id'])) {
    $query_empty = "DELETE FROM carrellofornitore WHERE carrellofornitore.prodotto = '{$_GET['id']}' ";
    $db->query($query_empty);
    if ($db->status == "ERROR") {
        Header('Location: index.php?error=1009');
    }
}
$query_carr = "SELECT prodottifornitore.*,taglia FROM carrellofornitore,prodottifornitore WHERE prodottifornitore.id=carrellofornitore.prodotto  AND carrellofornitore.ordinato =0";
$db->query($query_carr);
if ($db->status == "ERROR") {
    Header('Location: index.php?error=1009');
} else {
    $result = $db->getResult();
    $row['errore'] = "";
    if (!$result) {
        $row['errore'] = "Non ci sono ordini da te effettuati";
        $body->setContent($row);
    } else {
        $totale = 0;
        foreach ($result as $row) {
            $row['marca'] = utf8_encode($row['marca']);
            $row['modello'] = $row['modello'];
            $row['id'] = $row['id'];
            $row['prezzo'] = $row['prezzo'];
            $row['taglia'] = $row['taglia'];
            $totale = number_format($totale + $row['prezzo'], 2, '.', '');
            $body->setContent($row);
        }
        $body->setContent("totale", $totale * 10);
    }
}
adminInject($main, $body);
$main->close();
?>