<?php
  require "include/dbms.inc.php";
  require "include/template2.inc.php";
  require "include/utils.inc.php";


  $main = new Template("html/frame-public.html");
  $body = new Template("html/index.html");

  $query_prod = "SELECT DISTINCT prodotti.*, immagini.immagine
                 FROM prodotti, immagini
                 WHERE prodotti.id = immagini.prodotto";
  
  $db->query($query_prod);
  if($db->status == "ERROR") {
    Header('Location: index.php?error=1006');
  } else {
    $result = $db->getResult();
    if(!$result) Header('Location: index.php?error=1007');
    
    foreach($result as $row) {
        $row['tipo']="style1";
      if($row['tipologia']=="Basket")
        $row['tipo'] = "style2";
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = $row['modello'];
      $row['prezzo'] = $row['prezzo'];
      $row['immagine'] = $row['immagine'];
      $row['id'] = $row['id'];
      $body->setContent($row);
    }
  }
  


  session_start();

  if(isset($_SESSION['user'])){
    $utente = $_SESSION['user']['id'];
    $query_carr="SELECT prodotti.*,taglia FROM carrello,prodotti WHERE prodotti.id=carrello.prodotto AND carrello.utente=$utente";
    $db->query($query_carr);
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
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = $row['modello'];
      $row['id'] = $row['id'];
      $row['prezzo'] = $row['prezzo'];
      $row['taglia'] = $row['taglia'];
      $totale = number_format($totale + $row['prezzo'], 2, '.', '');
      $main->setContent($row);
    }
    $main->setContent("totale", $totale);
  }
  }
  if(isset($_SESSION['ext'])){
    $utente = $_SESSION['ext']['id'];
    $query_carr="SELECT prodotti.*,taglia FROM carrelloext,prodotti WHERE prodotti.id=carrelloext.prodotto AND carrelloext.utente=$utente";
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
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = $row['modello'];
      $row['id'] = $row['id'];
      $row['prezzo'] = $row['prezzo'];
      $row['taglia'] = $row['taglia'];
      $totale = number_format($totale + $row['prezzo'], 2, '.', '');
      $main->setContent($row);
    }
    $main->setContent("totale", $totale);
  }
  }
  

  if(isset($_GET['logout'])){
    session_unset();
    Header("Location: ./index.php");
  }else if(isset($_SESSION['user'])){
    inject(TRUE, $main , $body ,$db);}
    else if(isset($_SESSION['admin'])){
      Header("Location: ./admin/admin.php");
    }
    else if(isset($_GET['product_error'])) {
    if($_GET['product_error'] == 1006)
      $body->setContent("errore_prodotti", "<span style=\"color:red;\">Errore nel server: non ci sono riuscito a recuperare i prodotti.</span>");
    else if($_GET['product_error'] == 1007)
      $body->setContent("errore_prodotti", "<span style=\"color:#31708f;\">Non ci sono prodotti... :(</span>");
  } else {
    inject(FALSE, $main , $body ,$db);
  }
  $main->close();
  ?>