<?php

class AuthHelper
{
    function sign_up($email, $password, $usersFile)
    {

        // add the body of the function based on the guidelines of signup.php
        if (!self::validateSigninInput($email, $password)) {
            return false;
        }
        if (self::userIsBanned($email)) {
            return false;
        }
        // check if the email is in the database already
        if (self::emailExists($email, $usersFile)) {

            header('Location: ./index.php');
        } else {
            // encrypt password
            // save the user in the database
            self::saveNewUser($usersFile, $email, self::encryptPassword($password));
            $_SESSION['newEnrolledUser'] = true;
            return true;
        }
        return false;
    }

    function sign_out()
    {
        $_SESSION['logged']=false;
        session_destroy();
        header('Location: ./index.php');
    }

    function sign_in($usersFile, $bannedUsers, $email, $password)
    {
        if(count($_POST)>0){
            // 1. check if email and password have been submitted
            if(isset($email) && isset($password)) {
            //if(isset($_POST['email']) && isset($_POST['password'])) {
                // 2. check if the email is well formatted
                // 3. check if the password is well formatted
                //$email = $_POST['email'];
                //$password = $_POST['password'];
                if (self::signin($email, $password, $usersFile, $bannedUsers)) {
                    header('Location: ./index.php');
                }
            }
            else if (self::is_logged()) {
                header('Location: ./index.php');
            }
            else $_SESSION['logged']=false;
            header('Location: ./index.php?failedSignin=true');
        }
    }

    function signin($email, $password, $usersFile, $bannedUsersFile){

        if (self::validateSigninInput($email, $password)) {
            if(self::verifyUser($usersFile, $bannedUsersFile, $email)) {
                if (self::verifyPassword($password, $email, $usersFile)) {
                    // 9. store session information
                    $_SESSION['email'] = $email;
                    $_SESSION['password'] = $password;
                    $_SESSION['logged']=true;
                    // 10. redirect the user to the index.php page
                    return true;
                }
            }
        }
        return false;
    }

    function emailExists($email, $usersFile) {

        $usersLine = fopen($usersFile, 'r');  //open for reading
        while( false !== ( $data = fgetcsv($usersLine) ) ) {
            if($data[0] == $email) {
                return true;
            }
        }
        return false;
    }


    function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    function is_logged()
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged']==true) {
            return true;
        }
        return false;
    }

    //validate the email and password
    function validateSigninInput($email, $password) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        //password doesn't contain at least 2 symbols or isn't long enough
        else if (!preg_match('/[\'^£$%&*!()}{@#~?><>,|=_+¬-]/', $password) || strlen($password) < 8)
        {
            return false;
        }
        return true;
    }

    function userIsBanned($email)
    {
        $bannedUsersLine = fopen('../data/banned.csv', 'r');
        while (false !== ($data = fgetcsv($bannedUsersLine))) {
            if ($data[0] == $email) {
                return true;
            }
        }
        return false;
    }

    //check if the file containing banned users exists, check if the email has been banned
    //check if the file containing users exists, check if the email is registered
    function verifyUser($usersFile, $bannedUsersFile, $email) {
        $emailMatch = false;
        if(file_exists($usersFile) && file_exists($bannedUsersFile)) {

            $usersLine = fopen($usersFile, 'r');  //open for reading
            while( false !== ( $data = fgetcsv($usersLine) ) ) {
                if($data[0] == $email) {
                    $emailMatch = true;
                }
            }
            if (self::userIsBanned($email) == true) {
                die('You are banned');
            }
            if ($emailMatch == true) {
                return true;
            }
        }
        else {
            return false;
        }

    }

    function verifyPassword($password, $email, $usersFile) {

        $usersLine = fopen($usersFile, 'r');  //open for reading
        while( false !== ( $data = fgetcsv($usersLine) ) ) {
            if($data[0] == $email) {
                $verify = password_verify($password, $data[1]);
                if ($verify) {
                    return true;
                }
            }
        }
        return false;
    }
}