<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../dtml-admin/frame-public.html");
  $body = new Template("../dtml-admin/product-fornitore.html");
  $row['id'] = $_SESSION['admin']['username'];
  $main->setContent($row);
if(isset($_GET['id'])) {
  $product_detail_query = "SELECT DISTINCT prodottifornitore.*, immaginifornitore.immagine
                          FROM prodottifornitore, immaginifornitore
                          WHERE prodottifornitore.id = {$_GET['id']} AND immaginifornitore.prodotto = {$_GET['id']}  
                          ";

  $db->query($product_detail_query);

  if($db->status == "ERROR") {
print_r($product_detail_query);  } else {
    $result = $db->getResult();
    if(!$result) Header('Location: error.php?id=1005');

    foreach($result as $row) {
      $row['id'] = $row['id'];
      $row['immagine'] = $row['immagine'];
      $row['fornitore'] = $row['fornitore'];
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = utf8_encode($row['modello']);
      $row['prezzo'] = utf8_decode($row['prezzo']);
      $body->setContent($row);
    }
  }
}

adminInject($main, $body);

  $main->close();
?>