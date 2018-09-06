<?php 
    require "include/dbms.inc.php";

    session_start(); // attiva la gestione sessione

if ((isset($_POST['name'])) && (isset($_POST['email']))) {
    /*
     * controllo se l'utente ha inserito username e password nella form di login,
     * se l'utente inserisce u e p nella form di login, lo script login.php viene
     * richiamato attraverso la action della form
     *
    */
  $news_query = "INSERT INTO news (nome, email, messaggio) VALUES ('{$_POST['name']}','{$_POST['email']}','{$_POST['message']}');";
  

    $db->query($news_query);

    if ($db->status == "ERROR") {
        /* utente o password errate */
        Header("Location: error.php?id=1045");
        exit;
    }
    else{
        Header("Location: index.php");
        }
} 
?>