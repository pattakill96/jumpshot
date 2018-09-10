<?php

  function adminInject($main, $body) {
     $logoutButton = new Template("../dtml-admin/button-logout.html");
    // $userButton = new Template("../themes/default/dtml/button-user.html");
    // $cartButton = new Template("../themes/default/dtml/button-cart.html");
    // $adminButton = new Template("../themes/default/dtml/button-admin.html");
    // $adminButtonSidebar = new Template("../themes/default/dtml/button-admin-sidebar.html");
    $main->setContent("logout", $logoutButton->get());
    $main->setContent("body", $body->get());
    

  }


  function paginationUsers($body, $action, $result, $currPage) {
    $lastPage = ceil(count($result)/6);
    $result = array_slice($result, $currPage*6-6, 6);
    $pagination = new Template("../themes/default/dtml-admin/pagination.html");
    $pagination->setContent("prev", ($currPage>1) ? "admin-user-$action.php?page=".($currPage-1) : "admin-user-$action.php?page=1");
    $pagination->setContent("next", ($currPage<$lastPage) ? "admin-user-$action.php?page=".($currPage+1) : "admin-user-$action.php?page=".$lastPage);
    foreach($result as $row) {
      $body->setContent("action", $action);
      $body->setContent("username", $row['username']);
    }
  
    if($lastPage < 8) {
      for($i = 1; $i <= $lastPage; $i++) {
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-user-$action.php?page={$i}");
        $single->setContent("class", ($currPage==$i) ? "active" : "");
        $single->setContent("val", $i);
        $pagination->setContent("single", $single->get());
      }
    } else {
      $single = new Template("../themes/default/dtml-admin/pagination-single.html");
      $single->setContent("href", "admin-user-$action.php?page=1");
      $single->setContent("class", ($currPage==1) ? "active" : "");
      $single->setContent("val", "1");
      $pagination->setContent("single", $single->get());
      if($currPage <= 2 || $currPage > $lastPage-2) {
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-user-$action.php?page=2");
        $single->setContent("class", ($currPage==2) ? "active" : "");
        $single->setContent("val", "2");
        $pagination->setContent("single", $single->get());
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-user-$action.php?page=3");
        $single->setContent("class", ($currPage==3) ? "active" : "");
        $single->setContent("val", "3");
        $pagination->setContent("single", $single->get());
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-user-$action.php?page=".($lastPage-2));
        $single->setContent("class", ($currPage==$lastPage-2) ? "active" : "");
        $single->setContent("val", $lastPage-2);
        $pagination->setContent("single", $single->get());
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-user-$action.php?page=".($lastPage-1));
        $single->setContent("class", ($currPage==$lastPage-1) ? "active" : "");
        $single->setContent("val", $lastPage-1);
        $pagination->setContent("single", $single->get());
      } else {
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
        for($i = -1; $i <= 1; $i++) {
          $single = new Template("../themes/default/dtml-admin/pagination-single.html");
          $single->setContent("href", "admin-user-$action.php?page=".($currPage+$i));
          $single->setContent("class", ($currPage==$currPage+$i) ? "active" : "");
          $single->setContent("val", $currPage+$i);
          $pagination->setContent("single", $single->get());
        }
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
      }
      $single = new Template("../themes/default/dtml-admin/pagination-single.html");
      $single->setContent("href", "admin-user-$action.php?page=".$lastPage);
      $single->setContent("class", ($currPage==$lastPage) ? "active" : "");
      $single->setContent("val", $lastPage);
      $pagination->setContent("single", $single->get());
    }
    $body->setContent("pagination", $pagination->get());
  }

  function paginationProducts($body, $action, $result, $currPage) {
    $lastPage = ceil(count($result)/6);
    $result = array_slice($result, $currPage*6-6, 6);
    $pagination = new Template("../themes/default/dtml-admin/pagination.html");
    $pagination->setContent("prev", ($currPage>1) ? "admin-product-$action.php?page=".($currPage-1) : "admin-product-$action.php?page=1");
    $pagination->setContent("next", ($currPage<$lastPage) ? "admin-product-$action.php?page=".($currPage+1) : "admin-product-$action.php?page=".$lastPage);
    if($currPage == 1) $pagination->setContent("p_disabled", "disabled ");
    else if($currPage == $lastPage) $pagination->setContent("n_disabled", "disabled ");
    foreach($result as $row) {
      $body->setContent("action", $action);
      if($action == "coupon-edit" || $action == "coupon-delete") $row['titolo'] = "{$row['id_coupon']} ({$row['titolo']})";
      $body->setContent($row);
    }
  
    if($lastPage < 8) {
      for($i = 1; $i <= $lastPage; $i++) {
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-product-$action.php?page={$i}");
        $single->setContent("class", ($currPage==$i) ? "active" : "");
        $single->setContent("val", $i);
        $pagination->setContent("single", $single->get());
      }
    } else {
      $single = new Template("../themes/default/dtml-admin/pagination-single.html");
      $single->setContent("href", "admin-product-$action.php?page=1");
      $single->setContent("class", ($currPage==1) ? "active" : "");
      $single->setContent("val", "1");
      $pagination->setContent("single", $single->get());
      if($currPage <= 2 || $currPage > $lastPage-2) {
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-product-$action.php?page=2");
        $single->setContent("class", ($currPage==2) ? "active" : "");
        $single->setContent("val", "2");
        $pagination->setContent("single", $single->get());
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-product-$action.php?page=3");
        $single->setContent("class", ($currPage==3) ? "active" : "");
        $single->setContent("val", "3");
        $pagination->setContent("single", $single->get());
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-product-$action.php?page=".($lastPage-2));
        $single->setContent("class", ($currPage==$lastPage-2) ? "active" : "");
        $single->setContent("val", $lastPage-2);
        $pagination->setContent("single", $single->get());
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-product-$action.php?page=".($lastPage-1));
        $single->setContent("class", ($currPage==$lastPage-1) ? "active" : "");
        $single->setContent("val", $lastPage-1);
        $pagination->setContent("single", $single->get());
      } else {
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
        for($i = -1; $i <= 1; $i++) {
          $single = new Template("../themes/default/dtml-admin/pagination-single.html");
          $single->setContent("href", "admin-product-$action.php?page=".($currPage+$i));
          $single->setContent("class", ($currPage==$currPage+$i) ? "active" : "");
          $single->setContent("val", $currPage+$i);
          $pagination->setContent("single", $single->get());
        }
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
      }
      $single = new Template("../themes/default/dtml-admin/pagination-single.html");
      $single->setContent("href", "admin-product-$action.php?page=".$lastPage);
      $single->setContent("class", ($currPage==$lastPage) ? "active" : "");
      $single->setContent("val", $lastPage);
      $pagination->setContent("single", $single->get());
    }
    $body->setContent("pagination", $pagination->get());
  }

  function paginationOrders($body, $action, $result, $currPage) {
    $lastPage = ceil(count($result)/6);
    $result = array_slice($result, $currPage*6-6, 6);
    $pagination = new Template("../themes/default/dtml-admin/pagination.html");
    $pagination->setContent("prev", ($currPage>1) ? "admin-$action.php?page=".($currPage-1) : "admin-$action.php?page=1");
    $pagination->setContent("next", ($currPage<$lastPage) ? "admin-$action.php?page=".($currPage+1) : "admin-$action.php?page=".$lastPage);
    foreach($result as $row) {
      $body->setContent("action", $action);
      $row['id'] = "n°{$row['id_ordine']}";
      $row['user'] = "({$row['username']})";
      $body->setContent($row);
    }
  
    if($lastPage < 8) {
      for($i = 1; $i <= $lastPage; $i++) {
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-$action.php?page={$i}");
        $single->setContent("class", ($currPage==$i) ? "active" : "");
        $single->setContent("val", $i);
        $pagination->setContent("single", $single->get());
      }
    } else {
      $single = new Template("../themes/default/dtml-admin/pagination-single.html");
      $single->setContent("href", "admin-$action.php?page=1");
      $single->setContent("class", ($currPage==1) ? "active" : "");
      $single->setContent("val", "1");
      $pagination->setContent("single", $single->get());
      if($currPage <= 2 || $currPage > $lastPage-2) {
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-$action.php?page=2");
        $single->setContent("class", ($currPage==2) ? "active" : "");
        $single->setContent("val", "2");
        $pagination->setContent("single", $single->get());
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-$action.php?page=3");
        $single->setContent("class", ($currPage==3) ? "active" : "");
        $single->setContent("val", "3");
        $pagination->setContent("single", $single->get());
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-$action.php?page=".($lastPage-2));
        $single->setContent("class", ($currPage==$lastPage-2) ? "active" : "");
        $single->setContent("val", $lastPage-2);
        $pagination->setContent("single", $single->get());
        $single = new Template("../themes/default/dtml-admin/pagination-single.html");
        $single->setContent("href", "admin-$action.php?page=".($lastPage-1));
        $single->setContent("class", ($currPage==$lastPage-1) ? "active" : "");
        $single->setContent("val", $lastPage-1);
        $pagination->setContent("single", $single->get());
      } else {
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
        for($i = -1; $i <= 1; $i++) {
          $single = new Template("../themes/default/dtml-admin/pagination-single.html");
          $single->setContent("href", "admin-$action.php?page=".($currPage+$i));
          $single->setContent("class", ($currPage==$currPage+$i) ? "active" : "");
          $single->setContent("val", $currPage+$i);
          $pagination->setContent("single", $single->get());
        }
        $pagination->setContent("single", "<a class=\"dots\">...</a>");
      }
      $single = new Template("../themes/default/dtml-admin/pagination-single.html");
      $single->setContent("href", "admin-$action.php?page=".$lastPage);
      $single->setContent("class", ($currPage==$lastPage) ? "active" : "");
      $single->setContent("val", $lastPage);
      $pagination->setContent("single", $single->get());
    }
    $body->setContent("pagination", $pagination->get());
  }
?>
