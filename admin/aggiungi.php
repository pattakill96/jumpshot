<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";
  $id = rand();
  
 
if (!isset($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
     Header('Location: error.php?id=1005');
  }

  
  
  //percorso della cartella dove mettere i file caricati dagli utenti
  $uploaddir = 'C:\xampp\htdocs\jumpshot\img\\';
  
  //Recupero il percorso temporaneo del file
  $userfile_tmp = $_FILES['image']['tmp_name'];

  
  //recupero il nome originale del file caricato
  $userfile_name = $_FILES['image']['name'];
  $img = "img/".$_FILES['image']['name'];
  
  //copio il file dalla sua posizione temporanea alla mia cartella upload
  if (move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name)) {
    //Se l'operazione è andata a buon fine...
    if(isset($_POST['marca']) && isset($_POST['modello']) && isset($_POST['prezzo'])){
        $insert_prod="INSERT INTO prodotti (id, marca, modello, tipologia,prezzo ) VALUES ( '$id','{$_POST['marca']}','{$_POST['modello']}','{$_POST['payment']}','{$_POST['prezzo']}' )";
        $insert_img="INSERT INTO immagini (immagine,prodotto) VALUES ('$img','$id') ";
    $db->query($insert_prod);
    if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');}
    $db->query($insert_img);
    if($db->status == "ERROR") {
        Header('Location: error.php?id=1005');}
      }else{
        Header('Location: error.php?id=1005');
      }
      if (isset($_POST['descrizione'])){
        $_POST['descrizione'] = utf8_decode(str_replace("'", "", $_POST['descrizione']));
        $insert_desc="INSERT INTO descrizioneprodotti (prodotto, testo) VALUES ( $id,'{$_POST['descrizione']}') ";
        $db->query($insert_desc);
        if($db->status == "ERROR") {
        Header('Location: error.php?id=1005');}
     
      Header('Location: dettaglioprodotto.php?id='.$id);}
    }else{
    //Se l'operazione è fallta...
    Header('Location: error.php?id=1005');  }
?>

  $main->close();
?>