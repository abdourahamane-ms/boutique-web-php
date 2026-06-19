<?php
require_once 'functions.php';
session_destroy();
session_start();
message('Vous etes deconnecte.');
rediriger('login.php');
?>
