<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/order.html");
$query_carr1 = "SELECT DISTINCT prodottifornitore.*,taglia,immaginifornitore.immagine 
    FROM carrellofornitore,prodottifornitore,immaginifornitore 
    WHERE prodottifornitore.id=carrellofornitore.prodotto  AND prodottifornitore.id = immaginifornitore.prodotto AND carrellofornitore.ordinato =0";
$db->query($query_carr1);
if ($db->status == "ERROR") {
    Header('Location: index.php?error=1008');
} else {
    $result = $db->getResult();
    $row['errore'] = "";
    if (!$result) {
        $row['errore'] = "Non ci sono prodotti nel tuo carrello";
    }
    $totale = 0;
    foreach ($result as $row) {
        $row['immagine'] = $row['immagine'];
        $row['marca'] = utf8_encode($row['marca']);
        $row['modello'] = $row['modello'];
        $row['id'] = $row['id'];
        $row['prezzo'] = $row['prezzo'];
        $row['taglia'] = utf8_encode($row['taglia']);
        $totale = number_format($totale + $row['prezzo'], 2, '.', '');
        $body->setContent($row);
    }
    $body->setContent("totale", $totale * 10);
}
adminInject($main, $body);
$main->close();
?>