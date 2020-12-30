<?php
require_once '../config/authentication.php';
$auth = new Authentication();
$auth->logout_admin();
header("Location: ../auth/login.php");
?>