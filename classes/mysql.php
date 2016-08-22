<?php
/**
 * Created by PhpStorm.
 * User: itsan
 * Date: 8/14/2016
 * Time: 11:24 PM
 */
require_once "constants.php";

class mysql{
    private $conn;

    function __construct(){
        $this->conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME) or die('Error connecting database');
    }

    function verifyAuthentication($un, $pw){
        $query = "SELECT *
                    FROM USER
                    WHERE USERNAME = '{$un}' AND PASSWORD = '{$pw}'
                    LIMIT 1";
        if ($result = $this->conn->query($query)) {
            $userID = 0;
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $userID = $row['id'];
            }

            /* free result set */
            $result->free();

            return $userID;
        }
    }

    function handleFBLogin($id, $name){
        $query = "CALL user_handleFBLogin('{$name}', '{$id}')";
        if ($result = $this->conn->query($query)) {
            $userID = 0;
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $userID = $row['id'];
            }

            /* free result set */
            $result->free();
            echo $userID;
            return $userID;
        }
    }

    function handleGoogleLogin($id, $name){
        $query = "CALL user_handleGoogleLogin('{$name}', '{$id}')";
        if ($result = $this->conn->query($query)) {
            $userID = 0;
            /* fetch associative array */
            while ($row = $result->fetch_assoc()) {
                $userID = $row['id'];
            }

            /* free result set */
            $result->free();
            echo $userID;
            return $userID;
        }
    }
}