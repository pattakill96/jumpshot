<?php
require "include/dbms.inc.php";
session_start(); // attiva la gestione sessione
if ((isset($_POST['name'])) && (isset($_POST['email']))) {
    $news_query = "INSERT INTO news (nome, email, messaggio) VALUES ('{$_POST['name']}','{$_POST['email']}','{$_POST['message']}');";
    $db->query($news_query);
    if ($db->status == "ERROR") {
        Header("Location: error.php?id=1045");
        exit;
    } else {
        Header("Location: index.php");
    }
}
?>