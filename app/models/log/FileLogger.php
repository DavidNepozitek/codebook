<?php

namespace App\Model\Log;

class FileLogger implements ILogger
{
    CONST FILE_PATH = "log/app.log";
    
    public function log($message)
    {
        $date = new \DateTime();

        touch($this::FILE_PATH);

        $log = "[". $date->format("Y-m-d H-i-s") ."] " . $message;

        $fileContents = file_get_contents($this::FILE_PATH);
        file_put_contents($this::FILE_PATH, $log . "\n" . $fileContents);
    }

}