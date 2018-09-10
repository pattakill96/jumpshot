<?php
function inject($authenticated, $main, $body, $db, $admin = 0)
{
    if ($authenticated) {
        $logoutButton = new Template("html/button-logout.html");
        $main->setContent("logout", $logoutButton->get());
        if (!$admin) {
        }
    } else {
        $loginForm = new Template("html/form-login.html");
        $main->setContent("login", $loginForm->get());
    }
    $main->setContent("body", $body->get());
}
?>