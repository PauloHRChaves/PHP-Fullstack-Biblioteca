<?php
session_start();

$_SESSION = [];

session_destroy();

header("Location: /Frontend/index.html");
exit;
?>