<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";



if ((isset($_GET['id'])) && (isset($_GET['size'])) && isset($_GET['quant'])) {
    $set_taglia = "UPDATE taglieprodotti SET  taglieprodotti.quantita = taglieprodotti.quantita +'{$_GET['quant']}' WHERE taglia='{$_GET['size']}' AND scarpa = '{$_GET['id']}' ";
    $db->query($set_taglia);
    $result = $db->getResult();
    if ($result) {
    }Header('Location: admin.php?size=2');        
        if (!$result) {
            $set_taglia1 = "INSERT INTO taglieprodotti (scarpa,taglia,quantita) VALUES ('{$_GET['id']}','{$_GET['size']}','{$_GET['quant']}');";
            $db->query($set_taglia1);
            Header('Location: admin.php?size=1');
        }
    }
?>