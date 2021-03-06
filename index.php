<?php
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";
$main = new Template("html/frame-public.html");
$body = new Template("html/index.html");
$query_prod = "SELECT DISTINCT prodotti.*, immagini.immagine,tipologia.nome
                 FROM prodotti, immagini,tipologia
                 WHERE prodotti.id = immagini.prodotto AND tipologia.id = prodotti.tipologia ORDER BY prodotti.id";
$db->query($query_prod);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else {
    $result = $db->getResult();
    if (!$result)
        Header('Location: error.php?id=1006');
    foreach ($result as $row) {
        $row['tipo'] = "style3";
        if ($row['nome'] == "Basket")
            $row['tipo'] = "style2";
        $row['marca'] = utf8_encode($row['marca']);
        $row['modello'] = $row['modello'];
        $row['sconto'] = $row['sconto'];
        $app = $row['prezzo'] - ($row['prezzo']*$row['sconto']/100);
        $row['prezzo'] = number_format($app, 2, ',', ' ');
        if ($row['sconto'] > 0)
            $row['tipo'] = "style1";
        $row['immagine'] = $row['immagine'];
        $row['id'] = $row['id'];
        $body->setContent($row);
    }
}
session_start();
if (isset($_SESSION['user'])) {
    $utente = $_SESSION['user']['id'];
    $query_carr = "SELECT prodotti.*,taglia FROM carrello,prodotti WHERE prodotti.id=carrello.prodotto AND carrello.utente=$utente AND carrello.pagato = 0";
    $db->query($query_carr);
    if ($db->status == "ERROR") {
        $row['errore'] = "Non ci sono prodotti nel tuo carrello";
    } else {
        $result = $db->getResult();
        $row['errore'] = "";
        if (!$result) {
            $row['errore'] = "Non ci sono prodotti nel tuo carrello";
        } else {
            $totale = 0;
            foreach ($result as $row) {
                $row['marca'] = utf8_encode($row['marca']);
                $row['modello'] = $row['modello'];
                $row['id'] = $row['id'];
                $app = $row['prezzo'] - ($row['prezzo']*$row['sconto']/100);
                $row['prezzo'] = number_format($app, 2, ',', ' ');
                $row['taglia'] = $row['taglia'];
                $totale = number_format($totale + $row['prezzo'], 2, '.', '');
                $main->setContent($row);
            }
            $main->setContent("totale", $totale);
        }
    }
}
if (isset($_SESSION['ext'])) {
    $utente = $_SESSION['ext']['id'];
    $query_carr = "SELECT prodotti.*,taglia FROM carrelloext,prodotti WHERE prodotti.id=carrelloext.prodotto AND carrelloext.utente=$utente AND carrelloext.pagato = 0";
    $db->query($query_carr);
    if ($db->status == "ERROR") {
        Header('Location: error.php?id=1005');
    } else {
        $result = $db->getResult();
        $row['errore'] = "";
        if (!$result) {
            $row['errore'] = "Non ci sono prodotti nel tuo carrello";
        } else {
            $totale = 0;
            foreach ($result as $row) {
                $row['marca'] = utf8_encode($row['marca']);
                $row['modello'] = $row['modello'];
                $row['id'] = $row['id'];               
                $app = $row['prezzo'] - ($row['prezzo']*$row['sconto']/100);
                $row['prezzo'] = number_format($app, 2, ',', ' ');
                $row['taglia'] = $row['taglia'];
                $totale = number_format($totale + $row['prezzo'], 2, '.', '');
                $main->setContent($row);
            }
            $main->setContent("totale", $totale);
        }
    }
}
if (isset($_GET['news'])) 
    $body->setContent("news", "Il messaggio è stato ricevuto dai nostri server");
    if (isset($_GET['carr'])) 
    $body->setContent("news", "Il prodotto è stato aggiunto al carrello");
    if (isset($_GET['order'])) 
    $body->setContent("news", "Hai pagato il tuo ordine. A breve riceverai una mail");    
    if (isset($_GET['orderext'])) 
    $body->setContent("news", "Hai pagato il tuo ordine. A breve riceverai una mail con le credenziali per accedere al sito");
    if (isset($_GET['empty'])) 
    $body->setContent("news", "Il carrello è stato svuotato");
if (isset($_GET['logout'])) {
    session_unset();
    Header("Location: ./index.php");
} else if (isset($_SESSION['user'])) {
    inject(TRUE, $main, $body, $db);
} else if (isset($_SESSION['admin'])) {
    Header("Location: ./admin/admin.php");
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