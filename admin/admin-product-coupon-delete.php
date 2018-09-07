<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-product-coupon-search.html");

  $body->setContent("action_script", "coupon-delete");
  $body->setContent("action_title", "Rimuovi coupon");
  $body->setContent("action_desc", "Da qui puoi rimuovere definitivamente un coupon.");

  if(isset($_GET['coupon-delete']) && ($_GET['coupon-delete'] !== "")) {

    $coupon_delete_query = "DELETE FROM coupon
                            WHERE id_coupon = '{$_GET['coupon-delete']}';";
    
    $db->query($coupon_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?error=1005');

    Header('Location: admin-product-coupon-delete.php?page=1&success='.$_GET['coupon-delete']);

  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-product-coupon-delete.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "Coupon \"".$_GET['success']."\" rimosso con successo!");

    $coupon_search_query = "SELECT coupon.*, prodotto.titolo
                            FROM coupon, prodotto
                            WHERE coupon.id_prodotto = prodotto.id_prodotto
                            AND ( id_coupon LIKE '%' OR prodotto.titolo LIKE '%');";

    $db->query($coupon_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationProducts($body, "coupon-delete", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun prodotto trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
