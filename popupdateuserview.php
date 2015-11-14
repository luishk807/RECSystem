<?php
session_start();
include "include/config.php";
include "include/function.php";
$user= $_SESSION["rec_user"];
$crud_ghost=getGHost();
$crud_host=getHost();
$crud_session=getSession();
$crud_systemtitle=getSystemTitle();
include "../crud_lib/viewusers.php";
include "include/config.php";
?>