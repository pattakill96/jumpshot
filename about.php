<?php
  require "include/dbms.inc.php";
  require "include/template2.inc.php";
  require "include/utils.inc.php";


  $main = new Template("html/frame-public.html");
  $body = new Template("html/about.html");
  
  
  session_start();
  if(isset($_GET['logout'])){
    session_unset();
    inject(FALSE, $main , $body ,$db);
  }else if(isset($_SESSION['user'])){
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