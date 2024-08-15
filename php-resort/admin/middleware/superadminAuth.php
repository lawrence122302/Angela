<?php

if($_SESSION['auth_role'] != "2")
{
    $_SESSION['message'] = "Access Denied. Super Admin privileges not found.";
    header("Location: index.php");
    exit(0);
}

?>