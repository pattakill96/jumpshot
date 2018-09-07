<?php
  require "../include/dbms.inc.php";
  require "../include/auth.inc.php";

  if(isset($_POST['term']) && (strlen($_POST['term'])>2) && isset($_POST['action'])) {
    $product_search_query = "SELECT *
                             FROM prodotto
                             WHERE titolo LIKE '{$_POST['term']}%'
                             ORDER BY titolo;";

    $db->query($product_search_query);
    if($db->status == "ERROR") {
      header('Content-Type: application/json');
      $response = json_encode(array(
        'status' => 300,
        'message' => 'C\'è stato un errore.'
      ));
    } else {
      $result = $db->getResult();
      $html = '';
      foreach($result as $row) {
        $check = "SELECT id_coupon FROM coupon WHERE id_prodotto = {$row['id_prodotto']};";
        $db->query($check);
        $check = $db->getResult();
        $row['id_coupon'] = ($check) ? $check[0]['id_coupon'] : "";
        $html .= "<div class='4u 12u$(xsmall)' style='text-align:center;margin-bottom:1em;'>
                  <a class='product-click' action='{$_POST['action']}' product='{$row['id_prodotto']}' coupon='{$row['id_coupon']}' titolo='{$row['titolo']}' style='border-bottom: transparent !important;cursor:pointer;'>
                    <img src='../products/{$row['id_prodotto']}/{$row['id_prodotto']}-cover.jpg' width='100em'>
                  </a>
                  <br>
                  <a class='product-click' action='{$_POST['action']}' product='{$row['id_prodotto']}' coupon='{$row['id_coupon']}' titolo='{$row['titolo']}' style='cursor:pointer;'>{$row['titolo']}</a>
                </div>";
      }
      header('Content-Type: application/json');
      $response = json_encode(array(
        'status' => 200,
        'html' => $html
      ));
    }
    echo $response;

  }

?>
