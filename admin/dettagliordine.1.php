<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/dettagli-ordine.1.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);
if (isset($_GET['id']) && isset($_GET['t']) && isset($_GET['u'])) {
    if ($_GET['t'] == 1) {
        $body = new Template("../dtml-admin/dettagli-ordine.11.html");
    }
    $ord = $_GET['id'];
    $body->setContent('ord', $ord);
    if ($_GET['u'] == 0) {
        $order = $_GET['id'];
        $body->setContent("order", $order);
        $query_carr1 = "SELECT DISTINCT prodotti.*,taglia,immagini.immagine FROM carrelloext,prodotti,immagini WHERE prodotti.id=carrelloext.prodotto  AND prodotti.id = immagini.prodotto AND carrelloext.ordine = '{$_GET['id']}'";
        $db->query($query_carr1);
        if ($db->status == "ERROR") {
            Header('Location: index.php?error=1008');
        } else {
            $result = $db->getResult();
            $row['errore'] = "";
            if (!$result) {
                $row['errore'] = "Non ci sono prodotti nel tuo carrello";
                exit;
            }
            $totale = 0;
            foreach ($result as $row) {
                $row['immagine'] = $row['immagine'];
                $row['marca'] = utf8_encode($row['marca']);
                $row['modello'] = $row['modello'];
                $row['prezzo'] = $row['prezzo'];
                $row['taglia'] = utf8_encode($row['taglia']);
                $totale = number_format($totale + $row['prezzo'], 2, '.', '');
                $body->setContent($row);
            }
            $body->setContent("totale", $totale);
        }
        $query_pay = "SELECT ordiniext.* FROM ordiniext WHERE ordiniext.id='{$_GET['id']}'";
        $db->query($query_pay);
        if ($db->status == "ERROR") {
            Header('Location: index.php?error=1008');
        } else {
            $result = $db->getResult();
            if ($result) {
                $totale = 0;
                foreach ($result as $row1) {
                    $dest = $row1['nome'] . " " . $row1['cognome'];
                    $pay = $row1['pagamento'];
                    $ind = $row1['indirizzo'] . " " . $row1['citta'] . " " . $row1['CAP'];
                }
                $body->setContent("dest", $dest);
                $body->setContent("ind", $ind);
                $body->setContent("pay", $pay);
            }
        }
    }
}
adminInject($main, $body);
$main->close();
?>