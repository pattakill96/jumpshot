<?php
  require "../include/dbms.inc.php";
  require "../include/auth.inc.php";

  if(isset($_POST['orderID']) && isset($_POST['productID']) && isset($_POST['qnt'])) {

    $set_product_qnt = "UPDATE prodotto2ordine
                        SET quantita = {$_POST['qnt']}
                        WHERE id_ordine = {$_POST['orderID']} AND id_prodotto = {$_POST['productID']}";

    $db->query($set_product_qnt);
    if($db->status == "ERROR") {
      header('Content-Type: application/json');
      $response = json_encode(array(
        'status' => 300,
        'message' => 'C\'è stato un errore durante l\'aggiornamento della quantità.'
      ));
    } else {
      $new_price_query = "SELECT prodotto.id_prodotto, quantita, prezzo
                          FROM prodotto, prodotto2ordine
                          WHERE prodotto.id_prodotto = prodotto2ordine.id_prodotto
                          AND prodotto2ordine.id_ordine = {$_POST['orderID']};";

      $db->query($new_price_query);
      $result = $db->getResult();

      if($db->status == "ERROR" || !$result) {
        header('Content-Type: application/json');
        $response = json_encode(array(
          'status' => 300,
          'message' => 'C\'è stato un errore durante l\'aggiornamento della quantità.'
        ));
      } else {
        $totale_ordine = 0;
        foreach($result as $row) {
          $row['prezzo_totale'] = number_format($row['quantita'] * $row['prezzo'], 2, '.', '');
          $totale_ordine = number_format($totale_ordine + $row['prezzo_totale'], 2, '.', '');
        }
        $new_price_query = "UPDATE ordine
                            SET totale_ordine = {$totale_ordine}
                            WHERE ordine.id_ordine = {$_POST['orderID']};";

        $db->query($new_price_query);
        if($db->status == "ERROR") {
          header('Content-Type: application/json');
          $response = json_encode(array(
            'status' => 300,
            'message' => 'C\'è stato un errore durante l\'aggiornamento della quantità.'
          ));
        }
      }
      header('Content-Type: application/json');
      $response = json_encode(array(
        'status' => 200,
        'message' => "Quantità aggiornata."
      ));
    }
    echo $response;

  }

?>
