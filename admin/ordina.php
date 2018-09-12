<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);
$query_carr1 = "SELECT SUM(prodottifornitore.prezzo)as tot,taglia, prodottifornitore.id  FROM prodottifornitore,carrellofornitore WHERE prodottifornitore.id=carrellofornitore.prodotto AND carrellofornitore.ordinato=0";
$db->query($query_carr1);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else {
    $result = $db->getResult();
    $row['errore'] = "";
    if (!$result) {
        $row['errore'] = "Non ci sono prodotti nel tuo carrello";
        exit;
    }
    foreach ($result as $row) {
        $taglia = $row['taglia'];
        $tot = $row['tot'] * 10;
        $id = $row['id'];
    }
}
$app = rand();
$set_order = "INSERT INTO ordinefornitore (id, totale) VALUES ('$app','$tot');";
$db->query($set_order);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else {
    $set_taglia = "UPDATE taglieprodotti SET  taglieprodotti.quantita = taglieprodotti.quantita +10 WHERE taglia=$taglia AND scarpa = $id ";
    $db->query($set_taglia);
    if ($db->status == "ERROR") {
        Header('Location: ierror.php?id=1005');
    } else {
        $result = $db->getResult();
        if (!$result) {
            $set_taglia = "INSERT INTO taglieprodotti (scarpa,taglia,quantita) VALUES ('$id','$taglia','10');";
            $db->query($set_taglia);
        }
    }
    $set_carr = "UPDATE carrellofornitore SET  ordinato = $app WHERE ordinato=0 ";
    $db->query($set_carr);
    if ($db->status == "ERROR") {
        Header('Location: error.php?id=1005');
    } else {
        Header('Location: admin.php');
    }
}
adminInject($main, $body);
$main->close();
?>