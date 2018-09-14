<?php
require "../include/dbms.inc.php";
require "../include/template2.inc.php";
require "../include/auth.inc.php";
require "../include/admin-utils.inc.php";

$query_carr1 = "SELECT SUM(prodottifornitore.prezzo)as tot,taglia, prodottifornitore.id  FROM prodottifornitore,carrellofornitore WHERE prodottifornitore.id=carrellofornitore.prodotto AND carrellofornitore.pagato=0";
$db->query($query_carr1);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else {
    $result = $db->getResult();
    $row['errore'] = "";
    if (!$result) {
        $row['errore'] = "Non ci sono prodotti nel tuo carrello";
    }
    foreach ($result as $row) {
        $taglia = $row['taglia'];
        $tot = $row['tot'] * 10;
        $id = $row['id'];
        $addshoe="SELECT prodottifornitore.*, immaginifornitore.immagine FROM prodottifornitore,immaginifornitore WHERE prodottifornitore.id=immaginifornitore.prodotto AND prodottifornitore.id=$id";
        $db->query($addshoe);
        if ($db->status == "ERROR") {
            Header('Location: error.php?id=1005');
        } else {
            $result1 = $db->getResult();
            $row['errore'] = "";
            if (!$result1) {
                Header('Location: error.php?id=1005');
            }
            foreach ($result1 as $row1) {
                $marca=$row1['marca'];
                $modello=$row1['modello'];
                $tipologia=$row1['tipologia'];
                $prezzo=$row1['prezzo'];
                $immagine=$row1['immagine'];
            }
            $cont=rand();
            $addprodotto="INSERT INTO prodotti (id, marca, modello, tipologia,prezzo ) VALUES ('$cont','$marca','$modello','$tipologia','$prezzo')";
            $addimmagine="INSERT INTO immagini (immagine,prodotto) VALUES ('$immagine','$cont')";
            $adddesc="INSERT INTO descrizioneprodotti (prodotto,testo) VALUES ('$cont','NESSUNA DESCRIZIONE DISPONIBILE')";
            $addtaglia="INSERT INTO taglieprodotti (scarpa,taglia,quantita) VALUES ('$cont','$taglia','10')";
            $db->query($addprodotto);
            if ($db->status == "ERROR") {
                Header('Location: error.php?id=1005');
            } else {
                $db->query($addimmagine);
                if ($db->status == "ERROR") {
                    Header('Location: error.php?id=1005');
                } else {
                    $db->query($adddesc);
                    if ($db->status == "ERROR") {
                        Header('Location: error.php?id=1005');
                    }else{
                    $db->query($addtaglia);
                    if ($db->status == "ERROR") {
                        Header('Location: error.php?id=1005');
                    }


}}}}}
$app = rand();
$set_order = "INSERT INTO ordinefornitore (id, totale) VALUES ('$app','$tot');";
$db->query($set_order);
if ($db->status == "ERROR") {
    Header('Location: error.php?id=1005');
} else    
    $set_carr = "UPDATE carrellofornitore SET  ordinato = $app,pagato =1 WHERE pagato=0 ";
    $db->query($set_carr);
    if ($db->status == "ERROR") {
        Header('Location: error.php?id=1005');
    } else {
        Header('Location: admin.php?ord=1');
    } }
adminInject($main, $body);
$main->close();
?>
