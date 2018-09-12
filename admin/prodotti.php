<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/prodotti.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);

$query_prod = "SELECT DISTINCT  prodotti.id AS id1,prodotti.*, immagini
.immagine
FROM prodotti, immagini

WHERE prodotti.id = immagini
.prodotto";
$db->query($query_prod);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else {
    $result = $db->getResult();
    if (!$result)
        Header('Location: error.php?id=1005');
        foreach ($result as $row1) {
        $row1['marca'] = utf8_encode($row1['marca']);
        $row1['modello'] = $row1['modello'];
        $row1['prezzo'] = $row1['prezzo'];
        $row1['immagine'] = $row1['immagine'];
        $row1['id1'] = $row1['id1'];
        $body->setContent($row1);
    }
}
adminInject($main, $body);
$main->close();
?>