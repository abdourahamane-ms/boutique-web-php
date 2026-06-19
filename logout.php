<?php
require_once 'connexion.php';
session_destroy();
header('Location: index.php');
exit;
?>
