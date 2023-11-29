<?php

namespace CosmoQuestX;

class Authorization
{

    /**
     * Compare the user name against the database for a given id
     *
     * @param resource $db
     * @param int $id
     * @param string $name
     * @return boolean
     */
    public static function chk_UserId($db, $id, $name): bool
    {

        $query = "SELECT id, name FROM users WHERE id = ?";
        $params = array($id);
        $result = $db->runQueryWhere($query, "s", $params)[0];

        // strip out any white space and make everything lower case because typing
        $comp = strtolower(trim($result['name'], "\t\n\r\0\x0B"));
        $name = strtolower(trim($name, " \t\n\r\0\x0B"));


        if (!strcmp($comp, $name))
            return TRUE;
        else
            return FALSE;

    }
}
