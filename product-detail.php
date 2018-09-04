<?php

require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";

$main = new Template("themes/default/dtml/frame-public.html");
$body = new Template("themes/default/dtml/product-detail.html");

if(isset($_GET['id'])) {
  $product_detail_query = "SELECT prodotti.*, immagini.immagine, taglieprodotti.taglia, taglieprodotti.quantita
                          FROM prodotti, taglieprodotti, immagini
                          WHERE prodotti.id_prodotto = {$_GET['id']} AND taglieprodotti.id = {$_GET['id']} AND immagini.prodotto = {$_GET['id']} ";

  $db->query($product_detail_query);

  if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
  } else {
    $result = $db->getResult();
    if(!$result) Header('Location: error.php?id=1005');

    foreach($result as $row) {
      $row['immagine'] = $row['immagine'];
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = utf8_encode($row['modello']);
      $row['tipologia'] = utf8_encode($row['tipologia']);
      $row['prezzo'] = utf8_decode($row['prezzo']);
      if($row['quantita'] <= 0)
        $row['quantita'] = '<span style="color:red">Esaurito</span>';
      else if($row['quantita'] <= 25)
        $row['quantita'] = '<span style="color:#eec81d">Solo '.$row['quantita'].' rimasti!</span>';
      else
        $row['quantita'] = '<span style="color:green">Disponibile</span>';

      $body->setContent($row);
    }
  }
}

session_start();

if(!isset($_SESSION['auth']))
  inject(FALSE, $main, $body, $db);
else
  inject(TRUE, $main, $body, $db, isset($_SESSION['auth']['service']['admin.php']));

$main->close();

?>
