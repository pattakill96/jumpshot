<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-product-search.html");

  $body->setContent("action_script", "review-delete");
  $body->setContent("action_title", "Elimina recensione");
  $body->setContent("action_desc", "Da qui puoi eliminare una recensione.");

  if(isset($_GET['review-delete']) && ($_GET['review-delete'] !== "")) {

    $product_reviews_query = "SELECT DISTINCT recensione.*, prodotto.titolo, immagine.percorso
                              FROM recensione, prodotto, immagine
                              WHERE recensione.id_prodotto = {$_GET['review-delete']}
                              AND prodotto.id_prodotto = {$_GET['review-delete']}
                              AND immagine.id_prodotto = {$_GET['review-delete']}
                              AND immagine.percorso LIKE '%wallpaper%';";

    $db->query($product_reviews_query);

    if($db->status == "ERROR") {
      Header('Location: error.php?id=1005');
    } else {
      $result = $db->getResult();
      if(!$result)
        $body->setContent("notifica", "Non ci sono recensioni per questo prodotto.");
      else {
        $body = new Template("../themes/default/dtml-admin/admin-product-reviews.html");

        foreach($result as $row) {
          $row['titolo'] = utf8_encode($row['titolo']);
          $row['titolo_recensione'] = utf8_encode($row['titolo_recensione']);
          $row['descrizione'] = mb_strimwidth(utf8_encode($row['descrizione']), 0, 300, "...");
          $row['data'] = date("d/m/y", strtotime($row['data']));
          $check = "SELECT id_recensione FROM likes WHERE id_recensione = {$row['id_recensione']};";
          $db->query($check);
          $check = $db->getResult();
          if($check) {
            $likes = "SELECT id_recensione, COUNT(username) AS num_likes
                      FROM likes
                      WHERE id_recensione = {$row['id_recensione']}
                      GROUP BY id_recensione;";
            $db->query($likes);
            $likes = $db->getResult()[0]['num_likes'];
          } else $likes = 0;
          $row['num_likes'] = $likes;
    
          $body->setContent($row);
        }
      }
    }
  } else if(isset($_GET['delete']) && ($_GET['delete'] !== "")) {

    $delete_review_query = "DELETE FROM likes
                            WHERE id_recensione = {$_GET['delete']};";

    $db->query($delete_review_query);

    if($db->status == "ERROR") Header('Location: error.php?id=1005');
    else {
      $delete_review_query = "DELETE FROM recensione
                              WHERE id_recensione = {$_GET['delete']};";
  
      $db->query($delete_review_query);
      if($db->status == "ERROR") Header('Location: error.php?id=1005');
      else Header('Location: admin-product-review-delete.php?page=1&success');
    }
  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-product-review-delete.php?page=1');
  } else {
    if(isset($_GET['success'])) $body->setContent("notifica", "Prodotto rimosso con successo!");

    $product_search_query = "SELECT DISTINCT prodotto.*
                            FROM prodotto, recensione
                            WHERE prodotto.titolo LIKE '%'
                            AND prodotto.id_prodotto = recensione.id_prodotto
                            ORDER BY prodotto.titolo;";

    $db->query($product_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationProducts($body, "review-delete", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun prodotto trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
