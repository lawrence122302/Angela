<?php

    require('inc/essentials.php');

    session_name('admin_session');
    session_start();
    session_destroy();
    redirect('index.php');

?>