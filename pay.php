<?php
require "include/dbms.inc.php";
session_start(); 
if ((isset($_GET['id'])) && (isset($_GET['t']))) {
    if (isset($_SESSION['user'])) {
        $order_pay = "INSERT INTO ordinipagati (ordine, spedizione, pagamento) VALUES ('{$_GET['id']}','in preparazione','{$_GET['t']}');";
        $db->query($order_pay);
        if ($db->status == "ERROR") {
            Header("Location: error.php?id=1005");
            exit;
        } else {
            $order_change = "UPDATE ordini SET pagato=1 WHERE id= '{$_GET['id']}'";
            $db->query($order_change);
            Header("Location: ./index.php?order=1");
        }
    } else if (isset($_SESSION['ext'])) {
        $order_pay = "INSERT INTO ordinepagatoext (ordine, spedizione, pagamento) VALUES ('{$_GET['id']}','in preparazione','{$_GET['t']}');";
        $db->query($order_pay);
        if ($db->status == "ERROR") {
            Header("Location: error.php?id=1005");
            exit;
        } else {
            $order_change = "UPDATE ordiniext SET pagato=1 WHERE id= '{$_GET['id']}'";
            $db->query($order_change);
            Header("Location: ./index.php?orderext=1");
        }
    }
}
?>