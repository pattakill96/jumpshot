<?php
session_start();
if (!isset($_SESSION['admin'])) {
    if ((isset($_POST['username'])) AND (isset($_POST['password']))) {
        $login_admin_query = "SELECT id, username FROM amministratori WHERE username = '{$_POST['username']}' AND password = '{$_POST['password']}'";
        $db->query($login_admin_query);
        $result1 = $db->getResult();
        if (!$result1) {
            if (!isset($_SESSION['user'])) {
                if ((isset($_POST['username'])) AND (isset($_POST['password']))) {
                    $login_query = "SELECT id, nome,cognome FROM utenti WHERE username = '{$_POST['username']}' AND password = '" . MD5($_POST['password']) . "'";
                    $db->query($login_query);
                    $result = $db->getResult();
                    if ($result) {
                        session_unset();
                        $_SESSION['user'] = $result[0];
                        $_SESSION['nome'] = $result[1];
                        $_SESSION['cognome'] = $result[2];
                    }
                    else {
                        Header('Location: error.php?id=1009');
                        exit;
                    }
                }
            }Header('Location: error.php?id=1009');
        } 
            else {
            
            $_SESSION['admin'] = $result1[0];
        }
    }
}
?>
