<?php
// Execute shell commands to copy files
$commands = [
    'cp -r public/css/* ../public_html/css',
    'cp -r public/js/* ../public_html/js',
    'cp -r public/admin-assets/js/* ../public_html/admin-assets/js',
    'cp -r public/admin-assets/css/* ../public_html/admin-assets/css',
    'cp -r public/img/icons/* ../public_html/img/icons',
    // 'cp public/favicon.ico ../public_html/favicon.ico',
    'cp -r config/filesystems_base2.php config/filesystems.php',
    'cp -r public/uploads/* ../public_html/uploads',
    'cp -r public/plugins/* ../public_html/plugins',
    'cp -r public/themes/* ../public_html/themes',
];

foreach ($commands as $command) {
    // Execute command
    exec($command, $output, $returnCode);

    // Check if command was successful
    if ($returnCode === 0) {
        echo "Command executed successfully: $command\n";
    } else {
        echo "Error executing command: $command\n";
        // Output any error messages
        foreach ($output as $line) {
            echo "$line\n";
        }
    }
}