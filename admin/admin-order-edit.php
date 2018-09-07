<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-order-search.html");

  $body->setContent("action_script", "edit");
  $body->setContent("action_title", "Modifica o approva ordine");
  $body->setContent("action_desc", "Da qui puoi modificare i dettagli di un ordine, approvarlo o rimuoverlo.");

  if(isset($_GET['user']) && ($_GET['user'] !== "")) {

    if(isset($_GET['order']) && isset($_GET['delete_prod'])) {
      $delete_product_query = "DELETE FROM prodotto2ordine
                              WHERE id_ordine = {$_GET['order']}
                              AND id_prodotto = {$_GET['delete_prod']};";
      $db->query($delete_product_query);
      if($db->status == "ERROR") Header('Location: error.php?error=1005');

      $new_price_query = "SELECT prodotto.id_prodotto, quantita, prezzo
                          FROM prodotto, prodotto2ordine
                          WHERE prodotto.id_prodotto = prodotto2ordine.id_prodotto
                          AND prodotto2ordine.id_ordine = {$_GET['order']};";

      $db->query($new_price_query);
      $result = $db->getResult();
      if($db->status == "ERROR") Header('Location: error.php?error=1005');
      else if(!$result) Header('Location: admin-order-edit.php?delete='.$_GET['order']);
      else {
        $totale_ordine = 0;
        foreach($result as $row) {
          $row['prezzo_totale'] = number_format($row['quantita'] * $row['prezzo'], 2, '.', '');
          $totale_ordine = number_format($totale_ordine + $row['prezzo_totale'], 2, '.', '');
        }
        $new_price_query = "UPDATE ordine
                            SET totale_ordine = {$totale_ordine}
                            WHERE ordine.id_ordine = {$_GET['order']};";

        $db->query($new_price_query);
        if($db->status == "ERROR") Header('Location: error.php?error=1005');
      }
    }
    $get_id_orders_query = "SELECT id_ordine
                            FROM ordine
                            WHERE username = '{$_GET['user']}'
                            AND id_ordine NOT IN (
                              SELECT id_ordine
                              FROM acquisto
                            )
                            ORDER BY id_ordine DESC;";
    
    $db->query($get_id_orders_query);

    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $result_id_orders = $db->getResult();
    if($result_id_orders) {
      $body = new Template("../themes/default/dtml-admin/admin-order-edit.html");
      $body->setContent("char", "i");
      $body->setContent("text", "tutti gli ordini");
      $body->setContent("username", $_GET['user']);
      foreach($result_id_orders as $order) {
        $get_orders_query = "SELECT ordine.id_ordine, prodotto.id_prodotto, titolo, prezzo, quantita, data_ordine, totale_ordine
                            FROM prodotto, prodotto2ordine, ordine
                            WHERE ordine.id_ordine = {$order['id_ordine']}
                            AND prodotto.id_prodotto = prodotto2ordine.id_prodotto
                            AND prodotto2ordine.id_ordine = ordine.id_ordine
                            ORDER BY data_ordine ASC;";
  
        $db->query($get_orders_query);
        if($db->status == "ERROR") Header('Location: profile.php?error=1005');
  
        $result_orders = $db->getResult();
        if($result_orders) {
          $body->setContent("data_ordine", date("d/m/y", strtotime($result_orders[0]['data_ordine'])));
          $body->setContent("user", $_GET['user']);
          $body->setContent("id_ordine", $result_orders[0]['id_ordine']);
          $body->setContent("totale_ordine", $result_orders[0]['totale_ordine']);
          $body->setContent("user_confirm", $_GET['user']);
          $body->setContent("id_ordine_confirm", $result_orders[0]['id_ordine']);
    
          foreach($result_orders as $row) {
            $row['prezzo_totale'] = number_format($row['quantita'] * $row['prezzo'], 2, '.', '');
    
            $body->setContent("order-id", $row['id_ordine']);
            $body->setContent("id_prodotto", $row['id_prodotto']);
            $body->setContent("titolo", $row['titolo']);
            $body->setContent("quantita", $row['quantita']);
            $body->setContent("prezzo", $row['prezzo']);
            $body->setContent("prezzo_totale", $row['prezzo_totale']);
          }
        }
      }
    } else
      $body->setContent("notifica", "L'utente '{$_GET['user']}' al momento non ha ordini in attesa di essere processati.");

  } else if(isset($_GET['delete']) && ($_GET['delete'] !== "")) {
    $order_delete_query = "DELETE FROM prodotto2ordine
                          WHERE id_ordine = {$_GET['delete']};";
    
    $db->query($order_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $order_delete_query = "DELETE FROM ordine
                          WHERE id_ordine = {$_GET['delete']};";
    
    $db->query($order_delete_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $body->setContent("notifica", "Ordine eliminato con successo!");

  } else if(isset($_GET['purchase']) && ($_GET['purchase'] !== "")) {

    $check = "SELECT id_ordine
              FROM acquisto
              WHERE id_ordine = {$_GET['purchase']};";
    
    $db->query($check);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');
    $result = $db->getResult();

    if($result)
      $body->setContent("notifica", "Errore: ordine già processato!");
    else {
      $new_purchase_query = "INSERT INTO `acquisto`
                            (`id_acquisto`, `id_ordine`, `data`, `admin`)
                            VALUES (
                              NULL,
                              {$_GET['purchase']},
                              DATE(NOW()),
                              '{$_SESSION['auth']['username']}');";
      
      $db->query($new_purchase_query);
      if($db->status == "ERROR") Header('Location: error.php?id=1005');

      $body->setContent("notifica", "Ordine approvato con successo!");
    }
  } else if(isset($_GET['id']) && ($_GET['id'] !== "")) {
    $order_search_query = "SELECT *
                          FROM ordine
                          WHERE id_ordine = {$_GET['id']};";
    
    $db->query($order_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $result = $db->getResult();
    if($result) {
      $body = new Template("../themes/default/dtml-admin/admin-order-edit.html");
      $get_order_query = "SELECT ordine.id_ordine, ordine.username, prodotto.id_prodotto, titolo, prezzo, quantita, data_ordine, totale_ordine
                          FROM prodotto, prodotto2ordine, ordine
                          WHERE ordine.id_ordine = {$_GET['id']}
                          AND prodotto.id_prodotto = prodotto2ordine.id_prodotto
                          AND prodotto2ordine.id_ordine = ordine.id_ordine;";
  
      $db->query($get_order_query);
      if($db->status == "ERROR") Header('Location: profile.php?error=1005');

      $result = $db->getResult();
      if($result) {
        $body->setContent("char", "e n°{$result[0]['id_ordine']}");
        $body->setContent("text", "l'ordine n°<strong>{$result[0]['id_ordine']}</strong>");
        $body->setContent("username", $result[0]['username']);
        $body->setContent("data_ordine", date("d/m/y", strtotime($result[0]['data_ordine'])));
        $body->setContent("user", $result[0]['username']);
        $body->setContent("id_ordine", $result[0]['id_ordine']);
        $body->setContent("totale_ordine", $result[0]['totale_ordine']);
        $body->setContent("user_confirm", $result[0]['username']);
        $body->setContent("id_ordine_confirm", $result[0]['id_ordine']);
  
        foreach($result as $row) {
          $row['prezzo_totale'] = number_format($row['quantita'] * $row['prezzo'], 2, '.', '');
  
          $body->setContent("order-id", $row['id_ordine']);
          $body->setContent("id_prodotto", $row['id_prodotto']) ;
          $body->setContent("titolo", $row['titolo']);
          $body->setContent("quantita", $row['quantita']);
          $body->setContent("prezzo", $row['prezzo']);
          $body->setContent("prezzo_totale", $row['prezzo_totale']);
        }
      }
    } else $body->setContent("notifica", "L'ordine n°{$_GET['id']} non esiste.");

  } else {
    if(!isset($_GET['page']) || $_GET['page'] < 1) Header('Location: admin-order-edit.php?page=1');

    $order_search_query = "SELECT DISTINCT user.username, user.*, id_ordine, data_ordine
                          FROM user, ordine
                          WHERE user.username = ordine.username
                          AND ordine.id_ordine NOT IN (
                              SELECT id_ordine
                              FROM acquisto
                              )
                          AND (user.username LIKE '%' OR id_ordine LIKE '')
                          ORDER BY id_ordine DESC;";

    $db->query($order_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationOrders($body, "order-edit", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun utente/ordine trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
