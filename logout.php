<?Php
session_start();
unset($_SESSION["rec_user"]);
unset($_SESSION["brownuser"]);
unset($_SESSION["woffice"]);
$_SESSION["loginresult"]="SUCCESS: You Are Logout Successfully";
header('location:index.php');
exit;
?>