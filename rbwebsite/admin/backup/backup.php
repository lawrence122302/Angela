<?php
    // Output file
    $backup_file = 'backup_' . date('Ymd_His') . '.sql';

    // Command
    $command = "mysqldump --user={$user} --password={$pass} --host={$host} {$dbname} > {$backup_file}";

    system($command);

    // Provide feedback
    if (file_exists($backup_file)) {
        echo "Backup successfully created: {$backup_file}";
    } else {
        echo "Backup failed.";
    }
?>