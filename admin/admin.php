<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/index.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);
$query_carr = "SELECT ordini.id AS id1,ordini.*, utenti.* FROM ordini,utenti WHERE ordini.utente=utenti.id ORDER BY time DESC";
$db->query($query_carr);
if ($db->status == "ERROR") {
    Header('Location: index.php?error=1008');
} else {
    $result = $db->getResult();
    $row['errore'] = "";
    if (!$result) {
        $row['errore'] = "Non ci sono ordini da te effettuati";
        $body->setContent($row);
    } else {
        foreach ($result as $row) {
            $row['nome'] = $row['nome'];
            $row['cognome'] = $row['cognome'];
            $row['indirizzo'] = $row['indirizzo'];
            $row['totale'] = $row['totale'];
            $row['pagamento'] = $row['pagamento'];
            $row['id'] = $row['id'];
            $row['id1'] = $row['id1'];
            $row['pagato'] = $row['pagato'];
            $body->setContent($row);
        }
    }
}
$query_carr1 = "SELECT ordiniext.id AS id2,ordiniext.nome AS nome1,ordiniext.cognome AS cognome1,ordiniext.indirizzo AS indirizzo1,
                    ordiniext.totale AS totale1,ordiniext.pagamento AS pagamento1,ordiniext.pagato AS pagato1  FROM ordiniext ORDER BY time DESC";
$db->query($query_carr1);
if ($db->status == "ERROR") {
    Header('Location: index.php?error=1008');
} else {
    $result1 = $db->getResult();
    if (!$result1) {
    } else {
        foreach ($result1 as $app) {
            $app['nome1'] = $app['nome1'];
            $app['cognome1'] = $app['cognome1'];
            $app['indirizzo1'] = $app['indirizzo1'];
            $app['totale1'] = $app['totale1'];
            $app['pagamento1'] = $app['pagamento1'];
            $app['id2'] = $app['id2'];
            $app['pagato1'] = $app['pagato1'];
            $body->setContent($app);
        }
    }
}
if (isset($_GET['carr'])) 
    $body->setContent("news", "Il carrello è stato svuotato");
    
if (isset($_GET['ord'])) 
$body->setContent("news", "Ordine effettuato");
if (isset($_GET['size'])) 
$body->setContent("news", "Taglie aggiornate con successo");
adminInject($main, $body);
$main->close();
?>