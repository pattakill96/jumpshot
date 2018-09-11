<?php
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";
$main = new Template("html/frame-public.html");
$body = new Template("html/dettagliordine.html");
session_start();
if (isset($_GET['id']) && (isset($_GET['t']))) {
    if (isset($_SESSION['user'])) {
        if (($_GET['t']) == 1) {
            $spedizione = "SELECT spedizione FROM ordinipagati WHERE ordine = '{$_GET['id']}' ";
            $db->query($spedizione);
            if ($db->status == "ERROR") {
                Header('Location: error.php?id=1005');
            } else {
                $result = $db->getResult();
                foreach ($result as $row) {
                    $spedizione = $row['spedizione'];
                }
                $body = new Template("html/dettagliordine.1.html");
                $body->setContent("spedizione", $spedizione);
            }
        }
        $order = $_GET['id'];
        $body->setContent("order", $order);
        $utente = $_SESSION['user']['id'];
        $query_carr1 = "SELECT DISTINCT prodotti.*,taglia,immagini.immagine FROM carrello,prodotti,immagini WHERE prodotti.id=carrello.prodotto AND carrello.utente=$utente AND prodotti.id = immagini.prodotto AND carrello.ordine = '{$_GET['id']}'";
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
    if (isset($_SESSION['ext'])) {
        if (($_GET['t']) == 1) {
            $spedizione = "SELECT spedizione FROM ordinepagatoext WHERE ordine = '{$_GET['id']}' ";
            $db->query($spedizione);
            if ($db->status == "ERROR") {
                Header('LLocation: error.php?id=1005');
            } else {
                $result = $db->getResult();
                foreach ($result as $row) {
                    $spedizione = $row['spedizione'];
                }
                $body = new Template("html/dettagliordine.1.html");
                $body->setContent("spedizione", $spedizione);
            }
        }
        $order = $_GET['id'];
        $body->setContent("order", $order);
        $utente = $_SESSION['ext']['id'];
        $query_carr1 = "SELECT DISTINCT prodotti.*,taglia,immagini.immagine FROM carrelloext,prodotti,immagini WHERE prodotti.id=carrelloext.prodotto AND carrelloext.utente=$utente AND prodotti.id = immagini.prodotto AND carrelloext.ordine = '{$_GET['id']}'";
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
            Header('Location: error.php?id=1005');
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
if (isset($_SESSION['user'])) {
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