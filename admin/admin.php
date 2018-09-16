<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/index.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);
$res_for_page=5;
$query_carr = "SELECT COUNT(ordini.id) AS num FROM ordini,utenti WHERE ordini.utente=utenti.id ";
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
        $cont=$result[0]['num'];
        $number_of_pages=ceil($cont/$res_for_page);

        if (!isset($_GET['page'])) {
            $page = 1;
          } else {
            $page = $_GET['page'];}
            
        $this_page_first_result = ($page-1)*$res_for_page;

        $query_carr = "SELECT ordini.id AS id1,ordini.*, utenti.* FROM ordini,utenti WHERE ordini.utente=utenti.id ORDER BY time DESC LIMIT " .$this_page_first_result. "," .$res_for_page ;
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
        for ($page=1;$page<=$number_of_pages;$page++) {
            $pages = '<a href="admin.php?page=' . $page . '">' . $page . '</a> ';
            $body->setContent("page",$pages);

          }
        }
      }
    }
 }

$query_carr1 = "SELECT COUNT(ordiniext.id) AS num1 FROM ordiniext ORDER BY time DESC ";
$db->query($query_carr1);
if ($db->status == "ERROR") {
    Header('Location: index.php?error=1008');
} else {
    $result1 = $db->getResult();
    $row['errore'] = "";
    if (!$result1) {
        $row['errore'] = "Non ci sono ordini da te effettuati";
        $body->setContent($row);
    } else {
        $cont1=$result1[0]['num1'];
        $number_of_pages1=ceil($cont1/$res_for_page);

        if (!isset($_GET['page1'])) {
            $page1 = 1;
          } else {
            $page1 = $_GET['page1'];}
            
        $this_page_first_result1 = ($page1-1)*$res_for_page;
 $query_carr2 = "SELECT ordiniext.id AS id2,ordiniext.nome AS nome1,ordiniext.cognome AS cognome1,ordiniext.indirizzo AS indirizzo1,
                     ordiniext.totale AS totale1,ordiniext.pagamento AS pagamento1,ordiniext.pagato AS pagato1  FROM ordiniext ORDER BY time DESC LIMIT " .$this_page_first_result1. "," .$res_for_page ;
$db->query($query_carr2);
if ($db->status == "ERROR") {
    Header('Location: index.php?error=1008');
} else {
    $result2 = $db->getResult();
    $row['errore'] = "";
    if (!$result2) {
        $row['errore'] = "Non ci sono ordini da te effettuati";
        $body->setContent($row);}
        else{
         foreach ($result2 as $app) {
             $app['nome1'] = $app['nome1'];
             $app['cognome1'] = $app['cognome1'];
             $app['indirizzo1'] = $app['indirizzo1'];
             $app['totale1'] = $app['totale1'];
             $app['pagamento1'] = $app['pagamento1'];
             $app['id2'] = $app['id2'];
             $app['pagato1'] = $app['pagato1'];
             $body->setContent($app);
    }}
    for ($page1=1;$page1<=$number_of_pages1;$page1++) {
        $pages1 = '<a href="admin.php?page1=' . $page1 . '">' . $page1 . '</a> ';
        $body->setContent("page1",$pages1);

      }
}}}
if (isset($_GET['carr'])) 
    $body->setContent("news", "Il carrello è stato svuotato");
    
if (isset($_GET['ord'])) 
$body->setContent("news", "Ordine effettuato");
if (isset($_GET['drop'])) 
$body->setContent("news", "Prodotto eliminato correttamente");
if (isset($_GET['src'])) 
$body->setContent("news", "L'ordine cercato non è presente nel nostro sistema");
if (isset($_GET['size'])) 
$body->setContent("news", "Taglie aggiornate con successo");
adminInject($main, $body);
$main->close();
?>