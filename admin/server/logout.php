<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location:../view/g1_login_view.php");
?>
