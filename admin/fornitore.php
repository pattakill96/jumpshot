<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/fornitore.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);
$app = 0;
// $fornitore_query = "SELECT * FROM fornitore";
// $db->query($fornitore_query);
// if ($db->status == "ERROR") {
//     Header('Location: error.php?id=1005');
// } else {
//     $result = $db->getResult();
//     $row['errore'] = "";
//     if (!$result) {
//         Header('Location: error.php?id=1005');
//     } else {
//         foreach ($result as $row) {
//             $app = $row['id'];
//             $row['denominazione'] = $row['denominazione'];
//             $row['indirizzo'] = $row['indirizzo'];
//             $body->setContent($row);
//         }
//     }
// }
$query_prod = "SELECT  prodottifornitore.id AS id1,prodottifornitore.*, immaginifornitore.immagine
FROM prodottifornitore, immaginifornitore
WHERE prodottifornitore.id = immaginifornitore.prodotto";
$db->query($query_prod);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else {
    $result = $db->getResult();
    if (!$result)
        Header('Location: error.php?id=1005');
    foreach ($result as $row1) {
        $row1['id3'] = $app;
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