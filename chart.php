<?php
  require "include/dbms.inc.php";
  require "include/template2.inc.php";
  require "include/utils.inc.php";

  session_start();
 
  $main = new Template("html/frame-public.1.html");
  $body = new Template("html/chart.html");
  if(isset($_SESSION['ext'])){
    $utente = $_SESSION['ext']['id'];
    $tab = 'carrelloext';}
  if(isset($_SESSION['user'])){
    $utente = $_SESSION['user']['id'];
    $query_carr="SELECT prodotti.*,taglia FROM carrello,prodotti WHERE prodotti.id=carrello.prodotto AND carrello.utente=$utente";
    $tab = 'carrello'; }
  if(isset($_GET['empty'])){
    $query_empty="DELETE FROM $tab WHERE $tab.utente=$utente";
    $db->query($query_empty);
  if($db->status == "ERROR") {
    Header('Location: index.php?error=1009');
  }
  Header('Location: index.php');
  } 
  if(isset($_GET['id'])){
    $query_empty="DELETE FROM $tab WHERE $tab.utente=$utente AND $tab.prodotto = '{$_GET['id']}' ";
    $db->query($query_empty);
  if($db->status == "ERROR") {
    Header('Location: index.php?error=1009');
  }
  $add_shoe="UPDATE taglieprodotti SET taglieprodotti.quantita = taglieprodotti.quantita +1  WHERE scarpa = '{$_GET['id']}' AND taglia = '{$_GET['t']}'";
  $db->query($add_shoe);
  Header('Location: index.php');
  }

  if(isset($_SESSION['user'])){
    $utente = $_SESSION['user']['id'];
    $db->query($query_carr);
  if($db->status == "ERROR") {
    Header('Location: index.php?error=1008');
  } else {
    $result = $db->getResult();
    $row['errore']="";
    if(!$result) {
    $row['errore']="Non ci sono prodotti nel tuo carrello";
    $body->setContent($row);
    }else{
    $totale=0;
    foreach($result as $row) {
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = $row['modello'];
      $row['id'] = $row['id'];
      $row['prezzo'] = $row['prezzo'];
      $row['taglia'] = utf8_encode($row['taglia']);
      $totale = number_format($totale + $row['prezzo'], 2, '.', '');
      $body->setContent($row);
    }
    $body->setContent("totale", $totale);
  }}
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
    $row['errore']="Non ci sono ordini da te effettuati";
    $body->setContent($row);
    }else{
    $totale=0;
    foreach($result as $row) {
      $row['marca'] = utf8_encode($row['marca']);
      $row['modello'] = $row['modello'];
      $row['id'] = $row['id'];
      $row['prezzo'] = $row['prezzo'];
      $row['taglia'] = $row['taglia'];
      $totale = number_format($totale + $row['prezzo'], 2, '.', '');
      $body->setContent($row);
    }
    $body->setContent("totale", $totale);
  }
  }}

  
  if(isset($_SESSION['user'])){
    inject(TRUE, $main , $body ,$db);}
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