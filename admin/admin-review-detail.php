<?php

require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";

$main = new Template("../themes/default/dtml-admin/frame-public.html");
$body = new Template("../themes/default/dtml-admin/admin-review-detail.html");

if(isset($_GET['id'])) {
  $review_detail_query = "SELECT recensione.*, prodotto.titolo, immagine.percorso
                          FROM recensione, prodotto, immagine
                          WHERE recensione.id_prodotto = prodotto.id_prodotto
                          AND prodotto.id_prodotto = immagine.id_prodotto
                          AND id_recensione = {$_GET['id']}
                          AND immagine.percorso LIKE '%wallpaper%'
                          ORDER BY recensione.data;";

  $db->query($review_detail_query);

  if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
  } else {
    $result = $db->getResult();
    if(!$result) Header('Location: error.php?id=10051');

    foreach($result as $row) {
      $row['titolo'] = utf8_encode($row['titolo']);
      $row['titolo_recensione'] = utf8_encode($row['titolo_recensione']);
      $row['descrizione'] = utf8_encode($row['descrizione']);
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

adminInject($main, $body);

$main->close();

?>
