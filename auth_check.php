<?php
require_once 'functions.php';

if (!est_connecte()) {
    message('Vous devez vous connecter pour acceder a cette page.', 'erreur');
    rediriger('login.php');
}
?>
