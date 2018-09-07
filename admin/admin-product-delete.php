<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-product-search.html");

  $body->setContent("action_script", "delete");
  $body->setContent("action_title", "Rimuovi prodotto");
  $body->setContent("action_desc", "Da qui puoi rimuovere un prodotto.");

  if(isset($_GET['delete']) && ($_GET['delete'] !== "")) {

    $product_delete_query = "DELETE FROM carrello
                            WHERE id_prodotto = '{$_GET['delete']}';";
    
    $db->query($product_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?error=1005');

    $get_imgs_query = "SELECT *
                      FROM immagine
                      WHERE id_prodotto = '{$_GET['delete']}';";
                      
    $db->query($get_imgs_query);
    if($db->status == "ERROR") Header('Location: error.php?error=1005');

    $result = $db->getResult();
    if($result)
      foreach($result as $row) {
        if(file_exists($row['percorso'])) {
          chmod($row['percorso'],0755); //Change the file permissions if allowed
          unlink($row['percorso']); //remove the file
        }
      }

    $product_delete_query = "DELETE FROM immagine
                            WHERE id_prodotto = '{$_GET['delete']}';";
    
    $db->query($product_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?error=1005');

    $product_delete_query = "DELETE FROM coupon
                            WHERE id_prodotto = '{$_GET['delete']}';";
    
    $db->query($product_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?error=1005');
    
    if(isset($_GET['all'])) {
      $product_delete_query = "DELETE FROM ordine
                              WHERE id_prodotto = '{$_GET['delete']}';";

      $db->query($product_delete_query);
      if($db->status == "ERROR") Header('Location: error.php?error=1005');

      $product_delete_query = "DELETE FROM acquisto
                              WHERE id_prodotto = '{$_GET['delete']}';";

      $db->query($product_delete_query);
      if($db->status == "ERROR") Header('Location: error.php?error=1005');

      $product_delete_query = "DELETE FROM prodotto
                              WHERE id_prodotto = '{$_GET['delete']}';";

      $db->query($product_delete_query);
      if($db->status == "ERROR") Header('Location: error.php?error=1005');
    }
    Header('Location: admin-product-delete.php?page=1&success=');
  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-product-delete.php?page=1');
  } else {
    if(isset($_GET['success'])) $body->setContent("notifica", "Prodotto rimosso con successo!");

    $product_search_query = "SELECT *
                            FROM prodotto
                            WHERE titolo LIKE '%'
                            ORDER BY titolo;";

    $db->query($product_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationProducts($body, "delete", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun prodotto trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
