<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    $databases = [$db];
    $user = $uname;
    $pass = $pass;
    $host = $hname;

    if(!file_exists(BACKUP_PATH))
    {
        mkdir(BACKUP_PATH);
    }

    foreach($databases as $database)
    {
        if(!file_exists(BACKUP_PATH.$database))
        {
            mkdir(BACKUP_PATH.$database);
        }

        $filename = $database."_".date("F_d_Y")."@".date("g_ia").uniqid("_", false);
        $folder = BACKUP_PATH.$database."/".$filename.".sql";

        $command = MYSQLDUMP_PATH . " --user={$user} --password={$pass} --host={$host} {$database} --result-file={$folder}";
        exec($command, $output, $return_var);
        if ($return_var != 0) {
            echo 0;
        } else {
            echo 1;
        }
    
    }
?>