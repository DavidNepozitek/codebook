<?php

namespace App\Model;

class ConfigModel extends BaseModel
{

    CONST CONFIG_FILE = "app/config/app-config.json";

    public function getSection($section)
    {

        if (file_exists($this::CONFIG_FILE)) {

            $configJSON = file_get_contents($this::CONFIG_FILE);
            $config = json_decode($configJSON, true);
            
            return $config[$section];
        } else {
            return false;
        }
    }
}