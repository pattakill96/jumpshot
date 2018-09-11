<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/ordini.html");
$query_carr = "SELECT carrellofornitore.*,ordinefornitore.totale,prodottifornitore.*,immaginifornitore.* FROM ordinefornitore, carrellofornitore, prodottifornitore, immaginifornitore WHERE carrellofornitore.ordinato=ordinefornitore.id AND carrellofornitore.prodotto = prodottifornitore.id AND carrellofornitore.prodotto=immaginifornitore.prodotto";
$db->query($query_carr);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else {
    $result = $db->getResult();
    $row['errore'] = "";
    if (!$result) {
        $row['errore'] = "Non ci sono ordini da te effettuati";
        $body->setContent($row);
    } else {
        foreach ($result as $row) {
            $row['marca'] = $row['marca'];
            $row['modello'] = $row['modello'];
            $row['taglia'] = $row['taglia'];
            $row['totale'] = $row['totale'];
            $row['id'] = $row['id'];
            $body->setContent($row);
        }
    }
}
adminInject($main, $body);
$main->close();
?>