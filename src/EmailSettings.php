<?php

namespace CosmoQuestX;

class EmailSettings
{

    /**
     * @return array
     */
    public static function getEmailSettings(): array
    {
        $emailSettings = array();

        $emailSettings['host'] = "smtp.gmail.com";
        $emailSettings['username'] = "cosmoquestx@gmail.com";
        $emailSettings['password'] = "TheseRthewayssecurityends-1.2.3.";
        $emailSettings['port'] = "587";  // ssl uses 465
        $emailSettings['from'] = "cosmoquestx@gmail.com";
        return $emailSettings;
    }
}
