<?php

/**
 * Created by PhpStorm.
 * User: starstryder
 * Date: 6/9/19
 * Time: 5:57 PM
 */


// TODO: ADD ERROR CHECKING SO NO LITTLE JOHNNY TABLES FAIL

// Standard "How the hell did you get here?" Redirect to root directory
if (!isset($loader) || !$loader) {
    header($_SERVER['HTTP_HOST']);
    exit();
}


class DB
    /**
     * Main class for database interaction
     */
{
    private $host;
    private $user;
    private $password;
    private $database;

    /**
     * Constructor set up basic variables
     *
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     */
    function __construct($host, $user, $password, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;

        $this->conn = $this->connectDB();
    }

    /**
     * Connect to the database with the initialization parameters
     *
     * @return resource
     */
    function connectDB()
    {

        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        return $conn;
    }

    /**
     * Close the database connection
     *
     * @return void
     */
    function closeDB()
    {
        mysqli_close($this->conn);
    }

    /**
     * Executes a basic query on the database
     *
     * @param string $query
     * @return array an array consisting of an associative array per result row
     */
    function runBaseQuery($query)
    {
        $result = mysqli_query($this->conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $resultSet[] = $row;
        }
        if (empty($resultSet))
            die("database error on: $query");

        return $resultSet;
    }

    /**
     * Executes a query on the database
     *
     * @param string $query
     * @return array|boolean an array consisting of an associative array per
     *    result row or false if the query was unsuccessful
     */
    function runQuery($query)
    {
        if ($result = mysqli_query($this->conn, $query)) {
            error_log(mysqli_error($this->conn));

            if ($result === TRUE) {
                return TRUE;

            } elseif ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $resultSet[] = $row;
                }
                return $resultSet;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    /**
     * Executes a parametrized query on the database
     *
     * @param string $query
     * @param string $param_type
     * @param array $param_value_array
     * @return array|boolean an array consisting of an associative array per
     *    result row or false if the query was unsuccessful
     */
    function runQueryWhere($query, $param_type, $param_value_array)
    {

        if ($sql = $this->conn->prepare($query)) {
            $this->bindQueryParams($sql, $param_type, $param_value_array);
            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $resultSet[] = $row;
                }
            } else {
                return FALSE;
            }

            if (!empty($resultSet)) {
                return $resultSet[0];
            }
        } else
            die("SQL error on " . $this->database);
    }

    /**
     * Reorders the parameter type string with the parameter array to
     * build an sql query that includes the parameters
     *
     * @param string $sql
     * @param string $param_type
     * @param array $param_value_array
     */
    function bindQueryParams($sql, $param_type, $param_value_array)
    {
        $param_value_reference[] = &$param_type;
        for ($i = 0; $i < count($param_value_array); $i++) {
            $param_value_reference[] = &$param_value_array[$i];
        }

        call_user_func_array(array(
            $sql,
            'bind_param'
        ), $param_value_reference);
    }

    /**
     * Executes a query to insert values into the database
     *
     * @param string $query
     * @param string $param_type
     * @param array $param_value_array
     * @return boolean
     */
    function insert($query, $param_type, $param_value_array)
    {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        return $sql->execute();
    }

    /**
     * Executes a query to update values in the database
     *
     * @param string $query
     * @param string $param_type
     * @param array $param_value_array
     * @return boolean
     */
    function update($query, $param_type, $param_value_array)
    {

        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        return $sql->execute();
    }

    /**
     * Returns the auto generated id used in the latest query
     *
     * @return int
     */
    function getInsertId()
    {
        return $this->conn->insert_id;
    }

    /**
     * Returns the number of affected rows for the given parameters
     *
     * @param string $param
     * @param string $where
     * @return int
     */
    function getNumRows($param, $where)
    {
        $sql = "SELECT count(id) as N FROM " . $param . " " . $where;

        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row['N'];
    }

    /**
     * Special function to insert a queued download into the downloads table
     *
     * @param int $user_id
     * @return int
     */
    function submitDownload($user_id)
    {
        $sql = "INSERT INTO data_downloads
                (created_at, updated_at, provider, user_id) 
                VALUES (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'web', $user_id)";
        mysqli_query($this->conn, $sql);
        return $this->conn->insert_id;
    }

    /**
     * Special function to fetch queued downloads for a given user from the
     * downloads table
     *
     * @param int $user_id
     * @return array|boolean Ab array of associative arrays per result row or
     *    false if none exist
     */
    function getDownloads($user_id)
    {
        $sql = "SELECT link, name FROM data_downloads WHERE user_id = $user_id";
        $result = mysqli_query($this->conn, $sql);
        error_log(mysqli_error($this->conn));

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultSet[] = $row;
            }
            return $resultSet;
        } else {
            return FALSE;
        }
    }

    /**
     * Get the stored user configuration for the user with the given id
     *
     * @param int $id
     * @return array|boolean An array of stored values or false if unsuccessful
     */
    function getUser($id)
    {
        $sql = "SELECT * from users WHERE id = $id";
        $result = mysqli_query($this->conn, $sql);
        error_log(mysqli_error($this->conn));

        if ($result->num_rows == 1) {
            while ($row = $result->fetch_assoc()) {
                $resultSet[] = $row;
            }
            return $resultSet[0];
        } else {
            return FALSE;
        }
    }

    /**
     * Gets the user id from the database for a given user name
     *
     * @param string $name
     * @return int|boolean The user id or false if not found
     */
    function getUserIdByName($name)
    {
        $query = "SELECT id from users WHERE name = ?";
        $sql = $this->conn->prepare($query);
        $param_value_array = array($name);
        $param_type = "s";
        $this->bindQueryParams($sql, $param_type, $param_value_array);

        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows == 1) {
            while ($row = $result->fetch_assoc()) {
                $resultSet[] = $row;
            }
            return $resultSet[0]["id"];
        } else {
            return FALSE;
        }
    }

    /**
     * Checks if there is already a user present in the database for a
     * given field content
     * @param string $field The field to query
     * @param string $input The value to compare to
     * @return boolean Whether a user was found or not
     */
    function checkUser($field, $input)
    {
        $query = "SELECT $field FROM users WHERE $field = ?";
        $sql = $this->conn->prepare($query);
        $param_value_array = array($input);
        $param_type = "s";
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $rt = TRUE;
        } else {
            $rt = FALSE;
        }
        return $rt;
    }
}

?>
