<?php
session_start();
include('admin/config/dbcon.php');

function base_url($slug){
    echo 'http://localhost/Angela/php-resort/'.$slug;
}
?>