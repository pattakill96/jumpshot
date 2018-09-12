<?php
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";
$main = new Template("html/frame-public.html");
$body = new Template("html/ordini.html");
$body1 = new Template("html/ordini1.html");
session_start();
if (isset($_SESSION['ext'])) {
    $utente = $_SESSION['ext']['id'];
    $tab = 'ordiniext';
}
if (isset($_SESSION['user'])) {
    $utente = $_SESSION['user']['id'];
    $dati_utente = "SELECT utenti.* FROM utenti WHERE utenti.id=$utente";
    $db->query($dati_utente);
    if ($db->status == "ERROR") {
    } else {
        $result = $db->getResult();
        foreach ($result as $row) {
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $indirizzo = $row['indirizzo'];
        }
        $query_carr = "SELECT ordini.* FROM ordini WHERE ordini.utente=$utente ORDER BY time DESC ";
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
                    $row['nome'] = $nome;
                    $row['cognome'] = $cognome;
                    $row['indirizzo'] = $indirizzo;
                    $row['totale'] = $row['totale'];
                    $row['pagamento'] = $row['pagamento'];
                    $row['id'] = $row['id'];
                    $row['pagato'] = $row['pagato'];
                    $body->setContent($row);
                }
            }
        }
    }
}
if (isset($_SESSION['ext'])) {
    $utente = $_SESSION['ext']['id'];
    $query_carr = "SELECT ordiniext.* FROM ordiniext WHERE ordiniext.utente=$utente ORDER BY time DESC";
    $db->query($query_carr);
    if ($db->status == "ERROR") {
        Header('Location: error.php?id=1005');
    } else {
        $result = $db->getResult();
        $row['errore'] = "";
        if (!$result) {
            $row['errore'] = "Non ci sono ordini da te effettuati";
            $body1->setContent($row);
        } else {
            foreach ($result as $row) {
                $row['nome'] = $row['nome'];
                $row['cognome'] = $row['cognome'];
                $row['indirizzo'] = $row['indirizzo'];
                $row['citta'] = $row['citta'];
                $row['totale'] = $row['totale'];
                $row['pagamento'] = $row['pagamento'];
                $row['id'] = $row['id'];
                $row['pagato'] = $row['pagato'];
                $body1->setContent($row);
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
    inject(FALSE, $main, $body1, $db);
}
$main->close();
?>