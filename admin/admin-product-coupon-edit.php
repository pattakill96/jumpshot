<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-product-coupon-search.html");

  $body->setContent("action_script", "coupon-edit");
  $body->setContent("action_title", "Modifica dettagli coupon");
  $body->setContent("action_desc", "Da qui puoi modificare i dettagli di un coupon.");

  if(isset($_GET['coupon-edit'])) {

    $coupon_get_query = "SELECT coupon.*, prodotto.titolo
                          FROM coupon, prodotto
                          WHERE coupon.id_prodotto = prodotto.id_prodotto
                          AND id_coupon = '{$_GET['coupon-edit']}';";
    
    $db->query($coupon_get_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $result = $db->getResult()[0];
    if($result) {
      $body = new Template("../themes/default/dtml-admin/admin-product-coupon-edit.html");
      $body->setContent($result);
    } else
      $body->setContent("notifica", "Il prodotto non esiste.");

  } else if(isset($_POST['id_coupon'])) {

    if(strlen($_POST['id_coupon']) === 0 || strlen($_POST['id_coupon']) > 10)
        $body->setContent("notifica", "ERRORE: nserire un coupon di lunghezza compresa tra 1 e 10!");
      
    else {
      if(empty($_POST['validita'])) $_POST['validita'] = "NULL";
      if(empty($_POST['sconto'])) $_POST['sconto'] = 0;

      $coupon_edit_query = "UPDATE coupon SET
                            id_coupon = '{$_POST['id_coupon']}',
                            validita = '{$_POST['validita']}',
                            sconto = {$_POST['sconto']}
                            WHERE id_coupon = '{$GET['old']}';";
      
      $db->query($coupon_edit_query);
      if($db->status == "ERROR") Header('Location: error.php?id=1005');

      Header('Location: admin-product-coupon-edit.php?success='.$_POST['id_coupon']);
    }
  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-product-coupon-edit.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "Coupon \"".$_GET['success']."\" modificato con successo!");

    $coupon_search_query = "SELECT coupon.*, prodotto.titolo
                            FROM coupon, prodotto
                            WHERE coupon.id_prodotto = prodotto.id_prodotto
                            AND (id_coupon LIKE '%' OR prodotto.titolo LIKE '%');";

    $db->query($coupon_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationProducts($body, "coupon-edit", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun prodotto trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }


  adminInject($main, $body);

  $main->close();
?>
