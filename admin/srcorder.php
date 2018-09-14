<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";
$main = new Template("../dtml-admin/frame-public.html");
$body = new Template("../dtml-admin/index.html");
$row['id'] = $_SESSION['admin']['username'];
$main->setContent($row);

$srcord="SELECT * FROM ordini WHERE id ='{$_POST['ordine']}'";
$db->query($srcord);
$result = $db->getResult();
    if ($result) {
            foreach ($result as $row) {
            $id = $row['id'];
            $p = utf8_encode($row['pagato']);
            $u = $row['utente'];
    }
    Header('Location: dettagliordine.php?id='.$id.'&t='.$p.'&user='.$u);
}else{
    $srcord="SELECT * FROM ordiniext WHERE id ='{$_POST['ordine']}'";
$db->query($srcord);
$result = $db->getResult();
    if ($result) {
            foreach ($result as $row) {
            $id = $row['id'];
            $p = utf8_encode($row['pagato']);
    }
    Header('Location: dettagliordine.1.php?id='.$id.'&t='.$p);
}}
Header('Location: admin.php?src=1');

adminInject($main, $body);
$main->close();
?>