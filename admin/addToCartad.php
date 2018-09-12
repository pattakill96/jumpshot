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
        Header('Location: error.php?id=1005');
    } else {
        Header('Location: carrello.php');
    }
} else {
    Header('Location: error.php?id=1005');
}
?>