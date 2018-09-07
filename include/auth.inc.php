<?php
  // require("queries.inc.php");

  /*
   * libreria per la gestione dell'autenticazione (e autorizzazione), va
   * inclusa in qualsiasi script da "proteggere"
   *
   * ERRORI:
   *
   * 1001: username o password errate
   * 1002: per accedere a questa pagina bisogna autenticarsi
   * 1003: utente non autorizzato all'esecuzione del service
   * 1004: script momentaneamente non attivo
   * 1005: server error
   *
  */

  session_start(); // attiva la gestione sessione
    if (!isset($_SESSION['user'])){
        if ((isset($_POST['username'])) AND (isset($_POST['password']))) {
            /*
            * controllo se l'utente ha inserito username e password nella form di login,
            * se l'utente inserisce u e p nella form di login, lo script login.php viene
            * richiamato attraverso la action della form
            *
            */
            $login_query = "SELECT id, nome,cognome FROM utenti WHERE username = '{$_POST['username']}' AND password = '".MD5($_POST['password'])."'";
            $db->query($login_query);
            $result = $db->getResult();

            if (!$result) {
                if(!isset($_SESSION['admin'])){
                    if ((isset($_POST['username'])) AND (isset($_POST['password']))) {
                        /*
                        * controllo se l'utente ha inserito username e password nella form di login,
                        * se l'utente inserisce u e p nella form di login, lo script login.php viene
                        * richiamato attraverso la action della form
                        *
                        */
                        $login_admin_query = "SELECT id, username FROM amministratori WHERE username = '{$_POST['username']}' AND password = '{$_POST['password']}'";

                        $db->query($login_admin_query);
                        $result1 = $db->getResult();
                        if(!$result1){
                            Header("Location: error.php?id=1005");
                        }else{
                            $_SESSION['admin'] = $result1[0];
                            }
                }
            }}
            /*
            * username e password corrette, utente loggato
            */
            session_unset();
            $_SESSION['user'] = $result[0];
            $_SESSION['nome'] = $result[1];
            $_SESSION['cognome'] = $result[2];
            
            }
        }
    
?>
