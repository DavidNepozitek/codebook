<?php

namespace App\Models\Log;

class FileLogger implements ILogger
{
    CONST FILE_PATH = "log/app.log";

    public function __construct()
    {
        if (!file_exists($this::FILE_PATH)) {
            touch($this::FILE_PATH);
        }
    }

    public function log($message)
    {
        $date = new \DateTime();

        $log = "[". $date->format("Y-m-d H-i-s") ."] " . $message;

        $fileContents = file_get_contents($this::FILE_PATH);
        file_put_contents($this::FILE_PATH, $log . "\n" . $fileContents);
    }

}