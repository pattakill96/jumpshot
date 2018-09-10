<?php
require "include/dbms.inc.php";
session_start();
if ((isset($_GET['id'])) && (isset($_GET['size']))) {
    $drop_shoe = "UPDATE taglieprodotti SET taglieprodotti.quantita = taglieprodotti.quantita -1  WHERE scarpa = '{$_GET['id']}' AND taglia = '{$_GET['size']}'";
    if (isset($_SESSION['user'])) {
        $db->query($drop_shoe);
        $add_to_cart_query = "INSERT INTO carrello (utente,prodotto,taglia)
                                    VALUES ('{$_SESSION['user']['id']}','{$_GET['id']}','{$_GET['size']}');";
        $db->query($add_to_cart_query);
        if ($db->status == "ERROR") {
            Header('Location: index.php?problem');
        } else {
            Header('Location: index.php');
        }
    } elseif (isset($_SESSION['ext'])) {
        $db->query($drop_shoe);
        $add_to_cart_query = "INSERT INTO carrelloext (utente,prodotto,taglia)
                                    VALUES ('{$_SESSION['ext']['id']}','{$_GET['id']}','{$_GET['size']}');";
        $db->query($add_to_cart_query);
        if ($db->status == "ERROR") {
            Header('Location: index.php?problem');
        } else {
            Header('Location: index.php');
        }
    } else {
        $app = rand();
        $_SESSION['ext']['id'] = $app;
        $db->query($drop_shoe);
        $add_user_ext = "INSERT INTO utentiext (id,token) VALUES ($app,1)";
        $db->query($add_user_ext);
        $add_to_cart_query = "INSERT INTO carrelloext (utente,prodotto,taglia)
                                    VALUES ('{$_SESSION['ext']['id']}','{$_GET['id']}','{$_GET['size']}');";
        $db->query($add_to_cart_query);
        if ($db->status == "ERROR") {
            Header('Location: index.php?problem');
        } else {
            Header('Location: index.php');
        }
    }
} else {
    Header('Location: index.php?errore_inserimento_db');
}
?>