<?php
include("header.php");
if(isset($_SESSION['email'])) {
    $_SESSION['email'] = null;
    $_SESSION['id_user'] = null;
    header("Location: login.php");
    die;
}
include("footer.php");
?>