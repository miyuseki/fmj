<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: g3_home_view.php");
?>
