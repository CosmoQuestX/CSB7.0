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
{
    private $host;
    private $user;
    private $password;
    private $database;

    function __construct($host, $user, $password, $database) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;

        $this->conn = $this->connectDB();
    }

    function connectDB() {

        $conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        return $conn;
    }

    function closeDB() {
        mysqli_close($this->conn);
    }

    function runBaseQuery($query) {
        $result = mysqli_query($this->conn,$query);
        while($row=mysqli_fetch_assoc($result)) {
            $resultSet[] = $row;
        }
        if(empty($resultSet))
            die("database error on: $query");

        return $resultSet;
    }

    function runQuery($query) {

        $result = mysqli_query($this->conn, $query);
        echo mysqli_error($this->conn);
        error_log(mysqli_error($this->conn));

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $resultSet[] = $row;
            }
	    return $resultSet;
        }
        else {
            return FALSE;
        }

    }

    function runQueryWhere($query, $param_type, $param_value_array) {

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
        }
        else
            die("SQL error on " . $this->database);
    }

    function bindQueryParams($sql, $param_type, $param_value_array) {
        $param_value_reference[] = & $param_type;
        for($i=0; $i<count($param_value_array); $i++) {
            $param_value_reference[] = & $param_value_array[$i];
        }

        call_user_func_array(array(
            $sql,
            'bind_param'
        ), $param_value_reference);
    }

    function insert($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }

    function update($query, $param_type, $param_value_array) {
        $sql = $this->conn->prepare($query);
        $this->bindQueryParams($sql, $param_type, $param_value_array);
        $sql->execute();
    }

    function getInsertId() {
        return $this->conn->insert_id;
    }

    function getNumRows($param, $where) {
        $sql = "SELECT count(id) as N FROM ".$param." ".$where;

        $result = mysqli_query($this->conn, $sql);
        $row = mysqli_fetch_assoc($result);

        return $row['N'];
    }

    function submitDownload($user_id) {
        $sql = "INSERT INTO data_downloads
                (created_at, updated_at, provider, user_id) 
                VALUES (CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, 'web', $user_id)";
        mysqli_query($this->conn, $sql);
        return $this->conn->insert_id;
    }

    function getDownloads($user_id) {
        $sql = "SELECT link, name FROM data_downloads WHERE user_id = $user_id";
        $result = mysqli_query($this->conn, $sql);
        echo mysqli_error($this->conn);
        error_log(mysqli_error($this->conn));

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $resultSet[] = $row;
            }
            return $resultSet;
        }
        else {
            return FALSE;
        }
    }
}
?>
