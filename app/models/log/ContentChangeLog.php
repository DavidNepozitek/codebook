<?php

namespace App\Model\Log;

class contentChangeLog
{
    /**
* 
     *
 * @var ILogger  
*/
    private $logger;

    public function __construct(FileLogger $logger)
    {
        $this->logger = $logger;
    }

    public function logPageChange($name, $email)
    {
        switch ($name) {
        case "about":
            $name = "\"O projektu\"";
            break;
        case "links":
            $name = "\"Odkazy\"";
            break;
        }

        $message = "User " . $email . " changed the content of the page " . $name;

        $this->logger->log($message);
    }
}
