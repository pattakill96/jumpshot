<?php
  require "include/dbms.inc.php";
    session_start();
    if (isset($_SESSION['user'])){
       if((isset($_GET['id'])) && (isset($_GET['size'])) && (isset($_GET['price']))) {         
            $add_to_cart_query = "INSERT INTO carrello (utente,prodotto,taglia,prezzo,ordinato)
                                    VALUES ('{$_SESSION['user']['id']}','{$_GET['id']}','{$_GET['size']}','{$_GET['price']}',0);";
            $db->query($add_to_cart_query);

       if($db->status == "ERROR") {
        //Header('Location: index.php?problem');
     }else{
         Header('Location: index.php');
        
    //     }
     }
    }}
?>
