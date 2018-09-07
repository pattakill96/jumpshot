<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-product-search.html");

  $body->setContent("action_script", "coupon-insert");
  $body->setContent("action_title", "Inserisci nuovo coupon");
  $body->setContent("action_desc", "Da qui puoi inserire un nuovo coupon.");

  if(isset($_GET['coupon-insert'])) {

    if(isset($_POST['id_coupon'])) {
      
      if(strlen($_POST['id_coupon']) === 0 || strlen($_POST['id_coupon']) > 10)
        $body->setContent("notifica", "ERRORE: nserire un coupon di lunghezza compresa tra 1 e 10!");
      
      else {
        if(empty($_POST['validita'])) $_POST['validita'] = "NULL";
        if(empty($_POST['sconto'])) $_POST['sconto'] = 0;
        
        $insert_coupon_query = "INSERT INTO `coupon`
                                (`id_coupon`, `id_prodotto`, `validita`, `sconto`)
                                VALUES (
                                  '{$_POST['id_coupon']}',
                                  {$_GET['coupon-insert']},
                                  '{$_POST['validita']}',
                                  {$_POST['sconto']});";

        $db->query($insert_coupon_query);
        if($db->status == "ERROR")
          $body->setContent("notifica", "Errore nel server, oppure coupon già presente!");

        Header('Location: admin-product-coupon-insert.php?page=1&success='.$_POST['id_coupon']);
      }

    } else {
      $product_get_query = "SELECT id_prodotto, titolo
                            FROM prodotto
                            WHERE id_prodotto = {$_GET['coupon-insert']};";
      
      $db->query($product_get_query);
      if($db->status == "ERROR") Header('Location: error.php?id=1005');

      $result = $db->getResult()[0];
      if($result) {
        $body = new Template("../themes/default/dtml-admin/admin-product-coupon-insert.html");
        $body->setContent($result);
      } else
        Header('Location: error.php?id=1005');
    }
  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-product-coupon-insert.php?page=1');
  } else {
    if(isset($_GET['success'])) $body->setContent("notifica", "Coupon '{$_GET['success']}' inserito correttamente!");
    
    $product_search_query = "SELECT *
                            FROM prodotto
                            WHERE titolo LIKE '%'
                            ORDER BY titolo;";

    $db->query($product_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationProducts($body, "coupon-insert", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun prodotto trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
