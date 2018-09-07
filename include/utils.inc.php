<?php

function inject($authenticated, $main, $body, $db, $admin = 0) {
  if($authenticated) {
    $logoutButton = new Template("html/button-logout.html");
    // $userButton = new Template("html/button-user.html");
    //$cartButton = new Template("html/button-cart.html");
    // $adminButton = new Template("html/button-admin.html");
    // $adminButtonSidebar = new Template("html/button-admin-sidebar.html");

    // $button_cart_query = "SELECT quantita, prezzo
    //                        FROM carrello, prodotto
    //                        WHERE carrello.username = '{$_SESSION['user']}'
    //                        AND carrello.id_prodotto = prodotto.id_prodotto;";

    // $db->query($button_cart_query);
    // $result = $db->getResult();

    // $button_cart['button_cart_items'] = 0;
    // $button_cart['button_cart_price'] = 0;
    // if($db->status != "ERROR" && $result) {
    //   foreach($result as $row) {
    //     $button_cart['button_cart_items'] += $row['quantita'];
    //     $row['prezzo'] = $row['quantita'] * $row['prezzo'];
    //     $button_cart['button_cart_price'] = number_format($button_cart['button_cart_price'] + $row['prezzo'], 2, '.', '');
    //   }
    // }

  //$cartButton->setContent($button_cart);
  //$userButton->setContent("username", $_SESSION['auth']['username']);
  $main->setContent("logout", $logoutButton->get());
  //$main->setContent("user-button", $userButton->get());
  //$main->setContent("cart-button", $cartButton->get());
  if($admin) {
    //$main->setContent("admin-button", $adminButton->get());
    //$main->setContent("admin-button-sidebar", $adminButtonSidebar->get());
  }
} else {
  $loginForm = new Template("html/form-login.html");

$main->setContent("login", $loginForm->get());
}

// $categories_query = "SELECT categoria.nome
//                     FROM categoria;";

// $db->query($categories_query);

// if($db->status == "ERROR") {
//   $main->setContent("error_categories", "<h3 style=\"color:red;\">Errore nella query delle categorie!</h3>");
// } else {
//   $result = $db->getResult();
//   if(!$result) {
//     $main->setContent("error_categories", "<h3 style=\"color:#31708f;\">Non ci sono categorie!</h3>");
//   } else {
//     foreach($result as $row) {
//       $row['nav_category_name'] = utf8_encode($row['nome']);
//       $row['side_category_name'] = utf8_encode($row['nome']);
//       $main->setContent($row);
//     }
//   }
// }

// $last_products_query = "SELECT prodotto.*, immagine.percorso
//                         FROM prodotto, immagine
//                         WHERE prodotto.copertina = immagine.id_immagine
//                         ORDER BY prodotto.uscita DESC
//                         LIMIT 3;";

// $db->query($last_products_query);

// if($db->status == "ERROR") {
//   $main->setContent("errore_prodotti", "<h3 style=\"color:red;\">Errore nella query degli ultimi prodotti!</h3>");
// } else {
//   $result = $db->getResult();
//   if(!$result) {
//     $main->setContent("errore_prodotti", "<h3 style=\"color:#31708f;\">Non ci sono prodotti!</h3>");
//   } else {
//     foreach($result as $row) {
//       $row['ultimi_prodotti_titolo'] = utf8_encode($row['titolo']);
//       $row['ultimi_prodotti_uscita'] = date("d/m/y", strtotime($row['uscita']));
//       $row['ultimi_prodotti_percorso'] = $row['percorso'];

//       $main->setContent($row);
//     }
//   }
// }

$main->setContent("body", $body->get());
 }

 function paginationProducts($body, $db, $result, $currPage, $genere = "") {
   $lastPage = ceil(count($result)/6);
   $result = array_slice($result, $currPage*6-6, 6);
   if($genere !== "") $genere = "genere=$genere&";
   $pagination = new Template("html/pagination.html");
   $pagination->setContent("prev", ($currPage>1) ? "products.php?".$genere."page=".($currPage-1) : "products.php?".$genere."page=1");
   $pagination->setContent("next", ($currPage<$lastPage) ? "products.php?".$genere."page=".($currPage+1) : "products.php?".$genere."page=".$lastPage);
   foreach($result as $row) {
     $row['titolo'] = utf8_encode($row['titolo']);
     $row['uscita'] = date("d/m/y", strtotime($row['uscita']));

     $body->setContent($row);
     $body->setContent("titolo_pagina", "Tutti i prodotti");
   }

   if($lastPage < 8) {
     for($i = 1; $i <= $lastPage; $i++) {
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "products.php?".$genere."page={$i}");
       $single->setContent("class", ($currPage==$i) ? "active" : "");
       $single->setContent("val", $i);
       $pagination->setContent("single", $single->get());
     }
   } else {
     $single = new Template("html/pagination-single.html");
     $single->setContent("href", "products.php?".$genere."page=1");
     $single->setContent("class", ($currPage==1) ? "active" : "");
     $single->setContent("val", "1");
     $pagination->setContent("single", $single->get());
     if($currPage <= 2 || $currPage > $lastPage-2) {
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "products.php?".$genere."page=2");
       $single->setContent("class", ($currPage==2) ? "active" : "");
       $single->setContent("val", "2");
       $pagination->setContent("single", $single->get());
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "products.php?".$genere."page=3");
       $single->setContent("class", ($currPage==3) ? "active" : "");
       $single->setContent("val", "3");
       $pagination->setContent("single", $single->get());
       $pagination->setContent("single", "<a class=\"dots\">...</a>");
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "products.php?".$genere."page=".($lastPage-2));
       $single->setContent("class", ($currPage==$lastPage-2) ? "active" : "");
       $single->setContent("val", $lastPage-2);
       $pagination->setContent("single", $single->get());
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "products.php?".$genere."page=".($lastPage-1));
       $single->setContent("class", ($currPage==$lastPage-1) ? "active" : "");
       $single->setContent("val", $lastPage-1);
       $pagination->setContent("single", $single->get());
     } else {
       $pagination->setContent("single", "<a class=\"dots\">...</a>");
       for($i = -1; $i <= 1; $i++) {
         $single = new Template("html/pagination-single.html");
         $single->setContent("href", "products.php?".$genere."page=".($currPage+$i));
         $single->setContent("class", ($currPage==$currPage+$i) ? "active" : "");
         $single->setContent("val", $currPage+$i);
         $pagination->setContent("single", $single->get());
       }
       $pagination->setContent("single", "<a class=\"dots\">...</a>");
     }
     $single = new Template("html-admin/pagination-single.html");
     $single->setContent("href", "products.php?".$genere."page=".$lastPage);
     $single->setContent("class", ($currPage==$lastPage) ? "active" : "");
     $single->setContent("val", $lastPage);
     $pagination->setContent("single", $single->get());
   }
   $body->setContent("pagination", $pagination->get());
 }

 function paginationReviews($body, $db, $result, $currPage, $id_prodotto = "", $user = "") {
   $lastPage = ceil(count($result)/6);
   $result = array_slice($result, $currPage*6-6, 6);
   if($id_prodotto !== "") $id_prodotto = "genere=$id_prodotto&";
   $pagination = new Template("html/pagination.html");
   $pagination->setContent("prev", ($currPage>1) ? "reviews.php?".$id_prodotto."page=".($currPage-1) : "reviews.php?".$id_prodotto."page=1");
   $pagination->setContent("next", ($currPage<$lastPage) ? "reviews.php?".$id_prodotto."page=".($currPage+1) : "reviews.php?".$id_prodotto."page=".$lastPage);
   foreach($result as $row) {
     $row['titolo_recensione'] = utf8_encode($row['titolo_recensione']);
     $row['titolo'] = utf8_encode($row['titolo']);
     $row['username'] = utf8_encode($row['username']);
     $row['descrizione'] = mb_strimwidth(utf8_encode($row['descrizione']), 0, 300, "...");
     $row['data'] = date("d/m/y", strtotime($row['data']));
     $row['liked'] = "";
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

       $liked = "SELECT id_recensione
                 FROM likes
                 WHERE id_recensione = {$row['id_recensione']}
                 AND username = '$user';";
       $db->query($liked);
       $row['liked'] = ($db->getResult()) ? " liked" : "";

     } else $likes = 0;
     $row['num_likes'] = $likes;

     $body->setContent($row);
   }

   if($lastPage < 8) {
     for($i = 1; $i <= $lastPage; $i++) {
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "reviews.php?".$id_prodotto."page={$i}");
       $single->setContent("class", ($currPage==$i) ? "active" : "");
       $single->setContent("val", $i);
       $pagination->setContent("single", $single->get());
     }
   } else {
     $single = new Template("html/pagination-single.html");
     $single->setContent("href", "reviews.php?".$id_prodotto."page=1");
     $single->setContent("class", ($currPage==1) ? "active" : "");
     $single->setContent("val", "1");
     $pagination->setContent("single", $single->get());
     if($currPage <= 2 || $currPage > $lastPage-2) {
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "reviews.php?".$id_prodotto."page=2");
       $single->setContent("class", ($currPage==2) ? "active" : "");
       $single->setContent("val", "2");
       $pagination->setContent("single", $single->get());
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "reviews.php?".$id_prodotto."page=3");
       $single->setContent("class", ($currPage==3) ? "active" : "");
       $single->setContent("val", "3");
       $pagination->setContent("single", $single->get());
       $pagination->setContent("single", "<a class=\"dots\">...</a>");
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "reviews.php?".$id_prodotto."page=".($lastPage-2));
       $single->setContent("class", ($currPage==$lastPage-2) ? "active" : "");
       $single->setContent("val", $lastPage-2);
       $pagination->setContent("single", $single->get());
       $single = new Template("html/pagination-single.html");
       $single->setContent("href", "reviews.php?".$id_prodotto."page=".($lastPage-1));
       $single->setContent("class", ($currPage==$lastPage-1) ? "active" : "");
       $single->setContent("val", $lastPage-1);
       $pagination->setContent("single", $single->get());
     } else {
       $pagination->setContent("single", "<a class=\"dots\">...</a>");
       for($i = -1; $i <= 1; $i++) {
         $single = new Template("html/pagination-single.html");
         $single->setContent("href", "reviews.php?".$id_prodotto."page=".($currPage+$i));
         $single->setContent("class", ($currPage==$currPage+$i) ? "active" : "");
         $single->setContent("val", $currPage+$i);
         $pagination->setContent("single", $single->get());
       }
       $pagination->setContent("single", "<a class=\"dots\">...</a>");
     }
     $single = new Template("html-admin/pagination-single.html");
     $single->setContent("href", "reviews.php?".$id_prodotto."page=".$lastPage);
     $single->setContent("class", ($currPage==$lastPage) ? "active" : "");
     $single->setContent("val", $lastPage);
     $pagination->setContent("single", $single->get());
   }
   $body->setContent("pagination", $pagination->get());
}

?>
