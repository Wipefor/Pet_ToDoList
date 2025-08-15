<?php
$files = [
    'index.php', 'style.css',
    'config.php',
    'add.php',
    'delete.php', 'complete.php',
    'login.php', 'register.php'
];

$output = '';
foreach ($files as $file) {
    $output .= "// FILE: $file\n";
    $output .= file_get_contents($file)."\n\n";
}

file_put_contents('combined.php', $output);
echo ' code compiled in combined.php'.PHP_EOL;