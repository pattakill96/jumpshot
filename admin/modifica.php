<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  if (isset($_POST['descrizione'])){
    $_POST['descrizione'] = utf8_decode(str_replace("'", "", $_POST['descrizione']));
    $update_desc="UPDATE descrizioneprodotti SET testo = '{$_POST['descrizione']}' WHERE prodotto = '{$_POST['id']}' ";
    $db->query($update_desc);
    print($update_desc);
    if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');}
  }
 
if (!isset($_FILES['image']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
    if(isset($_POST['marca']) && isset($_POST['modello']) && isset($_POST['prezzo'])){
        $id=$_POST['id'];
    $update_prod="UPDATE prodotti SET marca = '{$_POST['marca']}', modello = '{$_POST['modello']}', prezzo = '{$_POST['prezzo']}', sconto = '{$_POST['sconto']}' WHERE id = '{$_POST['id']}'";
      $db->query($update_prod);
    if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');}
    }else{
        Header('Location: error.php?id=1005');
      }
      Header('Location: dettaglioprodotto.php?id='.$id);
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
        $id=$_POST['id'];
    $update_prod="UPDATE prodotti SET marca = '{$_POST['marca']}', modello = '{$_POST['modello']}', prezzo = '{$_POST['prezzo']}' WHERE id = '{$_POST['id']}'";
    $update_img="UPDATE immagini SET immagine = '$img' WHERE prodotto = '{$_POST['id']}'";
    $db->query($update_prod);
    if($db->status == "ERROR") {
    Header('Location: error.php?id=1005');}
    $db->query($update_img);
    if($db->status == "ERROR") {
        Header('Location: error.php?id=1005');}
      }else{
        Header('Location: error.php?id=1005');
      }
      Header('Location: dettaglioprodotto.php?id='.$id);
    }else{
    //Se l'operazione è fallta...
    echo 'Upload NON valido!'; 
  }
?>