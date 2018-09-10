<?php
  require "include/dbms.inc.php";
  require "include/template2.inc.php";
  require "include/utils.inc.php";

  session_start();
  $main = new Template("html/frame-public.1.html");
  $body = new Template("html/order.html");
  $body1 = new Template("html/order1.html");
  if(isset($_SESSION['ext'])){
    $utente = $_SESSION['ext']['id'];}
  if(isset($_SESSION['user'])){
    $utente = $_SESSION['user']['id'];}

  if(isset($_SESSION['user'])){
    $query_carr1="SELECT DISTINCT prodotti.*,taglia,immagini.immagine FROM carrello,prodotti,immagini WHERE prodotti.id=carrello.prodotto AND carrello.utente=$utente AND prodotti.id = immagini.prodotto AND carrello.pagato =0";
    $db->query($query_carr1);
  if($db->status == "ERROR") {
    Header('Location: index.php?error=1008');
  } else {
    $result = $db->getResult();
    $row['errore']="";
    if(!$result) {
    $row['errore']="Non ci sono prodotti nel tuo carrello";
    exit;}
    $totale=0;
    foreach($result as $row) {
        $row['immagine'] = $row['immagine'];
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = $row['modello'];
      $row['id'] = $row['id'];
      $row['prezzo'] = $row['prezzo'];
      $row['taglia'] = utf8_encode($row['taglia']);
      $totale = number_format($totale + $row['prezzo'], 2, '.', '');
      $body->setContent($row);
    }
    $body->setContent("totale", $totale);
  }
  }
    if(isset($_SESSION['ext'])){
    $utente = $_SESSION['ext']['id'];
    $query_carr="SELECT DISTINCT prodotti.*,taglia,immagini.immagine FROM carrelloext,prodotti,immagini  WHERE prodotti.id=carrelloext.prodotto AND carrelloext.utente=$utente AND prodotti.id = immagini.prodotto AND carrelloext.pagato =0";
    $db->query($query_carr);
  if($db->status == "ERROR") {
    Header('Location: index.php?error=1009');
  } else {
    $result = $db->getResult();
    $row['errore']="";
    if(!$result) {
    $row['errore']="Non ci sono prodotti nel tuo carrello";
    exit;}
    $totale=0;
    foreach($result as $row) {
        $row['immagine'] = $row['immagine'];
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = $row['modello'];
      $row['id'] = $row['id'];
      $row['prezzo'] = $row['prezzo'];
      $row['taglia'] = $row['taglia'];
      $totale = number_format($totale + $row['prezzo'], 2, '.', '');
      $body1->setContent($row);
    }
    $body1->setContent("totale", $totale);
  }
  }

  
  if(isset($_SESSION['user'])){
    inject(TRUE, $main , $body ,$db);}
    else if(isset($_GET['product_error'])) {
    if($_GET['product_error'] == 1006)
      $body->setContent("errore_prodotti", "<span style=\"color:red;\">Errore nel server: non ci sono riuscito a recuperare i prodotti.</span>");
    else if($_GET['product_error'] == 1007)
      $body->setContent("errore_prodotti", "<span style=\"color:#31708f;\">Non ci sono prodotti... :(</span>");
  } else {
    inject(FALSE, $main , $body1 ,$db);
  }
  $main->close();
  ?>

  ?>