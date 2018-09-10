<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
if ((isset($_GET['id'])) && (isset($_GET['size']))) {
    $add_to_cart_query = "INSERT INTO carrellofornitore (fornitore,prodotto,taglia)
                                    VALUES ('{$_GET['for']}','{$_GET['id']}','{$_GET['size']}');";
    $db->query($add_to_cart_query);
    if ($db->status == "ERROR") {
        print_r($add_to_cart_query);
        //Header('Location: index.php?problem');
    } else {
        Header('Location: carrello.php');
    }
} else {
    Header('Location: index.php?errore_inserimento_db');
}
?>