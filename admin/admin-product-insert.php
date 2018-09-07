<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-product-insert.html");

  // check formato prezzo
  if( isset($_POST['prezzo']) &&
      (!is_numeric($_POST['prezzo']) || ($_POST['prezzo'] !== number_format($_POST['prezzo'], 2, '.', ''))) )
    $body->setContent("notifica", "Inserisci un formato corretto nel prezzo!");

  else if(isset($_POST['titolo']) && !empty($_POST['titolo'])) {

    $_POST['titolo'] = utf8_decode(str_replace("'", "\'", $_POST['titolo']));
    $_POST['descrizione'] = utf8_decode(str_replace("'", "\'", $_POST['descrizione']));
    $_POST['sviluppatore'] = utf8_decode(str_replace("'", "\'", $_POST['sviluppatore']));
    if($_POST['id_categoria'] == '') $_POST['id_categoria'] = 1;
    $_POST['prezzo'] = number_format($_POST['prezzo'], 2, '.', '');

    $new_product_insert = "INSERT INTO `prodotto`
                          (`id_prodotto`, `titolo`, `descrizione`, `sviluppatore`, `id_categoria`, `prezzo`, `disponibilita`, `uscita`, `copertina`, `num_acquisti`)
                           VALUES (
                             NULL,
                             '{$_POST['titolo']}',
                             '{$_POST['descrizione']}',
                             '{$_POST['sviluppatore']}',
                             '{$_POST['id_categoria']}',
                             '{$_POST['prezzo']}',
                             '{$_POST['disponibilita']}',
                             '{$_POST['uscita']}',
                             '',
                             0);";

    $db->query($new_product_insert);

    if($db->status == "ERROR")
      $body->setContent("notifica", "Errore nel server!");

    else {
      
      if(!empty(basename($_FILES["copertina"]["name"]))) {
      
        $id_prodotto = mysqli_insert_id($db->link);
        $dir = "../products/".$id_prodotto."/";
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $imageFileType = strtolower(pathinfo(basename($_FILES["copertina"]["name"]), PATHINFO_EXTENSION));
        $file = $dir.$id_prodotto."-cover.".$imageFileType;

        // Check:
        if(!getimagesize($_FILES["copertina"]["tmp_name"])) // if is not img
          $body->setContent("notifica", "Il file selezionato non è un'immagine!");

        else if ($_FILES["copertina"]["size"] > 5*1048576) // if > 5MB
          $body->setContent("notifica", "Il file selezionato è troppo grande!");

        else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) // if has correct format
          $body->setContent("notifica", "L'immagine deve avere il formato jpg, jpeg, png oppure gif.");
          
        else if (!move_uploaded_file($_FILES["copertina"]["tmp_name"], $file)) // if upload fails
          $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");

        else { // if everything's fine, insert img in DB
          $path = "http://localhost/gamerage".substr($file, 2);
          $insert_img = "INSERT INTO `immagine`
                        (`id_immagine`, `id_prodotto`, `percorso`, `descrizione`)
                        VALUES (
                          NULL,
                          {$id_prodotto},
                          '{$path}',
                          '{$_POST['titolo']} - cover');";

          $db->query($insert_img);

          if($db->status == "ERROR")
            $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");

          $id_immagine = mysqli_insert_id($db->link);
          $update_product_cover = "UPDATE prodotto
                                  SET copertina = {$id_immagine}
                                  WHERE id_prodotto = {$id_prodotto};";

          $db->query($update_product_cover);

          if($db->status == "ERROR")
            $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");
        }
      }

      if(!empty(basename($_FILES["wallpaper"]["name"]))) {
      
        $dir = "../products/".$id_prodotto."/";
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $imageFileType = strtolower(pathinfo(basename($_FILES["wallpaper"]["name"]), PATHINFO_EXTENSION));
        $file = $dir.$id_prodotto."-wallpaper.".$imageFileType;

        // Check:
        if(!getimagesize($_FILES["wallpaper"]["tmp_name"])) // if is not img
          $body->setContent("notifica", "Il file selezionato non è un'immagine!");

        else if ($_FILES["wallpaper"]["size"] > 5*1048576) // if > 5MB
          $body->setContent("notifica", "Il file selezionato è troppo grande!");

        else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) // if has correct format
          $body->setContent("notifica", "L'immagine deve avere il formato jpg, jpeg, png oppure gif.");
          
        else if (!move_uploaded_file($_FILES["wallpaper"]["tmp_name"], $file)) // if upload fails
          $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");

        else { // if everything's fine, insert img in DB
          $path = "http://localhost/gamerage".substr($file, 2);
          $insert_img = "INSERT INTO `immagine`
                        (`id_immagine`, `id_prodotto`, `percorso`, `descrizione`)
                        VALUES (
                          NULL,
                          {$id_prodotto},
                          '{$path}',
                          '{$_POST['titolo']} - wallpaper'
                        );";

          $db->query($insert_img);

          if($db->status == "ERROR")
            $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");

        }
      }
    }
      
    $body->setContent("notifica", "Prodotto inserito con successo!");

  }

  adminInject($main, $body);

  $main->close();
?>
