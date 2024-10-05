<?php

    require('admin/inc/essentials.php');

    session_name('user_session');
    session_start();
    session_destroy();
    redirect('index.php');

?>