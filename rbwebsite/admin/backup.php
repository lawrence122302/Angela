<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    // mysqldump-php
    include_once('inc/mysqldump-php-master/src/Ifsnop/Mysqldump/Mysqldump.php');

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
        
        try {
            $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.$host.';dbname='.$database, $user, $pass);
            $dump->start($folder);
        
            if (file_exists($folder)) {
                $webPath = str_replace(DOCUMENT_ROOT, '', $folder); // Create the web path from server path
                $output = json_encode(["status" => 1, "file" => SITE_URL . $webPath]);
                echo $output;
            } else {
                $output = json_encode(["status" => 0]);
                echo $output;
            }
        } catch (\Exception $e) {
            echo json_encode(["status" => 0]);
            error_log('mysqldump-php error: ' . $e->getMessage());
        }
    }
?>