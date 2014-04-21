<?php
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    include '..' . DIRECTORY_SEPARATOR . $class . '.php';
});