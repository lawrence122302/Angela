<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    require 'inc/vendor/autoload.php';

    use DatabaseBackupManager\MySQLBackup;

    if (isset($_FILES['restore_database']) && $_FILES['restore_database']['error'] == 0)
    {
        $database = $db;
        $user = $uname;
        $pass = $pass;
        $host = $hname;

        try
        {
            // Initialize PDO connection
            $db = new PDO('mysql:host='.$host.';dbname='.$database, $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Create an instance of MySQLBackup
            $mysqlBackup = new MySQLBackup($db);

            // Define the path where the file will be temporarily saved
            $targetDir = RESTORED_PATH; // Ensure this directory is writable
            $targetFile = $targetDir . basename($_FILES['restore_database']['name']);

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['restore_database']['tmp_name'], $targetFile))
            {
                // Restore a database
                $backupFile = $targetFile;
                // Whether to drop existing tables before restoring data
                $restore = $mysqlBackup->restore($backupFile, true); // Default is true

                if($restore)
                {
                    echo 1;
                }
                else
                {
                    echo 0;
                }
            }
            else
            {
                echo "Failed to upload the file.";
            }
        }
        catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }
        catch (Exception $e) {
            echo "Database restoration failed: " . $e->getMessage();
        }
    }
    else {
        echo "No file uploaded or there was an error uploading the file.";
    }
?>