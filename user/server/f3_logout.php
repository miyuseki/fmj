<?php
session_start();

function logout()
{
    $_SESSION = array();
    session_destroy();
    header("Location: g3_home_view.php");
    return;
}
