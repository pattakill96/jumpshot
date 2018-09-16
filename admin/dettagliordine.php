<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/dettagli-ordine.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);
if (isset($_GET['id']) && isset($_GET['t']) && isset($_GET['user'])) {
    if ($_GET['t'] == 1) {
        $body = new Template("../dtml-admin/dettagli-ordine.11.html");
    }
    $ord = $_GET['id'];
    $body->setContent('ord', $ord);
        $utente = $_GET['user'];
        $query_carr1 = "SELECT DISTINCT prodotti.*,taglia,immagini.immagine FROM carrello,prodotti,immagini WHERE prodotti.id=carrello.prodotto AND carrello.utente=$utente AND prodotti.id = immagini.prodotto AND carrello.ordine = '{$_GET['id']}'";
        $db->query($query_carr1);
        if ($db->status == "ERROR") {
            Header('Location: error.php?id=1005');
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
                $app = $row['prezzo'] - ($row['prezzo']*$row['sconto']/100);
                $row['prezzo'] = number_format($app, 2, ',', ' ');
                $row['taglia'] = utf8_encode($row['taglia']);
                $totale = number_format($totale + $row['prezzo'], 2, '.', '');
                $body->setContent($row);
            }
            $body->setContent("totale", $totale);
        }
        $query_user = "SELECT utenti.* FROM utenti WHERE utenti.id=$utente";
        $db->query($query_user);
        if ($db->status == "ERROR") {
            Header('Location: error.php?id=1005');
        } else {
            $result = $db->getResult();
            if ($result) {
                $totale = 0;
                foreach ($result as $row1) {
                    $dest = $row1['nome'] . " " . $row1['cognome'];
                    $ind = $row1['indirizzo'] . " " . $row1['citta'] . " " . $row1['CAP'];
                }
                $body->setContent("dest", $dest);
                $body->setContent("ind", $ind);
            }
        }
        $query_pay = "SELECT ordini.pagamento FROM ordini WHERE ordini.id='{$_GET['id']}'";
        $db->query($query_pay);
        if ($db->status == "ERROR") {
            Header('Location: error.php?id=1005');
        } else {
            $result = $db->getResult();
            if ($result) {
                $totale = 0;
                foreach ($result as $row1) {
                    $pay = $row1['pagamento'];
                }
                $body->setContent("pay", $pay);
            }
        }    
    }
adminInject($main, $body);
$main->close();
?>
