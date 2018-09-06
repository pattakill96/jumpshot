<?php

require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/utils.inc.php";

$main = new Template("html/frame-public.html");
$body = new Template("html/product-detail.html");

if(isset($_GET['id'])) {
  $product_detail_query = "SELECT prodotti.*, immagini.immagine, taglieprodotti.taglia, taglieprodotti.quantita
                          FROM prodotti, taglieprodotti, immagini
                          WHERE prodotti.id = {$_GET['id']} AND taglieprodotti.scarpa = {$_GET['id']} AND immagini.prodotto = {$_GET['id']} AND taglieprodotti.quantita > 0 ";

  $db->query($product_detail_query);

  if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
  } else {
    $result = $db->getResult();
    if(!$result) Header('Location: error.php?id=1005');

    foreach($result as $row) {
      $row['id'] = $row['id'];
      $row['immagine'] = $row['immagine'];
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = utf8_encode($row['modello']);
      $row['taglia'] = utf8_encode($row['taglia']);
      $row['prezzo'] = utf8_decode($row['prezzo']);
      $body->setContent($row);
    }
  }
}

session_start();

if(!isset($_SESSION['user']))
  inject(FALSE, $main, $body, $db);
else
  inject(TRUE, $main, $body, $db, FALSE);

$main->close();

?>
