<?php
  require "../include/dbms.inc.php";
  require "../include/auth.inc.php";

  if(isset($_POST['term']) && (strlen($_POST['term'])>2) && isset($_POST['action'])) {
    $user_search_query = "SELECT user.username
                          FROM user, user_groups
                          WHERE user.username = user_groups.username
                          AND id_groups > 1
                          AND user.username LIKE '{$_POST['term']}%';";

    $db->query($user_search_query);
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
        $html .= "<div class='4u 12u$(xsmall)' style='text-align:center;margin-bottom:.5em;'>
          <a class='user-click' action='{$_POST['action']}' username='{$row['username']}' style='border-bottom: transparent !important;cursor:pointer;'>
            <img src='../themes/default/assets/images/user.png' width='60em'>
          </a>
          <br>
          <a class='user-click' action='{$_POST['action']}' username='{$row['username']}' style='cursor:pointer;'>{$row['username']}</a>
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
