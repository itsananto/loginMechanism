<?php
/**
 * Created by PhpStorm.
 * User: itsan
 * Date: 8/14/2016
 * Time: 11:24 PM
 */

require_once "mysql.php";

class membership{
    function verifyAuthentication($un, $pw){
        $mysql = new mysql();
        $userID = $mysql->verifyAuthentication($un, $pw);

        if($userID>0){
            $_SESSION['status']='authorized';
            $_SESSION['id'] = $userID;
            header('location: index.php');
        }else{
            return 'Please enter valid username and password.';
        }
    }

    function logOutUser(){
        if(isset($_SESSION['status'])){
            unset($_SESSION['status']);
            session_destroy();
        }
    }

    function isAuthenticated(){
        session_start();
        if($_SESSION['status']!='authorized'){
            header('location: login.php');
        }
    }

    function handleFBLogin($id, $name){
        $mysql = new mysql();
        $userID = $mysql->handleFBLogin($id, $name);

        if($userID>0){
            $_SESSION['status']='authorized';
            $_SESSION['id'] = $userID;
            header('location: index.php');
        }else{
            return 'Please enter valid username and password.';
        }
    }

    function handleGoogleLogin($id, $name){
        $mysql = new mysql();
        $userID = $mysql->handleGoogleLogin($id, $name);

        if($userID>0){
            $_SESSION['status']='authorized';
            $_SESSION['id'] = $userID;
            header('location: index.php');
        }else{
            return 'Please enter valid username and password.';
        }
    }
}