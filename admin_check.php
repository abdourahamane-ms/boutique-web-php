<?php
require_once 'auth_check.php';

if (!est_admin()) {
    message('Acces reserve a l\'administrateur.', 'erreur');
    rediriger('index.php');
}
?>
