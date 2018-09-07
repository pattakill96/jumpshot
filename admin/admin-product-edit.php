<?php

  require "../include/dbms.inc.php";
  require "../include/template2.inc.php";
  require "../include/auth.inc.php";
  require "../include/admin-utils.inc.php";

  $main = new Template("../themes/default/dtml-admin/frame-public.html");
  $body = new Template("../themes/default/dtml-admin/admin-product-search.html");

  $body->setContent("action_script", "edit");
  $body->setContent("action_title", "Modifica dettagli prodotto");
  $body->setContent("action_desc", "Da qui puoi modificare tutti i dettagli di un prodotto.");

  if(isset($_GET['edit']) && ($_GET['edit'] !== "")) {

    $product_get_query = "SELECT *
                          FROM prodotto
                          WHERE id_prodotto = {$_GET['edit']};";
    
    $db->query($product_get_query);

    if($db->status == "ERROR") Header('Location: error.php?id=1005');

    $product = $db->getResult()[0];
    if($product) {
      $body = new Template("../themes/default/dtml-admin/admin-product-edit.html");
      $body->setContent("action_script", "edit");
      $body->setContent("action_title", "Modifica dettagli prodotto &quot;".$product['titolo']."&quot;.");
      $body->setContent("action_desc", "Da qui puoi modificare tutti i dettagli di &quot;".$product['titolo']."&quot;.");

      $product['titolo'] = utf8_encode($product['titolo']);
      $product['descrizione'] = utf8_encode($product['descrizione']);
      $product['sviluppatore'] = utf8_encode($product['sviluppatore']);
      $body->setContent($product);
      if(!empty($product['id_categoria']))$body->setContent("selected".$product['id_categoria'], "selected ");
    } else
      $body->setContent("notifica", "Il prodotto non esiste o non può essere modificato.");

  } else if(isset($_POST['titolo']) && !empty($_POST['titolo'])) {

    $_POST['titolo'] = utf8_decode(str_replace("'", "\'", $_POST['titolo']));
    $_POST['descrizione'] = utf8_decode(str_replace("'", "\'", $_POST['descrizione']));
    $_POST['sviluppatore'] = utf8_decode(str_replace("'", "\'", $_POST['sviluppatore']));
    if($_POST['id_categoria'] == '') $_POST['id_categoria'] = 1;
    $_POST['prezzo'] = number_format($_POST['prezzo'], 2, '.', '');

    $edit_product_query = "UPDATE `prodotto` SET
                          titolo = '{$_POST['titolo']}',
                          descrizione = '{$_POST['descrizione']}',
                          sviluppatore = '{$_POST['sviluppatore']}',
                          id_categoria = {$_POST['id_categoria']},
                          prezzo = {$_POST['prezzo']},
                          disponibilita = {$_POST['disponibilita']},
                          uscita = '{$_POST['uscita']}'
                          WHERE id_prodotto = {$_GET['id_prodotto']};";

    $db->query($edit_product_query);

    if($db->status == "ERROR")
      $body->setContent("notifica", "Errore nel server!");

    else {
      
      if(!empty(basename($_FILES["copertina"]["name"]))) {
        $dir = "../products/".$_GET['id_prodotto']."/";
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $imageFileType = strtolower(pathinfo(basename($_FILES["copertina"]["name"]), PATHINFO_EXTENSION));
        $file = $dir.$_GET['id_prodotto']."-cover.".$imageFileType;
        $insert = true;
        if(file_exists($file)) $insert = false;

        // Check:
        if(!getimagesize($_FILES["copertina"]["tmp_name"])) // if is not img
          $body->setContent("notifica", "Il file selezionato non è un'immagine!");

        else if ($_FILES["copertina"]["size"] > 5*1048576) // if > 5MB
          $body->setContent("notifica", "Il file selezionato è troppo grande!");

        else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) // if has correct format
          $body->setContent("notifica", "L'immagine deve avere il formato jpg, jpeg, png oppure gif.");
          
        else if (!move_uploaded_file($_FILES["copertina"]["tmp_name"], $file)) // if upload fails
          $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");

        else if($insert) { // if everything's fine and cover didn't exist, insert img in DB
          $path = "http://localhost/gamerage".substr($file, 2);
          $insert_img = "INSERT INTO `immagine`
                        (`id_immagine`, `id_prodotto`, `percorso`, `descrizione`)
                        VALUES (
                          NULL,
                          {$_GET['id_prodotto']},
                          '{$path}',
                          '{$_POST['titolo']} - cover');";

          $db->query($insert_img);

          if($db->status == "ERROR")
            $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");

          $id_immagine = mysqli_insert_id($db->link);
          $update_product_cover = "UPDATE prodotto
                                  SET copertina = {$id_immagine}
                                  WHERE id_prodotto = {$_GET['id_prodotto']};";

          $db->query($update_product_cover);

          if($db->status == "ERROR")
            $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");
        }

      }

      if(!empty(basename($_FILES["wallpaper"]["name"]))) {
      
        $dir = "../products/".$_GET['id_prodotto']."/";
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $imageFileType = strtolower(pathinfo(basename($_FILES["wallpaper"]["name"]), PATHINFO_EXTENSION));
        $file = $dir.$_GET['id_prodotto']."-wallpaper.".$imageFileType;
        $insert = true;
        if(file_exists($file)) $insert = false;

        // Check:
        if(!getimagesize($_FILES["wallpaper"]["tmp_name"])) // if is not img
          $body->setContent("notifica", "Il file selezionato non è un'immagine!");

        else if ($_FILES["wallpaper"]["size"] > 5*1048576) // if > 5MB
          $body->setContent("notifica", "Il file selezionato è troppo grande!");

        else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) // if has correct format
          $body->setContent("notifica", "L'immagine deve avere il formato jpg, jpeg, png oppure gif.");

        else if (!move_uploaded_file($_FILES["wallpaper"]["tmp_name"], $file)) // if upload fails
            $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");
        
        else if($insert) { // if everything's fine and wallpaper didn't exist, insert img in DB
          $path = "http://localhost/gamerage".substr($file, 2);
          $insert_img = "INSERT INTO `immagine`
                        (`id_immagine`, `id_prodotto`, `percorso`, `descrizione`)
                        VALUES (
                          NULL,
                          {$_GET['id_prodotto']},
                          '{$path}',
                          '{$_POST['titolo']} - wallpaper'
                        );";

          $db->query($insert_img);

          if($db->status == "ERROR")
            $body->setContent("notifica", "Prodotto inserito, ma c'è stato un errore durante l'upload dell'immagine!");

        }
        

      }
      
    }

    Header('Location: admin-product-edit.php?page=1&success='.str_replace("\'", "'", $_POST['titolo']));
  } else if(!isset($_GET['page']) || $_GET['page'] < 1) {
    Header('Location: admin-product-edit.php?page=1');
  } else {
    if(isset($_GET['success']) && !empty($_GET['success'])) $body->setContent("notifica", "\"".$_GET['success']."\" è stato modificato con successo!");

    $product_search_query = "SELECT *
                            FROM prodotto
                            WHERE titolo LIKE '%'
                            ORDER BY titolo;";

    $db->query($product_search_query);
    if($db->status == "ERROR") Header('Location: error.php?id=1009');

    $result = $db->getResult();
    if($result) paginationProducts($body, "edit", $result, $_GET['page']);
    else $body->setContent("notifica", "Nessun prodotto trovato per \"<[ricerca]>\".");
    $body->setContent("ricerca", "");
  }

  adminInject($main, $body);

  $main->close();
?>
