<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    if (isset($_FILES['restore_database']) && $_FILES['restore_database']['error'] == 0)
    {
        $databases = ['rbwebsite'];
        $user = 'root';
        $pass = '';
        $host = 'localhost';

        $fileTmpPath = $_FILES['restore_database']['tmp_name'];

        foreach($databases as $database)
        {
            // Drop database
            $dropCommand = MYSQL_PATH . " --user={$user} --password={$pass} --host={$host} -e 'DROP DATABASE {$database}'";
            exec($dropCommand, $dropOutput, $dropReturnVar);

            if ($dropReturnVar != 0) {
                echo 0;
            }    

            // Create database
            $createCommand = "mysql --user={$user} --password={$pass} --host={$host} -e 'CREATE DATABASE {$database}'";
            exec($createCommand, $createOutput, $createReturnVar);

            if ($createReturnVar != 0) {
                echo 0;
            }

            $folder = BACKUP_PATH.$database."/".$filename.".sql";

            $restoreCommand = MYSQL_PATH . " --user={$user} --password={$pass} --database={$database} < {$fileTmpPath}";
            exec($restoreCommand, $restoreOutput, $restoreReturnVar);
            if ($restoreReturnVar != 0) {
                echo 0;
            } else {
                echo 1;
            }
        }
    }
    else {
        echo 0;
    }
?>