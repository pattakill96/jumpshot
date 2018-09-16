<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../dtml-admin/frame-public.html");
  $body = new Template("../dtml-admin/modifica-prodotto.html");
  $row['id'] = $_SESSION['admin']['username'];
  $main->setContent($row);
if(isset($_GET['id'])) {
  $product_detail_query = "SELECT DISTINCT prodotti.*, immagini.immagine,descrizioneprodotti.testo
                          FROM prodotti, immagini,descrizioneprodotti
                          WHERE prodotti.id = {$_GET['id']} AND immagini.prodotto = {$_GET['id']}  AND descrizioneprodotti.prodotto = {$_GET['id']} 
                          ";

  $db->query($product_detail_query);

  if($db->status == "ERROR") {
print_r($product_detail_query);  } else {
    $result = $db->getResult();
    if(!$result) Header('Location: error.php?id=1005');

    foreach($result as $row) {
      $row['id'] = $row['id'];
      $row['immagine'] = $row['immagine'];
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = utf8_encode($row['modello']);
      $row['prezzo'] = utf8_decode($row['prezzo']);
      $row['testo'] = $row['testo'];
      $row['sconto'] = $row['sconto'];
      $body->setContent($row);
    }
  }
}

adminInject($main, $body);

  $main->close();
?>