<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/dettagliofornitore.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);
$app = 0;
 $fornitore_query = "SELECT fornitore.*, descrizionefornitore.testo,imgfornitore.immagine FROM imgfornitore,fornitore,descrizionefornitore
                     WHERE fornitore.id = '{$_GET['id']}' AND descrizionefornitore.fornitore = '{$_GET['id']}' AND imgfornitore.fornitore='{$_GET['id']}'";
 $db->query($fornitore_query);
 if ($db->status == "ERROR") {
     Header('Location: error.php?id=1005');
 } else {
     $result = $db->getResult();
     $row['errore'] = "";
     if (!$result) {
         Header('Location: error.php?id=1005');
     } else {
         foreach ($result as $row) {
             $row['denominazione'] = $row['denominazione'];
             $row['immagine'] = $row['immagine'];
             $row['indirizzo'] = $row['indirizzo'];
             $row['testo'] = $row['testo'];
             $body->setContent($row);
         }
     }
 }

adminInject($main, $body);
$main->close();
?>